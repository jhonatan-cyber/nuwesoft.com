<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Project::with(['images', 'technologies']);

        // Server-side search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Server-side sort
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortField, ['name', 'category', 'created_at', 'updated_at'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        $projects = $query->paginate($perPage)->withQueryString();
        $technologies = \App\Models\Technology::where('is_active', true)->get();

        return Inertia::render('Dashboard/Projects/Index', [
            'projects' => $projects,
            'technologies' => $technologies,
            'filters' => $request->only(['search', 'sort_field', 'sort_order']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Projects/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|string|in:web,mobile,cloud,automation',
            'technologies' => 'nullable|array',
            'desc'         => 'nullable|string|max:5000',
            'icon'         => 'nullable|string|max:100',
            'images.*'     => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'project_url'  => 'nullable|url|max:500',
            'is_active'    => 'nullable|boolean',
        ]);

        $project = Project::create([
            'name'        => $validated['name'],
            'category'    => $validated['category'],
            'desc'        => $validated['desc'] ?? '',
            'icon'        => $validated['icon'] ?? 'Briefcase',
            'project_url' => $validated['project_url'] ?? '',
            'is_active'   => $validated['is_active'] ?? true,
        ]);

        if ($request->has('technologies')) {
            $project->technologies()->sync($validated['technologies']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $project->uploadImage($image);
                } catch (\Throwable $e) {
                    report($e);
                    return back()->withErrors(['images' => 'Error al subir una o más imágenes. Intenta de nuevo.']);
                }
            }
        }

        return Redirect::route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $project->load('images');

        return Inertia::render('Dashboard/Projects/Form', [
            'project' => $project,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|string|in:web,mobile,cloud,automation',
            'technologies' => 'nullable|array',
            'desc'         => 'nullable|string|max:5000',
            'icon'         => 'nullable|string|max:100',
            'images.*'     => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'remove_images'=> 'nullable|array',
            'project_url'  => 'nullable|url|max:500',
            'is_active'    => 'nullable|boolean',
        ]);

        $project->update([
            'name'        => $validated['name'],
            'category'    => $validated['category'],
            'desc'        => $validated['desc'] ?? '',
            'icon'        => $validated['icon'] ?? 'Briefcase',
            'project_url' => $validated['project_url'] ?? '',
            'is_active'   => $validated['is_active'] ?? $project->is_active,
        ]);

        if ($request->has('technologies')) {
            $project->technologies()->sync($validated['technologies']);
        }

        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $imageId) {
                try {
                    $project->deleteImage($imageId);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $project->uploadImage($image);
                } catch (\Throwable $e) {
                    report($e);
                    return back()->withErrors(['images' => 'Error al subir una o más imágenes. Intenta de nuevo.']);
                }
            }
        }

        return Redirect::route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->deleteAllImages();
        $project->delete();

        return Redirect::route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function publicIndex()
    {
        return Project::with(['images', 'technologies'])
            ->where('is_active', true)
            ->latest('created_at')
            ->get();
    }

    public function publicShow(Project $project)
    {
        abort_if(! $project->is_active, 404);

        $project->load(['images', 'technologies']);

        return Inertia::render('PortfolioProjectDetail', [
            'project' => $project,
        ]);
    }
}
