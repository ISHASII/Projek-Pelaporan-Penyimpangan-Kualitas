<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed 4 users for roles: qc, secthead, depthead, ppchead.
     * Fields per user: npk (5 chars, unique), username (unique), name, nohp (unique), password.
     * Note: role is assigned after create/update to avoid mass-assignment issues.
     */
    public function run(): void
    {
        $users = [
            [
                'npk' => 'QC001',
                'username' => 'qcadmin',
                'name' => 'QC Admin',
                'nohp' => '089990000001',
                'password' => 'password',
                'role' => 'qc',
            ],
            [
                'npk' => 'SH001',
                'username' => 'secthead',
                'name' => 'Sect Head',
                'nohp' => '089990000002',
                'password' => 'password',
                'role' => 'secthead',
            ],
            [
                'npk' => 'DH001',
                'username' => 'depthead',
                'name' => 'Dept Head',
                'nohp' => '089990000003',
                'password' => 'password',
                'role' => 'depthead',
            ],
            [
                'npk' => 'PH001',
                'username' => 'ppchead',
                'name' => 'PPC Head',
                'nohp' => '089990000004',
                'password' => 'password',
                'role' => 'ppchead',
            ],
            [
                'npk' => 'AGM01',
                'username' => 'agmuser',
                'name' => 'Assistant GM',
                'nohp' => '089990000005',
                'password' => 'password',
                'role' => 'agm',
            ],
            [
                'npk' => 'PR001',
                'username' => 'procurement',
                'name' => 'Procurement User',
                'nohp' => '089990000006',
                'password' => 'password',
                'role' => 'procurement',
            ],
        ];

        foreach ($users as $u) {
            // Use unique NPK as the key for idempotency; update other fields if user exists
            $user = User::updateOrCreate(
                ['npk' => $u['npk']],
                [
                    'username' => $u['username'],
                    'name' => $u['name'],
                    'nohp' => $u['nohp'],
                    'password' => $u['password'], // will be hashed by cast
                ]
            );

            // Assign role safely (role is not in $fillable)
            $user->role = $u['role'];
            $user->save();
        }
    }
}