<?php

namespace App\Repositories\Eloquent;

use App\Models\SiteSetting;
use App\Repositories\Contracts\SiteSettingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentSiteSettingRepository implements SiteSettingRepositoryInterface
{
    public function getPublicSettings(): array
    {
        $settings = SiteSetting::where('is_public', true)->get();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->group_name][$setting->setting_key] = $setting->setting_value;
        }
        return $result;
    }

    public function getAllGrouped(): array
    {
        $settings = SiteSetting::all();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->group_name][$setting->setting_key] = $setting->setting_value;
        }
        return $result;
    }

    public function updateGroup(string $group, array $settings): bool
    {
        return DB::transaction(function () use ($group, $settings) {
            foreach ($settings as $key => $val) {
                SiteSetting::updateOrCreate(
                    ['setting_key' => $key],
                    [
                        'group_name' => $group,
                        'setting_value' => $val,
                        'is_public' => true,
                    ]
                );
            }
            return true;
        });
    }
}
