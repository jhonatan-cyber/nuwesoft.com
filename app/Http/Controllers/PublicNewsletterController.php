<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicNewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'form_token' => 'nullable|string|max:255',
        ]);

        // Honeypot check
        if (! empty($request->input('form_token'))) {
            return back()->with('newsletter_success', '¡Gracias por suscribirte!');
        }

        $email = strtolower(trim($request->input('email')));

        // Check if already subscribed
        $existing = Subscriber::where('email', $email)->first();

        if ($existing) {
            if ($existing->status === 'active') {
                return back()->with('newsletter_success', 'Ya estás suscrito a nuestro newsletter.');
            }

            // Resubscribe
            $existing->update([
                'status' => 'active',
                'name' => $request->input('name') ?? $existing->name,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);

            return back()->with('newsletter_success', '¡Bienvenido de vuelta! Te suscribiste novamente.');
        }

        // Create new subscriber
        Subscriber::create([
            'email' => $email,
            'name' => $request->input('name'),
            'status' => 'active',
            'source' => $request->input('source', 'blog'),
            'subscribed_at' => now(),
        ]);

        Log::info('New newsletter subscriber', [
            'email' => $email,
            'source' => $request->input('source', 'blog'),
        ]);

        return back()->with('newsletter_success', '¡Gracias por suscribirte! Recibirás nuestras novedades.');
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');

        if (! $email) {
            return redirect('/')->with('error', 'Enlace de desuscripción inválido.');
        }

        $subscriber = Subscriber::where('email', strtolower($email))->first();

        if ($subscriber) {
            $subscriber->unsubscribe();
        }

        return redirect('/')->with('newsletter_success', 'Te desuscribiste correctamente del newsletter.');
    }
}
