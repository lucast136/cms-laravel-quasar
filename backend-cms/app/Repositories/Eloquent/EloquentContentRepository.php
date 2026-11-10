<?php

namespace App\Repositories\Eloquent;

use App\Models\Content;
use App\Models\ContentSection;
use App\Repositories\Contracts\ContentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentContentRepository implements ContentRepositoryInterface
{
    public function getPaginatedPages(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = Content::query()->where('type', 'page')->withCount('sections')->latest('updated_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    public function getPaginatedPosts(int $perPage = 10, ?string $search = null, ?string $category = null): LengthAwarePaginator
    {
        $query = Content::query()->where('type', 'post')->with(['author', 'terms'])->latest('published_at');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($category && $category !== 'all') {
            $query->whereHas('terms', function ($q) use ($category) {
                $q->where('slug', $category)->orWhere('name', $category);
            });
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id, array $with = []): ?Content
    {
        return Content::with($with)->find($id);
    }

    public function findBySlug(string $slug, string $type = 'page', array $with = []): ?Content
    {
        return Content::where('slug', $slug)->where('type', $type)->with($with)->first();
    }

    public function create(array $data): Content
    {
        return Content::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $content = Content::find($id);
        if (!$content) return false;
        return $content->update($data);
    }

    public function delete(int $id): bool
    {
        $content = Content::find($id);
        if (!$content) return false;
        return $content->delete();
    }

    /**
     * Sincroniza en masa las secciones del Page Builder bajo una transacción de base de datos
     */
    public function syncSections(int $contentId, array $sections): Collection
    {
        return DB::transaction(function () use ($contentId, $sections) {
            $content = Content::findOrFail($contentId);
            $keptSectionIds = [];

            foreach ($sections as $index => $sectionData) {
                $sectionId = !empty($sectionData['id']) && is_numeric($sectionData['id']) && $sectionData['id'] < 1000000000000
                    ? $sectionData['id']
                    : null;

                $payload = [
                    'content_id' => $contentId,
                    'component_type_id' => $sectionData['component_type_id'] ?? null,
                    'component_name' => $sectionData['component_name'] ?? 'Section',
                    'sort_order' => $index + 1,
                    'is_active' => $sectionData['is_active'] ?? true,
                    'settings' => $sectionData['settings'] ?? [],
                    'data' => $sectionData['data'] ?? [],
                ];

                if ($sectionId) {
                    $section = ContentSection::where('content_id', $contentId)->where('id', $sectionId)->first();
                    if ($section) {
                        $section->update($payload);
                        $keptSectionIds[] = $section->id;
                    } else {
                        $newSection = ContentSection::create($payload);
                        $keptSectionIds[] = $newSection->id;
                    }
                } else {
                    $newSection = ContentSection::create($payload);
                    $keptSectionIds[] = $newSection->id;
                }
            }

            // Eliminar secciones eliminadas en el constructor
            ContentSection::where('content_id', $contentId)
                ->whereNotIn('id', $keptSectionIds)
                ->delete();

            $content->touch(); // Actualiza updated_at de la página

            return $content->sections()->with('componentType')->get();
        });
    }
}
