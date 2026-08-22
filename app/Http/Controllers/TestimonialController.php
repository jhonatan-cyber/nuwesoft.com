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
        $status = $request->input('status', 'all');
        $rating = $request->input('rating', null);

        $query = Testimonial::orderBy('sort_order');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($rating !== null && $rating !== '') {
            $query->where('rating', (int) $rating);
        }

        $testimonials = $query->paginate($perPage)->withQueryString();
        $pendingCount = Testimonial::where('status', 'pending')->count();

        return Inertia::render('Dashboard/Testimonials/Index', [
            'testimonials' => $testimonials,
            'pendingCount' => $pendingCount,
            'currentStatus' => $status,
            'currentRating' => $rating,
        ]);
    }

    public function store(StoreTestimonialRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = $validated['status'] ?? 'approved';

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

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update([
            'status' => 'approved',
            'is_active' => true,
        ]);

        return back()->with('success', 'Testimonial approved.');
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->update([
            'status' => 'rejected',
            'is_active' => false,
        ]);

        return back()->with('success', 'Testimonial rejected.');
    }
}
