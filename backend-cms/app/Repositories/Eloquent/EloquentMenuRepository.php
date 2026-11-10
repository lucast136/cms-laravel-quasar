<?php

namespace App\Repositories\Eloquent;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentMenuRepository implements MenuRepositoryInterface
{
    public function getByLocation(string $location): ?Menu
    {
        return Menu::where('location', $location)
            ->where('is_active', true)
            ->with(['items' => function ($q) {
                $q->whereNull('parent_id')->with('children')->orderBy('sort_order');
            }])
            ->first();
    }

    public function getAll(): Collection
    {
        return Menu::with(['rootItems' => function ($q) {
            $q->with('children')->orderBy('sort_order');
        }])->get();
    }

    public function syncMenuItems(int $menuId, array $items): bool
    {
        return DB::transaction(function () use ($menuId, $items) {
            $menu = Menu::findOrFail($menuId);
            $keptIds = [];
            $tempIdToDbId = [];

            // 1. Eliminar duplicados en el payload entrante por id si vinieran repetidos
            $uniqueIncoming = [];
            $seenIds = [];
            foreach ($items as $rawItem) {
                $rawId = $rawItem['id'] ?? null;
                if ($rawId && in_array($rawId, $seenIds, true)) {
                    continue; // omitir repetido
                }
                if ($rawId) {
                    $seenIds[] = $rawId;
                }
                $uniqueIncoming[] = $rawItem;
            }

            // 2. Primera pasada: procesar o crear elementos raíz (parent_id nulo)
            foreach ($uniqueIncoming as $idx => $itemData) {
                $rawParent = $itemData['parent_id'] ?? null;
                if ($rawParent !== null && $rawParent !== '' && $rawParent !== 0) {
                    continue; // Procesar en segunda pasada
                }

                $rawId = $itemData['id'] ?? null;
                $isRealExistingId = is_numeric($rawId) && $rawId > 0 && $rawId < 1000000000000;

                $payload = [
                    'menu_id' => $menuId,
                    'parent_id' => null,
                    'content_id' => $itemData['content_id'] ?? null,
                    'title' => $itemData['title'] ?? 'Enlace',
                    'url' => $itemData['url'] ?? '#',
                    'target' => $itemData['target'] ?? '_self',
                    'icon_class' => $itemData['icon_class'] ?? null,
                    'sort_order' => $idx + 1,
                ];

                if ($isRealExistingId) {
                    $item = MenuItem::where('menu_id', $menuId)->where('id', $rawId)->first();
                    if ($item) {
                        $item->update($payload);
                        $keptIds[] = $item->id;
                        $tempIdToDbId[$rawId] = $item->id;
                    } else {
                        $newItem = MenuItem::create($payload);
                        $keptIds[] = $newItem->id;
                        $tempIdToDbId[$rawId] = $newItem->id;
                    }
                } else {
                    $newItem = MenuItem::create($payload);
                    $keptIds[] = $newItem->id;
                    if ($rawId) {
                        $tempIdToDbId[$rawId] = $newItem->id;
                    }
                }
            }

            // 3. Segunda pasada: procesar elementos hijos (con parent_id)
            foreach ($uniqueIncoming as $idx => $itemData) {
                $rawParent = $itemData['parent_id'] ?? null;
                if ($rawParent === null || $rawParent === '' || $rawParent === 0) {
                    continue; // Ya procesado
                }

                // Resolver parent_id real
                $resolvedParentId = $tempIdToDbId[$rawParent] ?? (is_numeric($rawParent) ? (int)$rawParent : null);

                $rawId = $itemData['id'] ?? null;
                $isRealExistingId = is_numeric($rawId) && $rawId > 0 && $rawId < 1000000000000;

                $payload = [
                    'menu_id' => $menuId,
                    'parent_id' => $resolvedParentId,
                    'content_id' => $itemData['content_id'] ?? null,
                    'title' => $itemData['title'] ?? 'Enlace',
                    'url' => $itemData['url'] ?? '#',
                    'target' => $itemData['target'] ?? '_self',
                    'icon_class' => $itemData['icon_class'] ?? null,
                    'sort_order' => $idx + 1,
                ];

                if ($isRealExistingId) {
                    $item = MenuItem::where('menu_id', $menuId)->where('id', $rawId)->first();
                    if ($item) {
                        $item->update($payload);
                        $keptIds[] = $item->id;
                        $tempIdToDbId[$rawId] = $item->id;
                    } else {
                        $newItem = MenuItem::create($payload);
                        $keptIds[] = $newItem->id;
                        $tempIdToDbId[$rawId] = $newItem->id;
                    }
                } else {
                    $newItem = MenuItem::create($payload);
                    $keptIds[] = $newItem->id;
                    if ($rawId) {
                        $tempIdToDbId[$rawId] = $newItem->id;
                    }
                }
            }

            MenuItem::where('menu_id', $menuId)->whereNotIn('id', $keptIds)->delete();
            return true;
        });
    }
}
