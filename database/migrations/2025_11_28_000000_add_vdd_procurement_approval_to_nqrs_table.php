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
        // Extend enum to include VDD and Procurement statuses
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
            'Menunggu Approval VDD',
            'Menunggu Approval Procurement',
            'Selesai',
            'Ditolak Foreman',
            'Ditolak Sect Head',
            'Ditolak Dept Head',
            'Ditolak PPC Head',
            'Ditolak VDD',
            'Ditolak Procurement'
        ) DEFAULT 'Menunggu Request dikirimkan'");

        Schema::table('nqrs', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by_vdd')->nullable()->after('approved_at_ppc');
            $table->timestamp('approved_at_vdd')->nullable()->after('approved_by_vdd');

            $table->unsignedBigInteger('approved_by_procurement')->nullable()->after('approved_at_vdd');
            $table->timestamp('approved_at_procurement')->nullable()->after('approved_by_procurement');

            $table->foreign('approved_by_vdd')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_procurement')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nqrs', function (Blueprint $table) {
            $table->dropForeign(['approved_by_vdd']);
            $table->dropForeign(['approved_by_procurement']);

            $table->dropColumn([
                'approved_by_vdd',
                'approved_at_vdd',
                'approved_by_procurement',
                'approved_at_procurement'
            ]);
        });

        // Revert enum to previous set (without VDD/Procurement)
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
};
