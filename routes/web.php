<?php

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TechnologyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/servicios', function () {
    return Inertia::render('Servicios');
})->name('servicios');

Route::get('/portafolio', function () {
    return Inertia::render('Portafolio', [
        'projects' => \App\Models\Project::with(['images', 'technologies'])
            ->where('is_active', true)
            ->latest('created_at')
            ->get(),
    ]);
})->name('portafolio');
Route::get('/api/portafolio', [ProjectController::class, 'publicIndex'])->name('portafolio.data');

Route::get('/contacto', function () {
    return Inertia::render('Contacto');
})->name('contacto');

Route::post('/contacto', [ContactMessageController::class, 'store'])->name('contacto.store');

Route::get('/dashboard', function () {
    $activeProjects = \App\Models\Project::where('is_active', true)->count();
    $totalTechnologies = \App\Models\Technology::where('is_active', true)->count();
    $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
    $recentProjects = \App\Models\Project::latest()->limit(5)->get();

    return Inertia::render('Dashboard', [
        'stats' => [
            'active_projects' => $activeProjects,
            'active_technologies' => $totalTechnologies,
            'unread_messages' => $unreadMessages,
            'total_projects' => \App\Models\Project::count(),
        ],
        'recent_projects' => $recentProjects,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Technologies CRUD
    Route::resource('dashboard/technologies', TechnologyController::class)->names('technologies')->except(['create', 'edit', 'show']);

    // Projects CRUD
    Route::resource('dashboard/projects', ProjectController::class)->names('projects');

    // Contact Messages
    Route::resource('dashboard/messages', ContactMessageController::class)->only(['index', 'destroy'])->names('messages');
    Route::patch('dashboard/messages/{message}/read', [ContactMessageController::class, 'markAsRead'])->name('messages.markAsRead');
});

require __DIR__.'/auth.php';
