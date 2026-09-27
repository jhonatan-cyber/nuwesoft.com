<?php

namespace Tests\Feature;

use App\Contracts\StorageServiceInterface;
use App\Jobs\UploadToCloudinary;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class UploadToCloudinaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_failure_is_rethrown_and_temp_file_is_kept_for_retry(): void
    {
        Storage::fake('local');
        Storage::put('temp/uploads/retry.jpg', 'image');
        $storage = $this->mock(StorageServiceInterface::class);
        $storage->shouldReceive('upload')->once()->andThrow(new RuntimeException('Cloudinary unavailable'));
        $job = new UploadToCloudinary('temp/uploads/retry.jpg');

        try {
            $job->handle($storage);
            $this->fail('The upload exception was not rethrown.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Cloudinary unavailable', $exception->getMessage());
        }

        Storage::assertExists('temp/uploads/retry.jpg');
        $this->assertSame(3, $job->tries);
        $this->assertSame([10, 60, 300], $job->backoff);
    }

    public function test_failed_job_removes_temp_file_after_retries_are_exhausted(): void
    {
        Storage::fake('local');
        Storage::put('temp/uploads/failed.jpg', 'image');
        $project = Project::create([
            'name' => 'Failed Media', 'category' => 'web', 'media_status' => 'pending', 'pending_uploads' => 1,
        ]);
        $job = new UploadToCloudinary(
            'temp/uploads/failed.jpg', modelType: 'project_image', projectId: $project->id,
        );

        $job->failed(new RuntimeException('Final failure'));

        Storage::assertMissing('temp/uploads/failed.jpg');
        $this->assertDatabaseHas('projects', [
            'id' => $project->id, 'media_status' => 'failed', 'pending_uploads' => 0,
        ]);
    }

    public function test_successful_project_upload_marks_media_as_completed(): void
    {
        Storage::fake('local');
        Storage::put('temp/uploads/success.jpg', 'image');
        $project = Project::create([
            'name' => 'Successful Media', 'category' => 'web', 'media_status' => 'pending', 'pending_uploads' => 1,
        ]);
        $storage = $this->mock(StorageServiceInterface::class);
        $storage->shouldReceive('upload')->once()->andReturn([
            'public_id' => 'projects/success', 'url' => 'http://example.com/success.jpg',
            'secure_url' => 'https://example.com/success.jpg',
        ]);

        (new UploadToCloudinary(
            'temp/uploads/success.jpg', modelType: 'project_image', projectId: $project->id,
        ))->handle($storage);

        Storage::assertMissing('temp/uploads/success.jpg');
        $this->assertDatabaseHas('projects', [
            'id' => $project->id, 'media_status' => 'completed', 'pending_uploads' => 0,
        ]);
        $this->assertDatabaseHas('project_images', ['project_id' => $project->id, 'public_id' => 'projects/success']);
    }
}
