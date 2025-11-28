<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class VddUserSeeder extends Seeder
{

    public function run(): void
    {
        $data = [
            'npk' => 'VDD01',
            'username' => 'vdduser',
            'name' => 'VDD User',
            'nohp' => '089990000010',
            'password' => 'password',
            'role' => 'vdd',
        ];

        $user = User::updateOrCreate(
            ['npk' => $data['npk']],
            [
                'username' => $data['username'],
                'name' => $data['name'],
                'nohp' => $data['nohp'],
                'password' => $data['password'],
            ]
        );

        // Ensure role is set (role is mass-assignable but set explicitly for clarity)
        $user->role = $data['role'];
        $user->save();
    }
}
