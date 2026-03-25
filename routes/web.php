<?php

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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Technologies CRUD
    Route::resource('dashboard/technologies', TechnologyController::class)->names('technologies')->except(['create', 'edit', 'show']);

    // Projects CRUD
    Route::resource('dashboard/projects', ProjectController::class)->names('projects');
});

require __DIR__.'/auth.php';
