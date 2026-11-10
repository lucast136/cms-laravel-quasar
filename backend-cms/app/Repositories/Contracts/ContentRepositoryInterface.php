<?php

namespace App\Repositories\Contracts;

use App\Models\Content;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContentRepositoryInterface
{
    public function getPaginatedPages(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator;
    public function getPaginatedPosts(int $perPage = 10, ?string $search = null, ?string $category = null): LengthAwarePaginator;
    public function findById(int $id, array $with = []): ?Content;
    public function findBySlug(string $slug, string $type = 'page', array $with = []): ?Content;
    public function create(array $data): Content;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function syncSections(int $contentId, array $sections): Collection;
}
