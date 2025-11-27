<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Otp;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OtpRequest;
use Mews\Captcha\Facades\Captcha;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function captcha()
    {
        return response()->json(['captcha' => Captcha::create('flat')]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('npk', $request->npk)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['npk' => 'NPK atau password salah']);
        }
        $otpCode = rand(100000, 999999);
        Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'expired_at' => now()->addMinutes(5),
            'is_used' => false,
        ]);

    session(['otp_user_id' => $user->id]);
    return redirect()->route('otp.form');
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function verifyOtp(OtpRequest $request)
    {
        $userId = session('otp_user_id');

        $otp = Otp::where('user_id', $userId)
            ->where('otp_code', $request->otp_code)
            ->where('is_used', false)
            ->where('expired_at', '>', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp_code' => 'OTP tidak valid atau sudah expired']);
        }

        $otp->is_used = true;
        $otp->save();

        Auth::loginUsingId($userId);
        session()->forget('otp_user_id');

        $user = auth()->user();
        $rawRole = $user->role ?? null;


        $role = $this->normalizeRole($rawRole);

        return match ($role) {
            'qc' => redirect()->route('dashboard.qc'),
            'secthead' => redirect()->route('dashboard.secthead'),
            'depthead' => redirect()->route('dashboard.depthead'),
            'ppchead' => redirect()->route('dashboard.ppchead'),
            'agm' => redirect()->route('dashboard.agm'),
            'procurement' => redirect()->route('dashboard.procurement'),
            default => redirect()->route('dashboard'),
        };
    }

    private function normalizeRole(?string $role): string
    {
        if (! $role) return '';
        $r = strtolower(preg_replace('/[\s_\-]/', '', $role));

        if (str_contains($r, 'sect')) return 'secthead';
        if (str_contains($r, 'dept')) return 'depthead';
        if (str_contains($r, 'ppc') || str_contains($r, 'ppchead') || str_contains($r, 'ppchead')) return 'ppchead';
        if (str_contains($r, 'qc')) return 'qc';
    if (str_contains($r, 'agm')) return 'agm';
    if (str_contains($r, 'procure') || str_contains($r, 'purchas')) return 'procurement';

        return $r;
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
