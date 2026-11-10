<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    public function before(?User $user, string $ability): ?bool
    {
        if ($user && $user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Menu $menu): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return $user ? $user->hasPermission('menus.manage') : app()->environment('local');
    }

    public function update(?User $user, Menu $menu): bool
    {
        return $user ? $user->hasPermission('menus.manage') : app()->environment('local');
    }

    public function delete(?User $user, Menu $menu): bool
    {
        return $user ? $user->hasPermission('menus.manage') : app()->environment('local');
    }
}
