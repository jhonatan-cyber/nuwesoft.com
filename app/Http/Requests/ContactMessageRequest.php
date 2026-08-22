<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ── Honeypot: must be empty (bots auto-fill hidden fields) ──
            'website_url' => 'nullable|string|max:0',

            // ── Timing token: must be valid and ≥3 seconds old ──
            'form_token' => 'required|string',

            // ── Real fields ──
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensaje' => 'nullable|string|max:5000|required_without:attachment',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // ── Honeypot check ──
            if ($this->filled('website_url')) {
                $validator->errors()->add('website_url', ' Spam detected.');

                return;
            }

            // ── Timing token check ──
            if ($validator->errors()->any()) {
                return;
            }

            $minSeconds = 3;
            try {
                $decoded = json_decode(Crypt::decryptString($this->input('form_token', '')), true);
                $createdAt = $decoded['ts'] ?? 0;
                $elapsed = now()->timestamp - $createdAt;

                if ($elapsed < $minSeconds) {
                    $validator->errors()->add(
                        'form_token',
                        ' submitted too quickly. Please try again.'
                    );
                }
            } catch (\Throwable $e) {
                $validator->errors()->add('form_token', 'Invalid form token. Please reload the page.');
            }
        });
    }
}
