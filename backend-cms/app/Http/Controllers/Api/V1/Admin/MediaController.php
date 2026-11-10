<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Orion\Http\Controllers\Controller;

class MediaController extends Controller
{
    protected $model = Media::class;

    public function searchableBy(): array
    {
        return ['title', 'filename', 'alt_text'];
    }

    public function filterableBy(): array
    {
        return ['mime_type', 'disk'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'file_size', 'title'];
    }

    /**
     * Endpoint personalizado para subida de archivos (QUploader)
     */
    public function upload(HttpRequest $request, MediaService $mediaService): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB máx
            'alt_text' => 'nullable|string|max:255',
        ]);

        $media = $mediaService->uploadFile(
            $request->file('file'),
            auth()->id() ?: 1,
            $request->input('alt_text')
        );

        return response()->json([
            'success' => true,
            'message' => 'Archivo subido correctamente.',
            'data' => $media,
        ], 201);
    }
}
