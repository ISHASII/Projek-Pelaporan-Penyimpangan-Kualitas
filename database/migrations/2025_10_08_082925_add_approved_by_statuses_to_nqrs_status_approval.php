<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE nqrs MODIFY COLUMN status_approval ENUM(
            'Menunggu Request dikirimkan',
            'Menunggu Approval Foreman',
            'Approved by QC',
            'Menunggu Approval Sect Head',
            'Approved by Sect Head',
            'Menunggu Approval Dept Head',
            'Approved by Dept Head',
            'Menunggu Approval PPC Head',
            'Approved by PPC',
            'Selesai',
            'Ditolak Foreman',
            'Ditolak Sect Head',
            'Ditolak Dept Head',
            'Ditolak PPC Head'
        ) DEFAULT 'Menunggu Request dikirimkan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE nqrs MODIFY COLUMN status_approval ENUM(
            'Menunggu Request dikirimkan',
            'Menunggu Approval Foreman',
            'Menunggu Approval Sect Head',
            'Menunggu Approval Dept Head',
            'Menunggu Approval PPC Head',
            'Selesai',
            'Ditolak Foreman',
            'Ditolak Sect Head',
            'Ditolak Dept Head',
            'Ditolak PPC Head'
        ) DEFAULT 'Menunggu Request dikirimkan'");
    }
};