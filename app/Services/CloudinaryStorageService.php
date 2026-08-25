<?php

namespace App\Services;

use App\Contracts\StorageServiceInterface;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Illuminate\Http\UploadedFile;

class CloudinaryStorageService implements StorageServiceInterface
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $cloudName = config('cloudinary.cloud_name');
        $apiKey = config('cloudinary.api_key');
        $apiSecret = config('cloudinary.api_secret');

        if (empty($cloudName) && app()->environment('local', 'testing')) {
            // Fail-fast en dev para detectar .env mal configurado (no bloquea prod si usa CLOUDINARY_URL)
            logger()->warning('Cloudinary cloud_name vacío — verifica CLOUDINARY_CLOUD_NAME o CLOUDINARY_URL en .env');
        }

        if (empty($cloudName)) {
            // En testing se permite cloud_name vacío (tests mockean el servicio)
            if (app()->environment('testing')) {
                $cloudName = 'test-cloud';
            }
        }

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => config('cloudinary.secure', true),
            ],
        ]);
    }

    /**
     * Prefijo por entorno: nuwesoft/local/projects, etc.
     */
    public function prefixedFolder(string $folder): string
    {
        $prefix = trim((string) config('cloudinary.folder_prefix', ''), '/');

        return $prefix ? $prefix . '/' . ltrim($folder, '/') : $folder;
    }

    public function upload(string|UploadedFile $file, string $folder = 'projects'): array
    {
        $path = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $isProjectImage = trim($folder, '/') === 'projects';
        $folder = $this->prefixedFolder($folder);
        $result = $this->cloudinary->uploadApi()->upload($path, [
            'folder' => $folder,
            'transformation' => $isProjectImage
                ? [['width' => 1600, 'crop' => 'limit', 'quality' => 'auto:good', 'fetch_format' => 'auto']]
                : [['width' => 1200, 'height' => 800, 'crop' => 'fill', 'quality' => 'auto', 'fetch_format' => 'auto']],
        ]);

        return [
            'public_id' => $result['public_id'],
            'url' => $result['secure_url'],
            'secure_url' => $result['secure_url'],
        ];
    }

    public function delete(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId);
    }

    public function getUrl(string $publicId, array $options = []): string
    {
        return $this->cloudinary->image($publicId)->toUrl($options);
    }

    public function getThumbnail(string $publicId, int $width = 400, int $height = 300): string
    {
        return $this->cloudinary->image($publicId)
            ->resize(Resize::fill($width, $height))
            ->toUrl();
    }
}
