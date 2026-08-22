<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA setup page (show QR code + secret).
     */
    public function showSetup(Request $request)
    {
        $user = $request->user();

        if ($user->two_factor_confirmed_at) {
            return redirect()->route('dashboard')->with('info', '2FA ya está habilitado.');
        }

        $google2fa = app(\PragmaRX\Google2FAQRCode\Google2FA::class);

        // Generate a new secret
        $secret = $google2fa->generateSecretKey();

        // Store encrypted secret temporarily (not confirmed yet)
        $user->two_factor_secret = Crypt::encryptString($secret);
        $user->save();

        // Generate QR code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return Inertia::render('Dashboard/TwoFactor/Setup', [
            'qr_code_url' => $qrCodeUrl,
            'secret' => $secret, // Show once for manual entry
        ]);
    }

    /**
     * Verify the first TOTP code to confirm 2FA setup.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();
        $google2fa = app(\PragmaRX\Google2FAQRCode\Google2FA::class);

        $secret = Crypt::decryptString($user->two_factor_secret);

        if (! $google2fa->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Código invisible. Verificá que esté sincronizado.']);
        }

        // Generate recovery codes
        $recoveryCodes = collect();
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes->push(bin2hex(random_bytes(4)));
        }

        $user->two_factor_confirmed_at = now();
        $user->two_factor_recovery_codes = Crypt::encryptString($recoveryCodes->toJson());
        $user->save();

        return redirect()->route('dashboard')->with('success', '2FA habilitado correctamente. Guardá los codes de recuperación.');
    }

    /**
     * Show 2FA verification page (during login).
     */
    public function showChallenge()
    {
        return Inertia::render('Dashboard/TwoFactor/Challenge');
    }

    /**
     * Verify TOTP code during login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();
        $google2fa = app(\PragmaRX\Google2FAQRCode\Google2FA::class);

        $secret = Crypt::decryptString($user->two_factor_secret);

        // Check if it's a recovery code
        if (strlen($request->code) > 6) {
            return $this->verifyRecoveryCode($request, $user);
        }

        if (! $google2fa->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Código inválido. Intentá de nuevo.']);
        }

        // Mark session as 2FA verified
        session(['two_factor_verified' => true]);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Verify a recovery code.
     */
    protected function verifyRecoveryCode(Request $request, $user)
    {
        $recoveryCodes = collect(json_decode(
            Crypt::decryptString($user->two_factor_recovery_codes),
            true
        ));

        $usedCode = $request->code;

        if (! $recoveryCodes->contains($usedCode)) {
            return back()->withErrors(['code' => 'Código de recuperación inválido.']);
        }

        // Remove used code
        $recoveryCodes = $recoveryCodes->reject(fn ($code) => $code === $usedCode)->values();

        $user->two_factor_recovery_codes = Crypt::encryptString($recoveryCodes->toJson());
        $user->save();

        session(['two_factor_verified' => true]);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Disable 2FA (requires current password).
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (! \Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        $user->two_factor_secret = null;
        $user->two_factor_confirmed_at = null;
        $user->two_factor_recovery_codes = null;
        $user->save();

        return redirect()->route('dashboard')->with('success', '2FA deshabilitado.');
    }
}
