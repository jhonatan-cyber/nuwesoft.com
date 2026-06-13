<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $activeProjects = Project::where('is_active', true)->count();
        $totalProjects = Project::count();
        $activeTechnologies = Technology::where('is_active', true)->count();
        $totalTechnologies = Technology::count();
        $pendingMessages = ContactMessage::count();
        $latestMessages = ContactMessage::latest()->take(5)->get();

        // Projects by category
        $projectsByCategory = Project::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Technologies by category
        $techByCategory = Technology::selectRaw('category, count(*) as total')
            ->where('is_active', true)
            ->groupBy('category')
            ->pluck('total', 'category');

        // Recent projects (last 5)
        $recentProjects = Project::with('technologies')
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'category', 'created_at']);

        // PostHog analytics integration (with fallback to elegant mock data)
        $posthogStats = $this->getPosthogStats();

        return Inertia::render('Dashboard', [
            'stats' => [
                'active_projects' => $activeProjects,
                'total_projects' => $totalProjects,
                'active_technologies' => $activeTechnologies,
                'total_technologies' => $totalTechnologies,
                'pending_messages' => $pendingMessages,
                'projects_by_category' => $projectsByCategory,
                'tech_by_category' => $techByCategory,
            ],
            'recent_projects' => $recentProjects,
            'latest_messages' => $latestMessages,
            'posthog_stats' => $posthogStats,
        ]);
    }

    /**
     * Retrieve analytics stats from PostHog or return mock data if not configured/available.
     */
    protected function getPosthogStats(): array
    {
        $posthogKey = config('services.posthog.key');
        $posthogHost = config('services.posthog.host', 'https://us.i.posthog.com');
        $projectId = env('POSTHOG_PROJECT_ID');

        if ($posthogKey && $projectId) {
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $posthogKey,
                ])->timeout(3)->get("{$posthogHost}/api/projects/{$projectId}/insights", [
                    'insight' => 'TRENDS',
                    'date_from' => '-7d',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Aquí procesaríamos el formato específico de PostHog.
                    // Por simplicidad del wrapper de visualización, retornamos una estructura adaptada.
                    return [
                        'source' => 'real',
                        'page_views' => collect($data['results'] ?? [])->map(fn($r) => [
                            'path' => $r['label'] ?? 'Unknown',
                            'views' => array_sum($r['data'] ?? []),
                        ])->sortByDesc('views')->take(5)->values()->toArray(),
                    ];
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        // Mock data fallback for development or unconfigured API
        return [
            'source' => 'mock',
            'page_views' => [
                ['path' => '/', 'views' => 1240],
                ['path' => '/portafolio', 'views' => 850],
                ['path' => '/servicios', 'views' => 420],
                ['path' => '/blog', 'views' => 310],
                ['path' => '/contacto', 'views' => 240],
            ],
            'visitors_by_country' => [
                ['country' => 'Argentina', 'code' => 'AR', 'count' => 1200],
                ['country' => 'España', 'code' => 'ES', 'count' => 450],
                ['country' => 'Estados Unidos', 'code' => 'US', 'count' => 180],
                ['country' => 'Chile', 'code' => 'CL', 'count' => 90],
            ]
        ];
    }
}
