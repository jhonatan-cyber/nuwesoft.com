<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Mail\ContactNotification;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Show the contact page.
     */
    public function show()
    {
        // Generate a signed timestamp token for anti-spam timing check.
        // The token is encrypted so bots can't forge a valid future timestamp.
        $antiSpamToken = Crypt::encryptString(json_encode([
            'ts' => now()->timestamp,
        ]));

        return Inertia::render('Contacto', [
            'anti_spam_token' => $antiSpamToken,
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function send(ContactMessageRequest $request)
    {
        $validated = $request->validated();

        // Handle file attachment if provided
        $attachmentUrl = null;
        $attachmentName = null;
        $tempPath = null;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();

            try {
                $tempPath = $file->store('temp/contact-attachments');
                // Note: URL will be set asynchronously by the UploadToCloudinary job.
            } catch (\Throwable $e) {
                report($e);
                $tempPath = null;
            }
        }

        $message = ContactMessage::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'mensaje' => $validated['mensaje'] ?? '',
            'attachment_url' => $attachmentUrl,
            'attachment_name' => $attachmentName,
        ]);

        // Dispatch async upload to Cloudinary — the job updates the message with the public URL
        if ($tempPath) {
            \App\Jobs\UploadToCloudinary::dispatch(
                filePath: $tempPath,
                folder: 'contact-attachments',
                modelType: 'contact_attachment',
                modelId: $message->id,
            );
        }

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
