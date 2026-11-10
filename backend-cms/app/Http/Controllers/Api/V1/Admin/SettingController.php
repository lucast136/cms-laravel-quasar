<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(
        protected SiteSettingService $settingService
    ) {}

    public function index(): JsonResponse
    {
        $settings = $this->settingService->getAllGrouped();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $payload = $request->all();
        $this->settingService->saveSettings($payload);

        return response()->json([
            'success' => true,
            'message' => 'Configuraciones guardadas exitosamente.',
            'data' => $this->settingService->getAllGrouped(),
        ]);
    }
}
