<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Orion\Http\Controllers\Controller;

class MenuController extends Controller
{
    protected $model = Menu::class;

    public function includes(): array
    {
        return ['items', 'rootItems', 'rootItems.children'];
    }

    /**
     * Sincronización en masa de items del menú
     */
    public function syncItems(int $id, HttpRequest $request, MenuService $menuService): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'present|array',
            'items.*.id' => 'nullable',
            'items.*.parent_id' => 'nullable|integer',
            'items.*.content_id' => 'nullable|integer',
            'items.*.title' => 'required|string',
            'items.*.url' => 'required|string',
            'items.*.target' => 'nullable|string',
            'items.*.icon_class' => 'nullable|string',
            'items.*.sort_order' => 'nullable|integer',
        ]);

        $menuService->updateMenuItems($id, $validated['items']);
        $menu = $menuService->getMenuByLocation(Menu::findOrFail($id)->location);

        return response()->json([
            'success' => true,
            'message' => 'Elementos del menú actualizados.',
            'data' => $menu,
        ]);
    }
}
