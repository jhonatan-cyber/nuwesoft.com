<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $testimonials = Testimonial::orderBy('sort_order')->paginate($perPage)->withQueryString();

        return Inertia::render('Dashboard/Testimonials/Index', [
            'testimonials' => $testimonials,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_role' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'content' => 'required|string|max:2000',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Testimonial::create($validated);

        return Redirect::route('testimonials.index')->with('success', 'Testimonial created.');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_role' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'content' => 'required|string|max:2000',
            'rating' => 'nullable|integer|min:1|max:5',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $testimonial->update($validated);

        return Redirect::route('testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return Redirect::route('testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
