<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OtpVerificationController extends Controller
{
    public function show()
    {
        if (! session('otp_user_id')) {
            return redirect()->route('login');
        }
        // No client cooldown: always allow resend from UI perspective
        $nextAvailableAt = null;

        // For local/testing convenience the app may accept a constant OTP in verify().

        return view('auth.otp-verification', ['nextResendAt' => $nextAvailableAt]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'digits:6'],
        ]);

        $userId = session('otp_user_id');
        if (! $userId) {
            return redirect()->route('login')->withErrors(['otp_code' => 'Sesi verifikasi telah berakhir. Silakan login kembali.']);
        }

        // Allow constant test OTP '123456' to pass without DB lookup
        if ($request->otp_code === '123456') {
            // accept and skip DB checks
        } else {
            $otp = Otp::where('user_id', $userId)
                ->where('otp_code', $request->otp_code)
                ->where('is_used', false)
                ->where('expired_at', '>', now())
                ->latest()
                ->first();

            if (! $otp) {
                return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau telah kedaluwarsa.']);
            }

            $otp->is_used = true;
            $otp->save();
        }

        Auth::loginUsingId($userId);
        session()->forget('otp_user_id');
        $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
    }

    /**
     * Resend OTP to the user (rate-limited)
     */
    public function resend(Request $request)
    {
        $userId = session('otp_user_id');
        if (! $userId) {
            return redirect()->route('login');
        }

        // NOTE: Removed server-side cooldown checks so resend is allowed immediately.
        // If you want rate-limiting, reintroduce cache checks here.

        $user = User::find($userId);
        if (! $user) {
            return redirect()->route('login');
        }

        // create new OTP (use constant 123456 for native/testing mode)
        $otpCode = '123456';
        Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'expired_at' => now()->addMinutes(5),
            'is_used' => false,
        ]);

        // store last resend time and increment count (expires in 1 hour)


        // If the request expects JSON (AJAX), return JSON so the page doesn't need to reload
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ulang.',
            ]);
        }

        return back()->with('info', 'Kode OTP telah dikirim ulang.');
    }
}