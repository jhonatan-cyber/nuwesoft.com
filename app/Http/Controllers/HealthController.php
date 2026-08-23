<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Technology;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function index(Request $request)
    {
        $startTime = microtime(true);

        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'environment' => app()->environment(),
            'debug' => config('app.debug'),
            'locale' => app()->getLocale(),
            'metrics' => [
                'projects_count' => app(CacheService::class)->remember('health.projects', 60, fn () => Project::where('is_active', true)->count()),
                'technologies_count' => app(CacheService::class)->remember('health.technologies', 60, fn () => Technology::where('is_active', true)->count()),
                'contact_messages' => app(CacheService::class)->remember('health.contacts', 60, fn () => ContactMessage::count()),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
            ],
            'system' => [
                'memory_usage' => $this->formatBytes(memory_get_usage(true)),
                'peak_memory' => $this->formatBytes(memory_get_peak_usage(true)),
                'uptime' => $this->getUptime(),
            ],
            'database' => [
                'connection' => config('database.default'),
                'status' => $this->checkDatabase(),
            ],
            'cache' => [
                'driver' => config('cache.default'),
                'status' => $this->checkCache(),
            ],
        ];

        $health['response_time_ms'] = round((microtime(true) - $startTime) * 1000, 2);

        $isAuthenticated = $request->user() !== null;

        if ($isAuthenticated) {
            $health['metrics']['users_count'] = \App\Models\User::count();
            $health['metrics']['total_projects'] = Project::count();
            $health['metrics']['total_technologies'] = Technology::count();
            $health['disk'] = [
                'free' => $this->formatBytes(disk_free_space(storage_path())),
                'total' => $this->formatBytes(disk_total_space(storage_path())),
            ];
        }

        return response()->json($health, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function ping()
    {
        return response()->json([
            'status' => 'pong',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    private function checkDatabase(): string
    {
        try {
            DB::connection()->getPdo();

            return 'connected';
        } catch (\Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    private function checkCache(): string
    {
        try {
            $key = 'health.test.' . now()->timestamp;
            Cache::put($key, true, 1);
            $result = Cache::get($key);
            Cache::forget($key);

            return $result ? 'operational' : 'failed';
        } catch (\Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    private function getUptime(): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return 'N/A';
        }
        $uptime = @file_get_contents('/proc/uptime');
        if ($uptime) {
            $seconds = (float) explode(' ', $uptime)[0];
            $days = floor($seconds / 86400);
            $hours = floor(($seconds % 86400) / 3600);
            $minutes = floor(($seconds % 3600) / 60);

            return "{$days}d {$hours}h {$minutes}m";
        }

        return 'N/A';
    }
}
