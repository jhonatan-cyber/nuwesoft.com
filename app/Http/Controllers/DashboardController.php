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
        ]);
    }
}
