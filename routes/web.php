<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TechnologyController;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/servicios', function () {
    return Inertia::render('Servicios', [
        'technologies' => \Illuminate\Support\Facades\Cache::remember('active_technologies_servicios', 3600, function () {
            return Technology::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'logo_url', 'category', 'invert_dark']);
        }),
    ]);
})->name('servicios');

Route::get('/portafolio', function () {
    return Inertia::render('Portafolio', [
        'projects' => \Illuminate\Support\Facades\Cache::remember('active_projects_with_relations', 3600, function () {
            return Project::with(['images', 'technologies'])
                ->where('is_active', true)
                ->latest('created_at')
                ->get();
        }),
        'technologies' => \Illuminate\Support\Facades\Cache::remember('active_technologies', 3600, function () {
            return Technology::where('is_active', true)->get();
        }),
    ]);
})->name('portafolio');
Route::get('/portafolio/{project}', [ProjectController::class, 'publicShow'])->name('portafolio.show');
Route::get('/api/portafolio', [ProjectController::class, 'publicIndex'])->middleware('throttle:api')->name('portafolio.data');

Route::get('/contacto', [ContactController::class, 'show'])->name('contacto');
Route::post('/contacto', [ContactController::class, 'send'])->middleware('throttle:contact')->name('contacto.send');

Route::get('/privacidad', function () {
    return Inertia::render('Privacidad');
})->name('privacidad');

Route::get('/blog', [App\Http\Controllers\PostController::class, 'publicIndex'])->name('blog.index');
Route::get('/blog/{post:slug}', [App\Http\Controllers\PostController::class, 'publicShow'])->name('blog.show');

Route::get('/terminos', function () {
    return Inertia::render('Terminos');
})->name('terminos');

Route::get('/sitemap.xml', function () {
    $settings = \App\Models\Setting::getAll();
    $siteName = $settings['site_name'] ?? 'NUWESOFT';

    $projects = \App\Models\Project::where('is_active', true)->get(['id', 'updated_at']);
    $posts = \App\Models\Post::published()->get(['slug', 'updated_at']);

    $pages = [
        ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'weekly', 'lastmod' => $projects->isNotEmpty() ? $projects->first()->updated_at?->toW3cString() : null],
        ['loc' => url('/servicios'), 'priority' => '0.9', 'changefreq' => 'monthly'],
        ['loc' => url('/portafolio'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => $projects->isNotEmpty() ? $projects->first()->updated_at?->toW3cString() : null],
        ['loc' => url('/contacto'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => url('/blog'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => $posts->isNotEmpty() ? $posts->first()->updated_at?->toW3cString() : null],
        ['loc' => url('/privacidad'), 'priority' => '0.3', 'changefreq' => 'yearly'],
        ['loc' => url('/terminos'), 'priority' => '0.3', 'changefreq' => 'yearly'],
    ];

    foreach ($projects as $project) {
        $pages[] = [
            'loc' => url('/portafolio/' . $project->id),
            'priority' => '0.7',
            'changefreq' => 'monthly',
            'lastmod' => $project->updated_at?->toW3cString(),
        ];
    }

    foreach ($posts as $post) {
        $pages[] = [
            'loc' => url('/blog/' . $post->slug),
            'priority' => '0.6',
            'changefreq' => 'monthly',
            'lastmod' => $post->updated_at?->toW3cString(),
        ];
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($pages as $page) {
        $xml .= '<url>' . "\n";
        $xml .= '<loc>' . e($page['loc']) . '</loc>' . "\n";
        $xml .= '<priority>' . $page['priority'] . '</priority>' . "\n";
        $xml .= '<changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
        if (isset($page['lastmod']) && $page['lastmod']) {
            $xml .= '<lastmod>' . $page['lastmod'] . '</lastmod>' . "\n";
        }
        $xml .= '</url>' . "\n";
    }

    $xml .= '</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml']);
});

Route::get('/rss.xml', function () {
    $projects = \App\Models\Project::with(['technologies'])
        ->where('is_active', true)
        ->latest('created_at')
        ->get();

    $settings = \App\Models\Setting::getAll();
    $siteName = $settings['site_name'] ?? 'NUWESOFT';
    $tagline = $settings['tagline'] ?? '';

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . "\n";
    $xml .= '<channel>' . "\n";
    $xml .= '<title>' . e($siteName) . ' — Portafolio</title>' . "\n";
    $xml .= '<link>' . e(url('/portafolio')) . '</link>' . "\n";
    $xml .= '<description>' . e($tagline) . '</description>' . "\n";
    $xml .= '<language>' . e(str_replace('_', '-', app()->getLocale())) . '</language>' . "\n";
    $xml .= '<atom:link href="' . e(url('/rss.xml')) . '" rel="self" type="application/rss+xml"/>' . "\n";
    $xml .= '<lastBuildDate>' . now()->toRssString() . '</lastBuildDate>' . "\n";

    foreach ($projects as $project) {
        $xml .= '<item>' . "\n";
        $xml .= '<title>' . e($project->name) . '</title>' . "\n";
        $xml .= '<link>' . e(url('/portafolio/' . $project->id)) . '</link>' . "\n";
        $xml .= '<guid isPermaLink="true">' . e(url('/portafolio/' . $project->id)) . '</guid>' . "\n";
        $xml .= '<description>' . e($project->desc) . '</description>' . "\n";
        $xml .= '<pubDate>' . e($project->created_at?->toRssString() ?? now()->toRssString()) . '</pubDate>' . "\n";
        $xml .= '<category>' . e($project->category) . '</category>' . "\n";
        $xml .= '</item>' . "\n";
    }

    $xml .= '</channel>' . "\n";
    $xml .= '</rss>' . "\n";

    return response($xml, 200, ['Content-Type' => 'application/rss+xml']);
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Messages (Contact inbox)
    Route::get('/dashboard/messages', [App\Http\Controllers\ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('/dashboard/messages/{message}', [App\Http\Controllers\ContactMessageController::class, 'show'])->name('messages.show');
    Route::post('/dashboard/messages/{message}/read', [App\Http\Controllers\ContactMessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/dashboard/messages/{message}/unread', [App\Http\Controllers\ContactMessageController::class, 'markAsUnread'])->name('messages.unread');
    Route::post('/dashboard/messages/read-all', [App\Http\Controllers\ContactMessageController::class, 'markAllAsRead'])->name('messages.read-all');
    Route::delete('/dashboard/messages/{message}', [App\Http\Controllers\ContactMessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/dashboard/messages/export/csv', App\Http\Controllers\MessageExportController::class)->name('messages.export.csv');

    // Technologies CRUD
    Route::resource('dashboard/technologies', TechnologyController::class)->names('technologies')->except(['create', 'edit', 'show']);

    // Projects CRUD
    Route::resource('dashboard/projects', ProjectController::class)->names('projects');

    // Posts (Blog)
    Route::resource('dashboard/posts', App\Http\Controllers\PostController::class)->names('posts');

    // Testimonials
    Route::resource('dashboard/testimonials', App\Http\Controllers\TestimonialController::class)->names('testimonials')->except(['create', 'edit', 'show']);

    // Settings
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::match(['post', 'patch'], '/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    });
});

// Health Check
Route::get('/health', [App\Http\Controllers\HealthController::class, 'index']);
Route::get('/ping', [App\Http\Controllers\HealthController::class, 'ping']);

// 404 Logs (authenticated)
Route::get('/dashboard/logs', [App\Http\Controllers\LogController::class, 'index'])
    ->middleware(['auth'])
    ->name('logs.index');

require __DIR__.'/auth.php';
