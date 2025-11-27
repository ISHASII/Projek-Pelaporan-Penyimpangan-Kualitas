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
        if (Schema::hasTable('cmrs')) {
            Schema::table('cmrs', function (Blueprint $table) {
                if (!Schema::hasColumn('cmrs', 'secthead_status')) {
                    $table->string('secthead_status')->nullable()->after('input_problem');
                }
                if (!Schema::hasColumn('cmrs', 'depthead_status')) {
                    $table->string('depthead_status')->nullable()->after('secthead_status');
                }
                if (!Schema::hasColumn('cmrs', 'ppchead_status')) {
                    $table->string('ppchead_status')->nullable()->after('depthead_status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cmrs')) {
            Schema::table('cmrs', function (Blueprint $table) {
                if (Schema::hasColumn('cmrs', 'ppchead_status')) {
                    $table->dropColumn('ppchead_status');
                }
                if (Schema::hasColumn('cmrs', 'depthead_status')) {
                    $table->dropColumn('depthead_status');
                }
                if (Schema::hasColumn('cmrs', 'secthead_status')) {
                    $table->dropColumn('secthead_status');
                }
            });
        }
    }
};