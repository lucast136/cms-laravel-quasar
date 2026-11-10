<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageSectionSyncController extends Controller
{
    public function __construct(
        protected ContentService $contentService
    ) {}

    /**
     * Sincroniza todas las secciones del Page Builder para una página
     */
    public function sync(int $id, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sections' => 'present|array',
            'sections.*.id' => 'nullable',
            'sections.*.component_type_id' => 'nullable|integer',
            'sections.*.component_name' => 'required|string',
            'sections.*.settings' => 'nullable|array',
            'sections.*.data' => 'nullable|array',
            'sections.*.sort_order' => 'nullable|integer',
            'sections.*.is_active' => 'nullable|boolean',
        ]);

        $sections = $this->contentService->syncPageSections($id, $validated['sections']);

        return response()->json([
            'success' => true,
            'message' => 'Secciones de la página sincronizadas correctamente.',
            'data' => $sections,
        ]);
    }
}
