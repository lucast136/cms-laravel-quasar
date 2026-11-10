<?php

namespace App\Policies;

use App\Models\Content;
use App\Models\User;

class ContentPolicy
{
    /**
     * Intercepta verificaciones: super_admin tiene acceso total.
     */
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

    public function view(?User $user, Content $content): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        if (!$user) {
            return app()->environment('local');
        }

        return $user->hasPermission('pages.create') || $user->hasPermission('posts.create');
    }

    public function update(?User $user, Content $content): bool
    {
        if (!$user) {
            return app()->environment('local');
        }

        return $user->hasPermission('pages.edit')
            || $user->hasPermission('posts.edit')
            || $user->id === $content->author_id;
    }

    public function delete(?User $user, Content $content): bool
    {
        if (!$user) {
            return app()->environment('local');
        }

        return $user->hasPermission('pages.delete') || $user->hasPermission('posts.delete');
    }

    public function restore(?User $user, Content $content): bool
    {
        return $user ? $user->hasRole('super_admin') : app()->environment('local');
    }

    public function forceDelete(?User $user, Content $content): bool
    {
        return $user ? $user->hasRole('super_admin') : app()->environment('local');
    }
}
