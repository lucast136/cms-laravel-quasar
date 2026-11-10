<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SiteSettingRepositoryInterface
{
    public function getPublicSettings(): array;
    public function getAllGrouped(): array;
    public function updateGroup(string $group, array $settings): bool;
}
