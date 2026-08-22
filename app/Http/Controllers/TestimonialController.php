<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
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

    public function store(StoreTestimonialRequest $request)
    {
        $validated = $request->validated();

        Testimonial::create($validated);

        return Redirect::route('testimonials.index')->with('success', 'Testimonial created.');
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $validated = $request->validated();

        $testimonial->update($validated);

        return Redirect::route('testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return Redirect::route('testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
