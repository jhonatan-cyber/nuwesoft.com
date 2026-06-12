<?php

namespace App\Http\Controllers;

use App\Models\FourOhFourLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogController extends Controller
{
    public function index()
    {
        $logs = FourOhFourLog::latest('logged_at')
            ->take(100)
            ->get()
            ->map(fn ($log) => [
                'url' => $log->url,
                'referer' => $log->referer,
                'ip' => $log->ip,
                'user_agent' => $log->user_agent,
                'created_at' => $log->logged_at?->format('Y-m-d H:i:s'),
            ]);

        $stats = [
            'total' => FourOhFourLog::count(),
            'today' => FourOhFourLog::whereDate('logged_at', today())->count(),
            'unique_urls' => FourOhFourLog::distinct('url')->count('url'),
        ];

        return Inertia::render('Dashboard/Logs/Index', [
            'logs' => $logs,
            'stats' => $stats,
        ]);
    }
}
