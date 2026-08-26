<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestoreDatabaseCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_restore_rejects_files_outside_backup_directory(): void
    {
        $this->artisan('backup:restore', ['file' => base_path('composer.json'), '--force' => true])
            ->expectsOutput('El respaldo debe existir dentro de storage/app/backups.')
            ->assertFailed();
    }

    public function test_restore_rejects_missing_backup(): void
    {
        $this->artisan('backup:restore', ['file' => 'missing.sql.gz', '--force' => true])
            ->expectsOutput('El respaldo debe existir dentro de storage/app/backups.')
            ->assertFailed();
    }
}
