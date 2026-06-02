<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\CloudinaryService;

class TechnologyController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 24);
        return Inertia::render('Dashboard/Technologies/Index', [
            'technologies' => Technology::latest()->paginate($perPage)->withQueryString()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $upload = $this->cloudinary->upload($request->file('logo'), 'technologies');
            $validated['logo_url'] = $upload['secure_url'] ?? $upload['url'] ?? null;
            $validated['logo_public_id'] = $upload['public_id'] ?? null;
        }

        Technology::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Technology $technology)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($technology->logo_public_id) {
                $this->cloudinary->delete($technology->logo_public_id);
            }

            $upload = $this->cloudinary->upload($request->file('logo'), 'technologies');
            $validated['logo_url'] = $upload['secure_url'] ?? $upload['url'] ?? null;
            $validated['logo_public_id'] = $upload['public_id'] ?? null;
        }

        $technology->update($validated);

        return redirect()->back();
    }

    public function destroy(Technology $technology)
    {
        if ($technology->logo_public_id) {
            $this->cloudinary->delete($technology->logo_public_id);
        }

        $technology->delete();
        return redirect()->back();
    }
}
