<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('lpks')) {
            Schema::table('lpks', function (Blueprint $table) {
                $table->string('referensi_lka')->nullable()->after('nomor_po');
                $table->date('tgl_terbit_lka')->nullable()->after('referensi_lka');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lpks')) {
            Schema::table('lpks', function (Blueprint $table) {
                if (Schema::hasColumn('lpks', 'tgl_terbit_lka')) {
                    $table->dropColumn('tgl_terbit_lka');
                }
                if (Schema::hasColumn('lpks', 'referensi_lka')) {
                    $table->dropColumn('referensi_lka');
                }
            });
        }
    }
};
