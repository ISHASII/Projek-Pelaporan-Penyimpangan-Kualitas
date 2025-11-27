<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lpks', function (Blueprint $table) {
            if (!Schema::hasColumn('lpks', 'nomor_part')) {
                $table->string('nomor_part')->nullable()->after('nama_part');
                $table->index('nomor_part', 'lpks_nomor_part_index');
            }
        });
    }

    public function down()
    {
        Schema::table('lpks', function (Blueprint $table) {
            if (Schema::hasColumn('lpks', 'nomor_part')) {
                // Drop index if exists
                try { $table->dropIndex('lpks_nomor_part_index'); } catch (\Throwable $e) { /* ignore */ }
                $table->dropColumn('nomor_part');
            }
        });
    }
};