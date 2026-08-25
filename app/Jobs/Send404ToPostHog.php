<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use PostHog\PostHog;

class Send404ToPostHog implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $logData,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! config('services.posthog.key')) {
            return;
        }

        try {
            PostHog::init(
                config('services.posthog.key'),
                ['host' => config('services.posthog.host')]
            );

            PostHog::capture([
                'distinct_id' => $this->logData['ip'] ?? 'unknown',
                'event' => '404_error',
                'properties' => $this->logData,
            ]);
        } catch (\Throwable $th) {
            Log::error('Failed to send 404 to PostHog: ' . $th->getMessage());
        }
    }
}
