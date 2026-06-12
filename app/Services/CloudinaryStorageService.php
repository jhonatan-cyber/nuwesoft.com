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
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => config('cloudinary.secure', true),
            ],
        ]);
    }

    public function upload(string|UploadedFile $file, string $folder = 'projects'): array
    {
        $path = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $result = $this->cloudinary->uploadApi()->upload($path, [
            'folder' => $folder,
            'transformation' => [
                ['width' => 1200, 'height' => 800, 'crop' => 'fill', 'quality' => 'auto', 'fetch_format' => 'auto'],
            ],
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
