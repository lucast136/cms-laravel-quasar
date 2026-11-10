<?php

namespace App\Repositories\Contracts;

use App\Models\ComponentType;
use Illuminate\Support\Collection;

interface ComponentTypeRepositoryInterface
{
    public function getAllActive(?string $category = null): Collection;
    public function findByCode(string $code): ?ComponentType;
    public function findById(int $id): ?ComponentType;
}
