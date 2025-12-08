<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'npk',
        'role',
        'username',
        'email',
        'name',
        'nohp',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user role matches a given role keyword.
     * Normalizes role string similar to middleware: remove spaces/underscores/dashes and lowercase.
     */
    public function hasRole(string $role): bool
    {
        $r = strtolower(preg_replace('/[\s_\-]/', '', $this->role ?? ''));
        return str_contains($r, strtolower($role));
    }

    /**
     * If user's email is missing, attempt to fetch it from the external lembur DB
     * based on the user's npk and save it to the local users table.
     * Returns the email string when available, or null if not found.
     */
    public function syncEmailFromLembur(): ?string
    {
        if (!empty($this->email)) return $this->email;
        if (empty($this->npk)) return null;

        try {
            $row = \DB::connection('lembur')->table('ct_users_hash')->where('npk', $this->npk)->select('user_email')->first();
            if ($row && !empty($row->user_email)) {
                $this->email = $row->user_email;
                try { $this->save(); } catch (\Throwable $e) { /* ignore save failure */ }
                return $this->email;
            }
        } catch (\Throwable $e) {
            \Log::info('Failed to query lembur for email for NPK ' . $this->npk . ': ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Laravel notification hook to get the email where the message should be sent.
     * Falls back to lembur.ct_users_hash.user_email if local email is empty.
     */
    public function routeNotificationForMail($notification)
    {
        if (!empty($this->email)) return $this->email;
        if (empty($this->npk)) return null;
        try {
            return \DB::connection('lembur')->table('ct_users_hash')->where('npk', $this->npk)->value('user_email');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
