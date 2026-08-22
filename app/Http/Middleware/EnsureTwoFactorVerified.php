<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTwoFactorVerified
{
    /**
     * If user has 2FA enabled, check that they've verified in this session.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->two_factor_confirmed_at && ! session('two_factor_verified')) {
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}
