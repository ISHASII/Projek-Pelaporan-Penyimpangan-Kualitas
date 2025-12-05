<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        // Authenticate exclusively via the external "lembur" DB (table: ct_users_hash)
        $extUser = null;
        try {
            $extUser = DB::connection('lembur')->table('ct_users_hash')->where('npk', $request->npk)->first();
        } catch (\Exception $e) {
            // If external DB cannot be reached, fail with a generic error (do not fallback to local DB)
            \Log::error('Lembur DB connection failed: ' . $e->getMessage());
            return back()->withErrors(['npk' => 'Authentication service unavailable']);
        }

        // If ext user not found, reject login
        if (!$extUser) {
            return back()->withErrors(['npk' => 'NPK atau password salah']);
        }

        // Verify password - try common hash column names
        $hashCols = ['password', 'pass', 'hash', 'passwd', 'pwd', 'passhash'];
        $storedHash = null;
        foreach ($hashCols as $c) {
            if (isset($extUser->{$c}) && $extUser->{$c}) {
                $storedHash = $extUser->{$c};
                break;
            }
        }

        $passwordOk = false;
        if ($storedHash) {
            // Detect algorithm based on hash format/length
            if (Str::startsWith($storedHash, ['$2y$', '$2a$'])) {
                $passwordOk = Hash::check($request->password, $storedHash);
            } elseif (strlen($storedHash) === 32) {
                $passwordOk = md5($request->password) === $storedHash;
            } elseif (strlen($storedHash) === 40) {
                $passwordOk = sha1($request->password) === $storedHash;
            } elseif (strlen($storedHash) === 64) {
                $passwordOk = hash('sha256', $request->password) === $storedHash;
            } else {
                // Fallback to Laravel Hash
                try {
                    $passwordOk = Hash::check($request->password, $storedHash);
                } catch (\Throwable $th) {
                    $passwordOk = false;
                }
            }
        }

        if (!$passwordOk) {
            return back()->withErrors(['npk' => 'NPK atau password salah']);
        }

        // Determine role from extUser fields: dept, golongan, acting
        $role = $this->mapExternalToRole($extUser);
        if (!$role) {
            return back()->withErrors(['npk' => 'Akun tidak memiliki akses ke sistem ini']);
        }

        // Find or create local user with same npk
        $preferredName = $extUser->full_name ?? ($extUser->name ?? ($extUser->username ?? $request->npk));
        $preferredUsername = $extUser->user_email ?? ($extUser->username ?? $request->npk);

        // Check if user already exists
        $existingUser = User::where('npk', $request->npk)->first();

        if ($existingUser) {
            // Update existing user
            $newUsername = $this->getUniqueUsername($preferredUsername, $request->npk);
            $existingUser->update([
                'role' => $role,
                'name' => $preferredName,
                'username' => $newUsername,
            ]);
            $user = $existingUser;
        } else {
            // Ensure username uniqueness. If the preferred username already exists for a different NPK,
            // fall back to a deterministic username using npk and append suffixes if needed.
            $sanitizedUsername = $this->getUniqueUsername($preferredUsername, $request->npk);
            $existingByUsername = User::where('username', $sanitizedUsername)->first();
            if ($existingByUsername && $existingByUsername->npk !== $request->npk) {
                // Use npk@lembur as a deterministic base
                $base = strtolower($request->npk . '@lembur');
                $candidate = $base;
                $i = 1;
                while (User::where('username', $candidate)->exists()) {
                    $candidate = $base . '-' . $i;
                    $i++;
                }
                $sanitizedUsername = $candidate;
            }

            // Create new user with random password
            $user = User::create([
                'npk' => $request->npk,
                'role' => $role,
                'name' => $preferredName,
                'username' => $sanitizedUsername,
                'password' => Hash::make(Str::random(40)),
            ]);
        }

        // Create OTP for two-factor authentication
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
            'foreman' => redirect()->route('foreman.dashboard'),
            'secthead' => redirect()->route('dashboard.secthead'),
            'depthead' => redirect()->route('dashboard.depthead'),
            'ppchead' => redirect()->route('dashboard.ppchead'),
            'agm' => redirect()->route('dashboard.agm'),
            'vdd' => redirect()->route('dashboard'),
            'procurement' => redirect()->route('dashboard.procurement'),
            default => redirect()->route('dashboard'),
        };
    }

    private function normalizeRole(?string $role): string
    {
        if (!$role) return '';
        $r = strtolower(preg_replace('/[\s_\-]/', '', $role));

        if (str_contains($r, 'sect')) return 'secthead';
        if (str_contains($r, 'dept')) return 'depthead';
        if (str_contains($r, 'ppc') || str_contains($r, 'ppchead')) return 'ppchead';
        if (str_contains($r, 'foreman')) return 'foreman';
        if (str_contains($r, 'qc')) return 'qc';
        if (str_contains($r, 'agm')) return 'agm';
        if (str_contains($r, 'vdd')) return 'vdd';
        if (str_contains($r, 'procure') || str_contains($r, 'purchas')) return 'procurement';

        return $r;
    }

    /**
     * Map an external lembur user row to a role string used in app's User model.
     * Returns role slug or null if not matched.
     *
     * Role mapping rules:
     * - QC: dept=QA, golongan in [0,1,2], acting=2
     * - Foreman: dept=QA, golongan=3, acting in [1,2]
     * - Sect Head: dept=QA, golongan=4, acting=2
     * - Dept Head: dept=QA, golongan=4, acting=1
     * - AGM: dept=AGM, golongan=4, acting=1
     * - PPC Head: dept=PPC, golongan=4, acting=1
     * - VDD: dept=VDD, golongan=4, acting=1
     * - Procurement: dept=PROCUREMENT, golongan=4, acting=1
     */
    private function mapExternalToRole(object $extUser): ?string
    {
        $dept = strtoupper(trim($extUser->dept ?? ($extUser->department ?? '')));
        $gol = isset($extUser->golongan) ? intval($extUser->golongan) : null;
        $act = isset($extUser->acting) ? intval($extUser->acting) : null;

        // QA Department roles
        if ($dept === 'QA') {
            // Foreman: golongan=3, acting in [1,2]
            if ($gol === 3 && in_array($act, [1, 2], true)) {
                return 'foreman';
            }
            // QC: golongan in [0,1,2], acting=2
            if (in_array($gol, [0, 1, 2], true) && $act === 2) {
                return 'qc';
            }
            // Sect Head: golongan=4, acting=2
            if ($gol === 4 && $act === 2) {
                return 'secthead';
            }
            // Dept Head: golongan=4, acting=1
            if ($gol === 4 && $act === 1) {
                return 'depthead';
            }
        }

        // AGM: dept=AGM, golongan=4, acting=1
        if ($dept === 'AGM' && $gol === 4 && $act === 1) {
            return 'agm';
        }

        // PPC Head: dept=PPC, golongan=4, acting=1
        if ($dept === 'PPC' && $gol === 4 && $act === 1) {
            return 'ppchead';
        }

        // VDD: dept=VDD, golongan=4, acting=1
        if ($dept === 'VDD' && $gol === 4 && $act === 1) {
            return 'vdd';
        }

        // Procurement: dept=PROCUREMENT, golongan=4, acting=1
        if ($dept === 'PROCUREMENT' && $gol === 4 && $act === 1) {
            return 'procurement';
        }

        return null;
    }

    /**
     * Generate a unique username for a given preferred username and npk
     * - If preferred username is unused or used by the same NPK, it's returned
     * - Otherwise fallback to npk@lembur and append suffixes until unique
     */
    private function getUniqueUsername(?string $preferred, string $npk): string
    {
        $preferred = strtolower(trim((string) ($preferred ?? '')));
        // sanitizing: if empty set to fallback base
        $base = strtolower($npk . '@lembur');

        if ($preferred === '' || strlen($preferred) > 255) {
            $candidate = $base;
        } else {
            $candidate = $preferred;
        }

        $existing = User::where('username', $candidate)->first();
        if (!$existing) {
            return $candidate;
        }

        // If it already exists but belongs to this npk, return it
        if ($existing && $existing->npk === $npk) {
            return $candidate;
        }

        // Fallback to base + suffix
        $i = 1;
        $candidate = $base;
        while (User::where('username', $candidate)->exists()) {
            $candidate = $base . '-' . $i;
            $i++;
        }
        return $candidate;
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
