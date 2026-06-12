<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Technology;
use App\Services\CloudinaryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class UploadToCloudinary implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param  string  $filePath  Storage path of the file to upload (e.g. 'temp/abc123.jpg')
     * @param  string  $folder    Cloudinary folder: 'projects', 'technologies', 'settings'
     * @param  string|null  $modelType  DB model type: 'project_image', 'technology', 'settings'
     * @param  int|null  $modelId   Model ID (Technology ID, or parent model ID)
     * @param  int|null  $projectId  For project_image: the parent Project ID
     * @param  int|null  $orderIndex  For project_image: the display order
     */
    public function __construct(
        public string $filePath,
        public string $folder = 'projects',
        public ?string $modelType = null,
        public ?int $modelId = null,
        public ?int $projectId = null,
        public ?int $orderIndex = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CloudinaryService $cloudinary): void
    {
        $fullPath = Storage::path($this->filePath);

        if (! file_exists($fullPath)) {
            report(new \Exception("UploadToCloudinary: File not found at {$fullPath}"));
            return;
        }

        try {
            $result = $cloudinary->upload($fullPath, $this->folder);

            match ($this->modelType) {
                'project_image' => $this->handleProjectImage($result),
                'technology'    => $this->handleTechnology($result),
                'settings'      => $this->handleSettings($result),
                default         => null,
            };
        } catch (\Throwable $e) {
            report($e);
        } finally {
            // Clean up temporary file
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    /**
     * Handle upload result for a project image.
     */
    protected function handleProjectImage(array $result): void
    {
        if (! $this->projectId) {
            return;
        }

        $project = Project::find($this->projectId);
        if (! $project) {
            return;
        }

        $maxOrder = $project->images()->max('order_index') ?? -1;

        ProjectImage::create([
            'project_id'   => $this->projectId,
            'image_url'    => $result['secure_url'],
            'public_id'    => $result['public_id'],
            'order_index'  => $this->orderIndex ?? ($maxOrder + 1),
        ]);
    }

    /**
     * Handle upload result for a technology logo.
     */
    protected function handleTechnology(array $result): void
    {
        if (! $this->modelId) {
            return;
        }

        $technology = Technology::find($this->modelId);
        if (! $technology) {
            return;
        }

        // Delete old logo from Cloudinary if exists
        if ($technology->logo_public_id) {
            try {
                app(CloudinaryService::class)->delete($technology->logo_public_id);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $technology->update([
            'logo_url'       => $result['secure_url'],
            'logo_public_id' => $result['public_id'],
        ]);
    }

    /**
     * Handle upload result for a settings logo.
     */
    protected function handleSettings(array $result): void
    {
        // Delete old logo from Cloudinary if exists
        $oldPublicId = \App\Models\Setting::getValue('logo_public_id');
        if ($oldPublicId) {
            try {
                app(CloudinaryService::class)->delete($oldPublicId);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        \App\Models\Setting::setValue('logo_url', $result['secure_url']);
        \App\Models\Setting::setValue('logo_public_id', $result['public_id']);
        event(new \App\Events\EntityUpdated('settings'));
    }
}
