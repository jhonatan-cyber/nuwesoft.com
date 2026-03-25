<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'stack',
        'desc',
        'icon',
        'image_url',
        'project_url',
        'is_active',
    ];

    protected $casts = [
        'stack' => 'array',
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order_index');
    }

    public function uploadImage(string|UploadedFile $file): ProjectImage
    {
        $cloudinary = new CloudinaryService;
        $result = $cloudinary->upload($file, 'projects');

        return $this->images()->create([
            'image_url' => $result['secure_url'],
            'public_id' => $result['public_id'],
            'order_index' => ($this->images()->max('order_index') ?? -1) + 1,
        ]);
    }

    public function deleteImage(int $imageId): void
    {
        $image = $this->images()->find($imageId);
        if ($image && $image->public_id) {
            $cloudinary = new CloudinaryService;
            $cloudinary->delete($image->public_id);
            $image->delete();
        }
    }

    public function deleteAllImages(): void
    {
        foreach ($this->images as $image) {
            if ($image->public_id) {
                $cloudinary = new CloudinaryService;
                $cloudinary->delete($image->public_id);
            }
        }
        $this->images()->delete();
    }

    protected function getPublicIdFromUrl(string $url): ?string
    {
        $parts = explode('/upload/', $url);
        if (count($parts) === 2) {
            $path = pathinfo($parts[1], PATHINFO_FILENAME);

            return 'projects/'.$path;
        }

        return null;
    }
    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }
}
