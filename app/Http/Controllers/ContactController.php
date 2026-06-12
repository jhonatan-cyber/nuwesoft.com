<?php

namespace App\Http\Controllers;

use App\Mail\ContactNotification;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Show the contact page.
     */
    public function show()
    {
        return Inertia::render('Contacto');
    }

    /**
     * Handle contact form submission.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensaje' => 'required|string|max:5000',
        ]);

        $message = ContactMessage::create($validated);

        // Send email notification to admin
        try {
            $adminEmail = config('mail.admin_address');
            if ($adminEmail && $adminEmail !== 'hello@example.com') {
                $notification = (new ContactNotification($message))
                    ->locale(app()->getLocale());
                Mail::to($adminEmail)->queue($notification);
            }
        } catch (\Throwable $e) {
            // Don't break the page if email fails
            report($e);
        }

        return redirect()->back()->with('success', 'contacto.alert');
    }
}
