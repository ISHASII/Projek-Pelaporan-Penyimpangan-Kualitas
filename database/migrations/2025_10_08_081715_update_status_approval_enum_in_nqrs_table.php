<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter column with all enum values (old and new)
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
        // Cannot rollback - data migration is irreversible
        throw new \Exception('This migration cannot be rolled back.');
    }
};