<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicTestimonialRequest;
use App\Mail\NewTestimonialMail;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PublicTestimonialController extends Controller
{
    public function show()
    {
        return Inertia::render('TestimonialForm', [
            'testimonials' => Testimonial::approved()
                ->with([])
                ->limit(6)
                ->get()
                ->map(fn ($t) => [
                    'client_name' => $t->client_name,
                    'client_role' => $t->client_role,
                    'client_company' => $t->client_company,
                    'content' => $t->content,
                    'rating' => $t->rating,
                ]),
        ]);
    }

    public function store(PublicTestimonialRequest $request)
    {
        $validated = $request->validated();

        // Anti-spam: honeypot check
        if (!empty($validated['form_token'])) {
            return back()->with('success', '¡Gracias por tu reseña! Será revisada por nuestro equipo.');
        }

        // Anti-spam: check duplicate in last 5 minutes
        $recent = Testimonial::where('client_name', $validated['client_name'])
            ->where('content', $validated['content'])
            ->where('created_at', '>', now()->subMinutes(5))
            ->exists();

        if ($recent) {
            return back()->with('success', 'Ya recibimos tu reseña. Será publicada pronto.');
        }

        $testimonial = Testimonial::create([
            'client_name' => $validated['client_name'],
            'client_role' => $validated['client_role'] ?? null,
            'client_company' => $validated['client_company'] ?? null,
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'status' => 'pending',
            'is_active' => false,
        ]);

        // Notify admin via email
        $this->notifyAdmin($testimonial);

        return redirect()->route('review.thanks');
    }

    public function thanks()
    {
        return Inertia::render('TestimonialThanks');
    }

    private function notifyAdmin(Testimonial $testimonial): void
    {
        try {
            $adminEmail = Setting::getValue('admin_email') ?? config('mail.from.address');

            if ($adminEmail) {
                Mail::to($adminEmail)->send(new NewTestimonialMail($testimonial));
            }
        } catch (\Exception $e) {
            // Don't break the submission if email fails
            \Log::warning('Failed to send testimonial notification email: ' . $e->getMessage());
        }
    }
}
