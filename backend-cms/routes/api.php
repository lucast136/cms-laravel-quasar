<?php

use App\Http\Controllers\Api\V1\Admin\ComponentTypeController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\MediaController;
use App\Http\Controllers\Api\V1\Admin\MenuController;
use App\Http\Controllers\Api\V1\Admin\PageController;
use App\Http\Controllers\Api\V1\Admin\PageSectionSyncController;
use App\Http\Controllers\Api\V1\Admin\PostController;
use App\Http\Controllers\Api\V1\Admin\SettingController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PublicController;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::prefix('v1')->group(function () {
    // -------------------------------------------------------------
    // 1. Rutas Públicas (Optimizadas para Quasar SSR y Motores SEO)
    // -------------------------------------------------------------
    Route::get('/pages/{slug}', [PublicController::class, 'showPage']);
    Route::get('/posts', [PublicController::class, 'listPosts']);
    Route::get('/posts/{slug}', [PublicController::class, 'showPost']);
    Route::get('/settings/public', [PublicController::class, 'publicSettings']);
    Route::get('/menus/{location}', [PublicController::class, 'getMenu']);
    Route::post('/forms/submit', [PublicController::class, 'submitForm']);

    // -------------------------------------------------------------
    // 2. Autenticación Laravel Sanctum
    // -------------------------------------------------------------
    Route::post('/auth/login', [AuthController::class, 'login']);

    // -------------------------------------------------------------
    // 3. Panel de Administración Protegido por Sanctum
    // -------------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/admin/dashboard/stats', [DashboardController::class, 'stats']);

        // Endpoints RESTful con Laravel Orion
        Orion::resource('admin/pages', PageController::class);
        Orion::resource('admin/posts', PostController::class);
        Orion::resource('admin/component-types', ComponentTypeController::class);
        Orion::resource('admin/media', MediaController::class);
        Orion::resource('admin/menus', MenuController::class);
        Orion::resource('admin/users', UserController::class);
        Route::get('/admin/roles', [UserController::class, 'roles']);

        // Operaciones personalizadas con el patrón Service-Repository
        Route::put('/admin/contents/{id}/sections', [PageSectionSyncController::class, 'sync']);
        Route::post('/admin/media/upload', [MediaController::class, 'upload']);
        Route::put('/admin/menus/{id}/items', [MenuController::class, 'syncItems']);

        // Configuración Global
        Route::get('/admin/settings', [SettingController::class, 'index']);
        Route::put('/admin/settings', [SettingController::class, 'update']);
    });
});
