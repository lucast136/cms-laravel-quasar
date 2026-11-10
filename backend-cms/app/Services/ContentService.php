<?php

namespace App\Services;

use App\Models\Content;
use App\Repositories\Contracts\ContentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ContentService
{
    public function __construct(
        protected ContentRepositoryInterface $contentRepo
    ) {}

    public function getPages(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        return $this->contentRepo->getPaginatedPages($perPage, $search, $status);
    }

    public function getPosts(int $perPage = 10, ?string $search = null, ?string $category = null): LengthAwarePaginator
    {
        return $this->contentRepo->getPaginatedPosts($perPage, $search, $category);
    }

    public function getPageById(int $id): ?Content
    {
        return $this->contentRepo->findById($id, ['sections.componentType', 'author']);
    }

    public function getPageBySlug(string $slug): ?Content
    {
        return $this->contentRepo->findBySlug($slug, 'page', ['sections.componentType', 'author']);
    }

    public function createPage(array $data, int $authorId): Content
    {
        $data['author_id'] = $authorId;
        $data['type'] = 'page';
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        return $this->contentRepo->create($data);
    }

    public function updatePage(int $id, array $data): bool
    {
        return $this->contentRepo->update($id, $data);
    }

    public function deletePage(int $id): bool
    {
        return $this->contentRepo->delete($id);
    }

    public function syncPageSections(int $pageId, array $sections): Collection
    {
        return $this->contentRepo->syncSections($pageId, $sections);
    }
}
