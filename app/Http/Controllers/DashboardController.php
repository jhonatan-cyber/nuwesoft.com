<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use App\Models\Technology;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $cacheTtl = 60; // seconds

        $activeProjects = Cache::remember('dashboard.active_projects', $cacheTtl, fn () => Project::where('is_active', true)->count());
        $totalProjects = Cache::remember('dashboard.total_projects', $cacheTtl, fn () => Project::count());
        $activeTechnologies = Cache::remember('dashboard.active_technologies', $cacheTtl, fn () => Technology::where('is_active', true)->count());
        $totalTechnologies = Cache::remember('dashboard.total_technologies', $cacheTtl, fn () => Technology::count());
        $pendingMessages = Cache::remember('dashboard.pending_messages', $cacheTtl, fn () => ContactMessage::count());
        $unreadMessages = Cache::remember('dashboard.unread_messages', $cacheTtl, fn () => ContactMessage::whereNull('read_at')->count());
        $totalPosts = Cache::remember('dashboard.total_posts', $cacheTtl, fn () => Post::count());
        $publishedPosts = Cache::remember('dashboard.published_posts', $cacheTtl, fn () => Post::where('is_published', true)->count());
        $totalTestimonials = Cache::remember('dashboard.total_testimonials', $cacheTtl, fn () => Testimonial::count());

        // Latest messages are always fresh (user expects real-time)
        $latestMessages = ContactMessage::latest()->take(5)->get();

        // Aggregate queries — cached
        $projectsByCategory = Cache::remember('dashboard.projects_by_category', $cacheTtl, fn () => Project::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
        );

        $techByCategory = Cache::remember('dashboard.tech_by_category', $cacheTtl, fn () => Technology::selectRaw('category, count(*) as total')
            ->where('is_active', true)
            ->groupBy('category')
            ->pluck('total', 'category')
        );

        // Recent projects — cached with eager loading
        $recentProjects = Cache::remember('dashboard.recent_projects', $cacheTtl, fn () => Project::with('technologies')
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'slug', 'category', 'created_at'])
        );

        // PostHog analytics integration (with fallback to elegant mock data)
        $posthogStats = $this->getPosthogStats();

        // Activity log — latest admin actions
        $activityLog = Cache::remember('dashboard.activity_log', 30, fn () => \App\Models\ActivityLog::latest()->take(10)->get()
        );

        return Inertia::render('Dashboard', [
            'stats' => [
                'active_projects' => $activeProjects,
                'total_projects' => $totalProjects,
                'active_technologies' => $activeTechnologies,
                'total_technologies' => $totalTechnologies,
                'pending_messages' => $pendingMessages,
                'unread_messages' => $unreadMessages,
                'total_posts' => $totalPosts,
                'published_posts' => $publishedPosts,
                'total_testimonials' => $totalTestimonials,
                'projects_by_category' => $projectsByCategory,
                'tech_by_category' => $techByCategory,
            ],
            'recent_projects' => $recentProjects,
            'latest_messages' => $latestMessages,
            'activity_log' => $activityLog,
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
                        'page_views' => collect($data['results'] ?? [])->map(fn ($r) => [
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
            ],
        ];
    }
}
