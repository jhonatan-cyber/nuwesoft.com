<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicReviewsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 12), 48);

        $testimonials = Testimonial::approved()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Testimonial::approved()->where('is_active', true)->count(),
            'avg_rating' => round(
                Testimonial::approved()->where('is_active', true)->avg('rating'),
                1
            ),
            'star_5' => Testimonial::approved()->where('is_active', true)->where('rating', 5)->count(),
            'star_4' => Testimonial::approved()->where('is_active', true)->where('rating', 4)->count(),
            'star_3' => Testimonial::approved()->where('is_active', true)->where('rating', 3)->count(),
        ];

        return Inertia::render('PublicReviews', [
            'testimonials' => $testimonials->through(fn ($t) => [
                'id' => $t->id,
                'client_name' => $t->client_name,
                'client_role' => $t->client_role,
                'client_company' => $t->client_company,
                'content' => $t->content,
                'rating' => $t->rating,
                'created_at' => $t->created_at->toISOString(),
            ]),
            'stats' => $stats,
        ]);
    }
}
