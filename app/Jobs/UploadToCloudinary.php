<?php

namespace App\Jobs;

use App\Contracts\StorageServiceInterface;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Technology;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class UploadToCloudinary implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [10, 60, 300];

    /**
     * Create a new job instance.
     *
     * @param  string  $filePath  Storage path of the file to upload (e.g. 'temp/abc123.jpg')
     * @param  string  $folder  Cloudinary folder: 'projects', 'technologies', 'settings'
     * @param  string|null  $modelType  DB model type: 'project_image', 'technology', 'settings'
     * @param  int|null  $modelId  Model ID (Technology ID, or parent model ID)
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
    public function handle(StorageServiceInterface $storage): void
    {
        $fullPath = Storage::path($this->filePath);

        $this->markProjectUploadProcessing();

        if (! file_exists($fullPath)) {
            throw new RuntimeException("UploadToCloudinary: File not found at {$fullPath}");
        }

        $result = $storage->upload($fullPath, $this->folder);

        match ($this->modelType) {
            'project_image' => $this->handleProjectImage($result),
            'technology' => $this->handleTechnology($result),
            'settings' => $this->handleSettings($result),
            'contact_attachment' => $this->handleContactAttachment($result),
            default => null,
        };

        $this->markProjectUploadCompleted();

        Storage::delete($this->filePath);
    }

    public function failed(Throwable $exception): void
    {
        report($exception);

        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }

        $this->markProjectUploadFailed($exception);
    }

    private function markProjectUploadProcessing(): void
    {
        if ($this->modelType === 'project_image' && $this->projectId) {
            Project::whereKey($this->projectId)->update(['media_status' => 'processing']);
        }
    }

    private function markProjectUploadCompleted(): void
    {
        $this->updateProjectUploadStatus(false);
    }

    private function markProjectUploadFailed(Throwable $exception): void
    {
        $this->updateProjectUploadStatus(true, $exception->getMessage());
    }

    private function updateProjectUploadStatus(bool $failed, ?string $message = null): void
    {
        if ($this->modelType !== 'project_image' || ! $this->projectId) {
            return;
        }

        DB::transaction(function () use ($failed, $message): void {
            $project = Project::query()->lockForUpdate()->find($this->projectId);
            if (! $project) {
                return;
            }

            $remaining = max(0, $project->pending_uploads - 1);
            $project->update([
                'pending_uploads' => $remaining,
                'media_status' => $failed ? 'failed' : ($remaining > 0 ? 'processing' : 'completed'),
                'media_error' => $failed ? mb_substr((string) $message, 0, 1000) : null,
            ]);
        });
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
            'project_id' => $this->projectId,
            'image_url' => $result['secure_url'],
            'public_id' => $result['public_id'],
            'order_index' => $this->orderIndex ?? ($maxOrder + 1),
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
                app(StorageServiceInterface::class)->delete($technology->logo_public_id);
            } catch (Throwable $e) {
                report($e);
            }
        }

        $technology->update([
            'logo_url' => $result['secure_url'],
            'logo_public_id' => $result['public_id'],
        ]);
    }

    /**
     * Handle upload result for a contact form attachment.
     */
    protected function handleContactAttachment(array $result): void
    {
        if (! $this->modelId) {
            return;
        }

        $message = \App\Models\ContactMessage::find($this->modelId);
        if (! $message) {
            return;
        }

        // Delete old attachment from Cloudinary if exists
        if ($message->attachment_public_id) {
            try {
                app(StorageServiceInterface::class)->delete($message->attachment_public_id);
            } catch (Throwable $e) {
                report($e);
            }
        }

        $message->update([
            'attachment_url' => $result['secure_url'],
            'attachment_public_id' => $result['public_id'],
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
                app(StorageServiceInterface::class)->delete($oldPublicId);
            } catch (Throwable $e) {
                report($e);
            }
        }

        \App\Models\Setting::setValue('logo_url', $result['secure_url']);
        \App\Models\Setting::setValue('logo_public_id', $result['public_id']);
        event(new \App\Events\EntityUpdated('settings'));
    }
}
