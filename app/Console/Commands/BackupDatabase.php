<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database
                            {--path= : Custom backup path}
                            {--compress : Compress with gzip}';

    protected $description = 'Create a database backup (PostgreSQL)';

    public function handle(): int
    {
        $start = microtime(true);

        // Determine backup path
        $backupPath = $this->option('path') ?: storage_path('app/backups');
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$timestamp}.sql";
        $fullPath = "{$backupPath}/{$filename}";

        // Ensure directory exists
        if (! File::isDirectory($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $this->info('📦 Iniciando backup de base de datos...');

        // Get connection config
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($config['driver'] !== 'pgsql') {
            $this->error("❌ Backup solo soporta PostgreSQL. Driver actual: {$config['driver']}");

            return self::FAILURE;
        }

        // Build pg_dump command
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 5432;
        $database = $config['database'];
        $username = $config['username'];

        $envVars = 'PGPASSWORD=' . escapeshellarg($config['password']);
        $cmd = "{$envVars} pg_dump -h {$host} -p {$port} -U {$username} -d {$database} --no-owner --no-acl -f " . escapeshellarg($fullPath);

        $this->line("   Host: {$host}:{$port}");
        $this->line("   Database: {$database}");
        $this->line("   Output: {$fullPath}");

        exec($cmd, $output, $returnCode);

        if ($returnCode !== 0) {
            $this->error('❌ Error al crear backup. Verificá que pg_dump esté instalado.');

            return self::FAILURE;
        }

        $filesize = File::size($fullPath);
        $duration = round(microtime(true) - $start, 2);

        // Compress if requested
        if ($this->option('compress')) {
            $this->line('   Comprimiendo...');
            exec('gzip ' . escapeshellarg($fullPath));
            $fullPath .= '.gz';
            $filesize = File::size($fullPath);
        }

        $this->info('✅ Backup creado exitosamente');
        $this->line("   Archivo: {$fullPath}");
        $this->line('   Tamaño: ' . number_format($filesize / 1024, 1) . ' KB');
        $this->line("   Tiempo: {$duration}s");

        // Cleanup old backups (keep last 30)
        $this->cleanupOldBackups($backupPath);

        return self::SUCCESS;
    }

    protected function cleanupOldBackups(string $path): void
    {
        $files = collect(File::files($path))
            ->filter(fn ($f) => str_starts_with($f->getFilename(), 'backup_'))
            ->sortByDesc(fn ($f) => $f->getMTime())
            ->values();

        if ($files->count() > 30) {
            $toDelete = $files->skip(30);
            foreach ($toDelete as $file) {
                File::delete($file->getPathname());
            }
            $this->line("   🗑️ Limpiados {$toDelete->count()} backups antiguos");
        }
    }
}
