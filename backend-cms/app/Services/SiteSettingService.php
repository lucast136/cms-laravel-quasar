<?php

namespace App\Services;

use App\Repositories\Contracts\SiteSettingRepositoryInterface;

class SiteSettingService
{
    public function __construct(
        protected SiteSettingRepositoryInterface $settingRepo
    ) {}

    public function getPublicSettings(): array
    {
        return $this->settingRepo->getPublicSettings();
    }

    public function getAllGrouped(): array
    {
        return $this->settingRepo->getAllGrouped();
    }

    public function saveSettings(array $groupedSettings): bool
    {
        foreach ($groupedSettings as $group => $settings) {
            if (is_array($settings)) {
                $this->settingRepo->updateGroup($group, $settings);
            }
        }
        return true;
    }
}
