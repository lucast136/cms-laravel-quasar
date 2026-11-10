<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
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

    public function view(?User $user, User $model): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return $user ? $user->hasRole('super_admin') : app()->environment('local');
    }

    public function update(?User $user, User $model): bool
    {
        return $user ? ($user->hasRole('super_admin') || $user->id === $model->id) : app()->environment('local');
    }

    public function delete(?User $user, User $model): bool
    {
        return $user ? ($user->hasRole('super_admin') && $user->id !== $model->id) : app()->environment('local');
    }
}

