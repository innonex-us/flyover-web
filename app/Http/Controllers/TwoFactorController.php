<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA setup form (Enable/Disable).
     */
    public function showLinkRequestForm()
    {
        // This method is for password reset usually, but let's stick to the plan.
        // We need a view that is included in profile, but the controller handles logic.
        // Actually, the profile view acts as the "show" for enabling.
        return redirect()->route('profile.edit');
    }

    /**
     * Show the 2FA challenge form during login.
     */
    public function index()
    {
        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the 2FA code during login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $user = auth()->user();
        $google2fa = new Google2FA();
        
        // Decrypt the secret
        try {
            $secret = decrypt($user->google2fa_secret);
        } catch (\Exception $e) {
            // If decryption fails (e.g. legacy or invalid), logout
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Security error. Please login again.']);
        }

        $valid = $google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            $request->session()->put('2fa_verified', true);
            return redirect()->intended('/dashboard'); // Or home
        }

        return back()->withErrors(['code' => 'The provided code is incorrect.']);
    }

    /**
     * Generate a new secret and show QR code content for enabling.
     * This is called via AJAX or a separate page to setup.
     * Let's make it a structured flow.
     */
    public function enable(Request $request)
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $request->user()->email,
            $secret
        );

        return view('profile.two-factor-setup', compact('secret', 'qrCodeUrl'));
    }

    /**
     * Confirm the setup by verifying the first code.
     */
    public function store(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|numeric',
        ]);

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($request->secret, $request->code);

        if ($valid) {
            $user = $request->user();
            $user->google2fa_secret = encrypt($request->secret);
            $user->save();
            
            $request->session()->put('2fa_verified', true);

            return redirect()->route('profile.edit')->with('status', 'two-factor-enabled');
        }

        return back()->withErrors(['code' => 'Invalid code. Please try again.']);
    }

    /**
     * Disable 2FA.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = $request->user();
        $user->google2fa_secret = null;
        $user->save();
        
        $request->session()->forget('2fa_verified');

        return back()->with('status', 'two-factor-disabled');
    }
}
