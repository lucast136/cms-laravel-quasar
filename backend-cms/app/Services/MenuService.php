<?php

namespace App\Services;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Support\Collection;

class MenuService
{
    public function __construct(
        protected MenuRepositoryInterface $menuRepo
    ) {}

    public function getMenuByLocation(string $location): ?Menu
    {
        return $this->menuRepo->getByLocation($location);
    }

    public function getAllMenus(): Collection
    {
        return $this->menuRepo->getAll();
    }

    public function updateMenuItems(int $menuId, array $items): bool
    {
        return $this->menuRepo->syncMenuItems($menuId, $items);
    }
}
