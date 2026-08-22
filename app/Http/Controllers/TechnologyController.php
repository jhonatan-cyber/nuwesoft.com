<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTechnologyRequest;
use App\Http\Requests\UpdateTechnologyRequest;
use App\Jobs\UploadToCloudinary;
use App\Models\Technology;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TechnologyController extends Controller
{
    public function index(Request $request)
    {
        $query = Technology::query();

        // Server-side search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Server-side sort
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortField, ['name', 'category', 'created_at', 'is_active', 'updated_at'])) {
            $query->orderBy($sortField, $sortOrder);
        }

        $perPage = $request->input('per_page', 24);

        return Inertia::render('Dashboard/Technologies/Index', [
            'technologies' => $query->paginate($perPage)->withQueryString(),
            'filters' => $request->only(['search', 'sort_field', 'sort_order']),
        ]);
    }

    public function store(StoreTechnologyRequest $request)
    {
        $validated = $request->validated();

        unset($validated['logo']);
        $technology = Technology::create($validated);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('temp/uploads');

            UploadToCloudinary::dispatch(
                filePath: $path,
                folder: 'technologies',
                modelType: 'technology',
                modelId: $technology->id,
            );
        }

        return redirect()->back();
    }

    public function update(UpdateTechnologyRequest $request, Technology $technology)
    {
        $validated = $request->validated();

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('temp/uploads');

            UploadToCloudinary::dispatch(
                filePath: $path,
                folder: 'technologies',
                modelType: 'technology',
                modelId: $technology->id,
            );
        }

        unset($validated['logo']);
        $technology->update($validated);

        return redirect()->back();
    }

    public function destroy(Technology $technology)
    {
        // Delete logo from Cloudinary if exists
        if ($technology->logo_public_id) {
            try {
                app(\App\Contracts\StorageServiceInterface::class)->delete($technology->logo_public_id);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $technology->delete();

        return redirect()->back();
    }
}
