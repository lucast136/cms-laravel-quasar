<?php

namespace App\Policies;

use App\Models\ComponentType;
use App\Models\User;

class ComponentTypePolicy
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
        return true; // Permitir listar catálogo de componentes al constructor
    }

    public function view(?User $user, ComponentType $componentType): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, ComponentType $componentType): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, ComponentType $componentType): bool
    {
        return $user->hasRole('admin') && !$componentType->is_system;
    }
}
