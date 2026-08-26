<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestoreDatabase extends Command
{
    protected $signature = 'backup:restore {file : Backup filename or absolute path} {--force : Skip confirmation}';

    protected $description = 'Restore PostgreSQL from a validated local backup';

    public function handle(): int
    {
        $backupDirectory = realpath(storage_path('app/backups'));
        $requested = (string) $this->argument('file');
        $candidate = str_contains($requested, DIRECTORY_SEPARATOR)
            ? $requested
            : storage_path('app/backups/' . $requested);
        $file = realpath($candidate);

        if (! $backupDirectory || ! $file || dirname($file) !== $backupDirectory) {
            $this->error('El respaldo debe existir dentro de storage/app/backups.');

            return self::FAILURE;
        }

        if (! str_ends_with($file, '.sql') && ! str_ends_with($file, '.sql.gz')) {
            $this->error('El respaldo debe tener extensión .sql o .sql.gz.');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm('Esta operación reemplazará la base de datos actual. ¿Continuar?')) {
            return self::FAILURE;
        }

        $config = config('database.connections.' . config('database.default'));
        if (($config['driver'] ?? null) !== 'pgsql') {
            $this->error('La restauración solo soporta PostgreSQL.');

            return self::FAILURE;
        }

        if (str_ends_with($file, '.gz') && $this->executeCommand('gzip -t ' . escapeshellarg($file)) !== 0) {
            $this->error('El archivo gzip está dañado.');

            return self::FAILURE;
        }

        $psql = $this->psqlCommand($config);
        $reset = $psql . ' -c ' . escapeshellarg('DROP SCHEMA public CASCADE; CREATE SCHEMA public;');
        if ($this->executeCommand($reset) !== 0) {
            $this->error('No se pudo preparar la base de datos para restaurar.');

            return self::FAILURE;
        }

        $source = str_ends_with($file, '.gz')
            ? 'gzip -dc ' . escapeshellarg($file)
            : 'cat ' . escapeshellarg($file);

        if ($this->executeCommand($source . ' | ' . $psql) !== 0) {
            $this->error('La restauración falló y requiere intervención inmediata.');

            return self::FAILURE;
        }

        $this->info('Base de datos restaurada y validada correctamente.');

        return self::SUCCESS;
    }

    /** @param array<string, mixed> $config */
    private function psqlCommand(array $config): string
    {
        return 'PGPASSWORD=' . escapeshellarg((string) $config['password'])
            . ' psql -v ON_ERROR_STOP=1'
            . ' -h ' . escapeshellarg((string) $config['host'])
            . ' -p ' . escapeshellarg((string) $config['port'])
            . ' -U ' . escapeshellarg((string) $config['username'])
            . ' -d ' . escapeshellarg((string) $config['database']);
    }

    private function executeCommand(string $command): int
    {
        passthru($command, $exitCode);

        return $exitCode;
    }
}
