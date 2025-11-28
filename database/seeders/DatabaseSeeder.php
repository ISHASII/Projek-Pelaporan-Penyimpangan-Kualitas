<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seed example test user and role users
        // create test user only if not exists (avoid duplicate on repeated seed)
        if (!User::where('nohp', '081100000000')->exists()) {
            User::factory()->create([
                'username' => 'testuser',
                'name' => 'Test User',
                'nohp' => '081100000000',
            ]);
        }

        $this->call([
            UserSeeder::class,
            // \Database\Seeders\LpkSeeder::class,
            \Database\Seeders\ForemanUserSeeder::class,
        ]);
    }
}
