<?php

namespace App\Repositories\Contracts;

use App\Models\Menu;
use Illuminate\Support\Collection;

interface MenuRepositoryInterface
{
    public function getByLocation(string $location): ?Menu;
    public function getAll(): Collection;
    public function syncMenuItems(int $menuId, array $items): bool;
}
