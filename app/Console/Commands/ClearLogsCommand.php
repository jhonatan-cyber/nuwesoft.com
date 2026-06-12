<?php

namespace App\Console\Commands;

use App\Models\FourOhFourLog;
use Illuminate\Console\Command;

class ClearLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear 404 logs older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting database 404 logs cleanup...');

        $cutoff = now()->subDays(30);
        $deletedCount = FourOhFourLog::where('logged_at', '<', $cutoff)->delete();

        $this->info("Successfully cleared {$deletedCount} log entries older than {$cutoff->toDateString()}.");
    }
}
