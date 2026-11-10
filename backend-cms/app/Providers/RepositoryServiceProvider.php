<?php

namespace App\Providers;

use App\Repositories\Contracts\ComponentTypeRepositoryInterface;
use App\Repositories\Contracts\ContentRepositoryInterface;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\SiteSettingRepositoryInterface;
use App\Repositories\Eloquent\EloquentComponentTypeRepository;
use App\Repositories\Eloquent\EloquentContentRepository;
use App\Repositories\Eloquent\EloquentMediaRepository;
use App\Repositories\Eloquent\EloquentMenuRepository;
use App\Repositories\Eloquent\EloquentSiteSettingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContentRepositoryInterface::class, EloquentContentRepository::class);
        $this->app->bind(ComponentTypeRepositoryInterface::class, EloquentComponentTypeRepository::class);
        $this->app->bind(SiteSettingRepositoryInterface::class, EloquentSiteSettingRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, EloquentMediaRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, EloquentMenuRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
