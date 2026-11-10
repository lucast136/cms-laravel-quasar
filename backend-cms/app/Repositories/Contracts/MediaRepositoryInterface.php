<?php

namespace App\Repositories\Contracts;

use App\Models\Media;
use Illuminate\Pagination\LengthAwarePaginator;

interface MediaRepositoryInterface
{
    public function getPaginated(int $perPage = 20, ?string $search = null, ?string $type = null): LengthAwarePaginator;
    public function create(array $data): Media;
    public function delete(int $id): bool;
}
