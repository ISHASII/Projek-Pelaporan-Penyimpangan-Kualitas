<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Part extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('por_item')->insert([
            ['kode' => 'ITEM001', 'description' => 'Front shock absorber',        'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM002', 'description' => 'Rear shock absorber',         'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM003', 'description' => 'Upper mounting bracket',      'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM004', 'description' => 'Lower mounting bracket',      'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM005', 'description' => 'Bushing set',                 'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM006', 'description' => 'Piston rod',                  'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM007', 'description' => 'Valve assembly',              'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM008', 'description' => 'Seal kit',                    'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM009', 'description' => 'Bearing set',                 'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'ITEM010', 'description' => 'Mounting bolt kit',           'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}