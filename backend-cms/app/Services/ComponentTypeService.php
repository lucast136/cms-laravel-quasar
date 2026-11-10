<?php

namespace App\Services;

use App\Models\ComponentType;
use App\Repositories\Contracts\ComponentTypeRepositoryInterface;
use Illuminate\Support\Collection;

class ComponentTypeService
{
    public function __construct(
        protected ComponentTypeRepositoryInterface $componentTypeRepo
    ) {}

    public function getCatalog(?string $category = null): Collection
    {
        return $this->componentTypeRepo->getAllActive($category);
    }

    public function getByCode(string $code): ?ComponentType
    {
        return $this->componentTypeRepo->findByCode($code);
    }
}
