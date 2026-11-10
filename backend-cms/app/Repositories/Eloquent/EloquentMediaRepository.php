<?php

namespace App\Repositories\Eloquent;

use App\Models\Media;
use App\Repositories\Contracts\MediaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentMediaRepository implements MediaRepositoryInterface
{
    public function getPaginated(int $perPage = 20, ?string $search = null, ?string $type = null): LengthAwarePaginator
    {
        $query = Media::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('filename', 'like', "%{$search}%");
            });
        }

        if ($type && $type !== 'all') {
            if ($type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($type === 'pdf') {
                $query->where('mime_type', 'like', '%pdf%');
            }
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Media
    {
        return Media::create($data);
    }

    public function delete(int $id): bool
    {
        $media = Media::find($id);
        if (!$media) return false;
        return $media->delete();
    }
}
