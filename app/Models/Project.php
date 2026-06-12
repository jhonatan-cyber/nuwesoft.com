<?php

namespace App\Models;

use App\Jobs\UploadToCloudinary;
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
        'desc',
        'icon',
        'project_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order_index');
    }

    public function uploadImage(string|UploadedFile $file): void
    {
        // Save file to temporary storage and dispatch the Cloudinary upload job
        $path = $file instanceof UploadedFile
            ? $file->store('temp/uploads')
            : $file;

        UploadToCloudinary::dispatch(
            filePath: $path,
            folder: 'projects',
            modelType: 'project_image',
            projectId: $this->id,
            orderIndex: ($this->images()->max('order_index') ?? -1) + 1,
        );
    }

    public function deleteImage(int $imageId): void
    {
        $image = $this->images()->find($imageId);
        if ($image && $image->public_id) {
            try {
                app(CloudinaryService::class)->delete($image->public_id);
            } catch (\Throwable $e) {
                report($e);
            }
            $image->delete();
        }
    }

    public function deleteAllImages(): void
    {
        foreach ($this->images as $image) {
            if ($image->public_id) {
                try {
                    app(CloudinaryService::class)->delete($image->public_id);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }
        $this->images()->delete();
    }
    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }

    protected static function booted()
    {
        static::saved(fn () => event(new \App\Events\EntityUpdated('project')));
        static::deleted(fn () => event(new \App\Events\EntityUpdated('project')));
    }
}
