<?php

namespace App\Models;

use App\Contracts\StorageServiceInterface;
use App\Enums\ProjectCategory;
use App\Jobs\UploadToCloudinary;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $category
 * @property string $desc
 * @property string|null $icon
 * @property string|null $project_url
 * @property bool $is_active
 */
#[TypeScript]
class Project extends Model
{
    use HasFactory, LogActivity;

    protected $fillable = [
        'name', 'slug', 'category', 'desc',
        'icon', 'project_url', 'is_active',
        'media_status', 'pending_uploads', 'media_error',
    ];

    protected $casts = [
        'category' => ProjectCategory::class,
        'is_active' => 'boolean',
        'pending_uploads' => 'integer',
    ];

    public function setAttribute($key, $value): static
    {
        if ($key === 'category') {
            if ($value instanceof ProjectCategory) {
                $value = $value->value;
            } elseif (is_string($value)) {
                $value = strtolower($value);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Use slug for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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

        $this->increment('pending_uploads');
        $this->update(['media_status' => 'pending', 'media_error' => null]);

        try {
            UploadToCloudinary::dispatch(
                filePath: $path,
                folder: 'projects',
                modelType: 'project_image',
                projectId: $this->id,
                orderIndex: ($this->images()->max('order_index') ?? -1) + 1,
            );
        } catch (\Throwable $exception) {
            $this->decrement('pending_uploads');
            $this->update(['media_status' => 'failed', 'media_error' => $exception->getMessage()]);

            throw $exception;
        }
    }

    public function deleteImage(int $imageId): void
    {
        $image = $this->images()->find($imageId);
        if ($image && $image->public_id) {
            app(StorageServiceInterface::class)->delete($image->public_id);
            $image->delete();
        }
    }

    public function deleteAllImages(): void
    {
        foreach ($this->images as $image) {
            if ($image->public_id) {
                app(StorageServiceInterface::class)->delete($image->public_id);
            }

            // Commit each successful remote deletion locally. If a later deletion
            // fails, no database row remains pointing to an already removed asset.
            $image->delete();
        }
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    // boot() logic moved to ProjectObserver
}
