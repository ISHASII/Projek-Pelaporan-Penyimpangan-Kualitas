<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ForemanUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('FOREMAN_SEED_EMAIL', 'foreman@example.com');
        $name = env('FOREMAN_SEED_NAME', 'Foreman User');
        $password = env('FOREMAN_SEED_PASSWORD', 'secret123');

        // If a user with this email exists, update role to 'foreman'. Otherwise insert a new user.
        $existing = DB::table('users')->where('email', $email)->first();

        $now = now();

        if ($existing) {
            $update = [
                'role' => 'foreman',
                'name' => $name,
                // Ensure password is set to a secure hash when updating existing record
                'password' => Hash::make($password),
                'updated_at' => $now,
            ];

            // If npk column exists and current record has no npk, assign one
            if (Schema::hasColumn('users', 'npk')) {
                if (empty($existing->npk)) {
                    $candidate = null;
                    do {
                        $candidate = strtoupper('F' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));
                    } while (DB::table('users')->where('npk', $candidate)->exists());
                    $update['npk'] = $candidate;
                }
            }

            // If nohp column exists ensure it's set (use env fallback)
            if (Schema::hasColumn('users', 'nohp')) {
                if (empty($existing->nohp)) {
                    $update['nohp'] = env('FOREMAN_SEED_NOHP', '081100000001');
                }
            }

            DB::table('users')->where('id', $existing->id)->update($update);
        } else {
            $insert = [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'foreman',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Only include email if the column exists in the users table
            if (Schema::hasColumn('users', 'email')) {
                $insert['email'] = $email;
                if (Schema::hasColumn('users', 'email_verified_at')) {
                    $insert['email_verified_at'] = $now;
                }
            }

            // Include npk if required
            if (Schema::hasColumn('users', 'npk')) {
                $candidate = null;
                do {
                    $candidate = strtoupper('F' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));
                } while (DB::table('users')->where('npk', $candidate)->exists());
                $insert['npk'] = $candidate;
            }

            // Include nohp if exists
            if (Schema::hasColumn('users', 'nohp')) {
                $insert['nohp'] = env('FOREMAN_SEED_NOHP', '081100000001');
            }

            // Include username if exists
            if (Schema::hasColumn('users', 'username')) {
                $insert['username'] = env('FOREMAN_SEED_USERNAME', 'foreman');
            }

            DB::table('users')->insert($insert);
        }

        $this->command->info('Foreman user seeded/updated: ' . $email . ' (password from env FOREMAN_SEED_PASSWORD)');
    }
}