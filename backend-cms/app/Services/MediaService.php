<?php

namespace App\Services;

use App\Models\Media;
use App\Repositories\Contracts\MediaRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaService
{
    public function __construct(
        protected MediaRepositoryInterface $mediaRepo
    ) {}

    public function getMediaList(int $perPage = 20, ?string $search = null, ?string $type = null): LengthAwarePaginator
    {
        return $this->mediaRepo->getPaginated($perPage, $search, $type);
    }

    public function uploadFile(UploadedFile $file, ?int $userId = null, ?string $altText = null): Media
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $safeName = Str::slug($originalName ?: 'archivo') . '-' . time() . '.' . $extension;

        $path = $file->storeAs('uploads', $safeName, 'public');

        // Garantizar acceso público directo en Windows aunque no exista symlink de administrador
        $publicUploadsDir = public_path('storage/uploads');
        if (!is_link(public_path('storage'))) {
            if (!File::exists($publicUploadsDir)) {
                File::makeDirectory($publicUploadsDir, 0755, true);
            }
            $sourceFile = storage_path('app/public/' . $path);
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $publicUploadsDir . DIRECTORY_SEPARATOR . $safeName);
            }
        }

        $dimensions = null;
        if (str_starts_with((string) $file->getMimeType(), 'image/')) {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo) {
                $dimensions = [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1],
                ];
            }
        }

        return $this->mediaRepo->create([
            'user_id' => $userId,
            'title' => ucwords(str_replace('-', ' ', Str::slug($originalName ?: 'archivo'))),
            'alt_text' => $altText ?: $originalName,
            'filename' => $safeName,
            'filepath' => '/storage/' . $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'file_size' => $file->getSize(),
            'dimensions' => $dimensions,
        ]);
    }

    public function deleteFile(int $id): bool
    {
        return $this->mediaRepo->delete($id);
    }
}
