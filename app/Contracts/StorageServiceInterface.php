<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface StorageServiceInterface
{
    /**
     * Upload a file to the storage system.
     *
     * @return array{public_id: string, url: string, secure_url: string}
     */
    public function upload(string|UploadedFile $file, string $folder = 'projects'): array;

    /**
     * Delete a file from the storage system by its public ID.
     */
    public function delete(string $publicId): void;

    /**
     * Get the URL for a stored file.
     */
    public function getUrl(string $publicId, array $options = []): string;

    /**
     * Get a thumbnail URL for a stored file.
     */
    public function getThumbnail(string $publicId, int $width = 400, int $height = 300): string;
}
