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
        $projects = Project::with(['images', 'technologies'])->latest()->paginate($perPage)->withQueryString();
        $technologies = \App\Models\Technology::where('is_active', true)->get();

        return Inertia::render('Dashboard/Projects/Index', [
            'projects' => $projects,
            'technologies' => $technologies,
        ]);
    }

    public function create()
    {
        $technologies = \App\Models\Technology::where('is_active', true)->get();

        return Inertia::render('Dashboard/Projects/Form', [
            'technologies' => $technologies,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stack' => 'nullable',
            'technologies' => 'nullable|array',
            'desc' => 'nullable|string',
            'icon' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'project_url' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $stack = $this->normalizeStack($request->input('stack'));

        $project = Project::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'stack' => $stack,
            'desc' => $validated['desc'] ?? '',
            'icon' => $validated['icon'] ?? 'Briefcase',
            'project_url' => $validated['project_url'] ?? '',
            'is_active' => $validated['is_active'],
        ]);

        if ($request->has('technologies')) {
            $project->technologies()->sync($validated['technologies']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $project->uploadImage($image);
            }
        }

        return Redirect::route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $project->load(['images', 'technologies']);
        $technologies = \App\Models\Technology::where('is_active', true)->get();

        return Inertia::render('Dashboard/Projects/Form', [
            'project' => $project,
            'technologies' => $technologies,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stack' => 'nullable',
            'technologies' => 'nullable|array',
            'desc' => 'nullable|string',
            'icon' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'remove_images' => 'nullable|array',
            'project_url' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $stack = $this->normalizeStack($request->input('stack'));

        $project->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'stack' => $stack,
            'desc' => $validated['desc'] ?? '',
            'icon' => $validated['icon'] ?? 'Briefcase',
            'project_url' => $validated['project_url'] ?? '',
            'is_active' => $validated['is_active'],
        ]);

        if ($request->has('technologies')) {
            $project->technologies()->sync($validated['technologies']);
        }

        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $imageId) {
                $project->deleteImage($imageId);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $project->uploadImage($image);
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

    protected function normalizeStack(mixed $stack): array
    {
        if (is_array($stack)) {
            return array_values(array_filter(array_map('trim', $stack), fn ($v) => $v !== ''));
        }

        if (!is_string($stack) || trim($stack) === '') {
            return [];
        }

        $decoded = json_decode($stack, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded), fn ($v) => $v !== ''));
        }

        return array_values(array_filter(array_map('trim', explode(',', $stack)), fn ($v) => $v !== ''));
    }
}
