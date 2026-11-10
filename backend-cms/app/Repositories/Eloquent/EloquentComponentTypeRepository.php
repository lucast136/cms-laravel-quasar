<?php

namespace App\Repositories\Eloquent;

use App\Models\ComponentType;
use App\Repositories\Contracts\ComponentTypeRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentComponentTypeRepository implements ComponentTypeRepositoryInterface
{
    public function getAllActive(?string $category = null): Collection
    {
        $query = ComponentType::where('is_active', true);

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        return $query->get();
    }

    public function findByCode(string $code): ?ComponentType
    {
        return ComponentType::where('code', $code)->first();
    }

    public function findById(int $id): ?ComponentType
    {
        return ComponentType::find($id);
    }
}
