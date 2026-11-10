<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy
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

    public function view(?User $user, Media $media): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return $user ? $user->hasPermission('media.upload') : app()->environment('local');
    }

    public function update(?User $user, Media $media): bool
    {
        return $user ? $user->hasPermission('media.upload') : app()->environment('local');
    }

    public function delete(?User $user, Media $media): bool
    {
        return $user ? $user->hasPermission('media.delete') : app()->environment('local');
    }
}
