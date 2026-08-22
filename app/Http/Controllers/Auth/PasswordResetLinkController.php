<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Max password reset requests before lockout.
     */
    private const MAX_ATTEMPTS = 3;

    /**
     * Lockout duration in seconds (15 minutes).
     */
    private const LOCKOUT_SECONDS = 900;

    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $throttleKey = Str::transliterate(Str::lower($request->string('email')) . '|password-reset|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'Demasiadas solicitudes. Intentá de nuevo en ' . ceil($seconds / 60) . ' minutos.',
            ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        RateLimiter::clear($throttleKey);

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
