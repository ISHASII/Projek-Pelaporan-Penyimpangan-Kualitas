<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Backfill existing NULLs with safe default values
        DB::table('lpks')->whereNull('perlakuan_terhadap_part')->update(['perlakuan_terhadap_part' => 'N/A']);
        DB::table('lpks')->whereNull('frekuensi_claim')->update(['frekuensi_claim' => 'N/A']);
        DB::table('lpks')->whereNull('perlakuan_part_defect')->update(['perlakuan_part_defect' => 'N/A']);
        DB::table('lpks')->whereNull('lokasi_penemuan_claim')->update(['lokasi_penemuan_claim' => 'N/A']);
        DB::table('lpks')->whereNull('status_repair')->update(['status_repair' => 'N/A']);

        // Try to alter columns to NOT NULL. Note: some DB drivers require doctrine/dbal for column alteration.
        try {
            Schema::table('lpks', function (Blueprint $table) {
                $table->string('perlakuan_terhadap_part')->nullable(false)->change();
                $table->string('frekuensi_claim')->nullable(false)->change();
                $table->string('perlakuan_part_defect')->nullable(false)->change();
                $table->string('lokasi_penemuan_claim')->nullable(false)->change();
                $table->string('status_repair')->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            // If change() is not supported (e.g., doctrine/dbal missing or SQLite), write a warning to logs and continue.
            info('make_lpk_dropdowns_not_null migration: could not alter columns to NOT NULL automatically: ' . $e->getMessage());
        }
    }

    public function down()
    {
        try {
            Schema::table('lpks', function (Blueprint $table) {
                $table->string('perlakuan_terhadap_part')->nullable()->change();
                $table->string('frekuensi_claim')->nullable()->change();
                $table->string('perlakuan_part_defect')->nullable()->change();
                $table->string('lokasi_penemuan_claim')->nullable()->change();
                $table->string('status_repair')->nullable()->change();
            });
        } catch (\Throwable $e) {
            info('make_lpk_dropdowns_not_null migration down: could not revert column nullability: ' . $e->getMessage());
        }

        // No data rollback for backfilled values (they will remain 'N/A')
    }
};