<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Supplier extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('por_supplier')->insert([
            ['por_kode' => 'SUP001', 'por_nama' => 'PT. Alpha Supplier',   'created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP002', 'por_nama' => 'PT. Bravo Supply',     'created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP003', 'por_nama' => 'PT. Charlie Parts',    'created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP004', 'por_nama' => 'PT. Delta Components', 'created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP005', 'por_nama' => 'PT. Echo Industries',  'created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP006', 'por_nama' => 'PT. Foxtrot Works',   'created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP007', 'por_nama' => 'PT. Gamma Traders',    'created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP008', 'por_nama' => 'PT. Hotel Manufacturing','created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP009', 'por_nama' => 'PT. India Components', 'created_at' => $now, 'updated_at' => $now],
            ['por_kode' => 'SUP010', 'por_nama' => 'PT. Juliet Parts',     'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
