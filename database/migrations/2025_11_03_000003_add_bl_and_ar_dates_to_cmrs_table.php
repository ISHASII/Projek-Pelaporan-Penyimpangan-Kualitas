<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('cmrs')) {
            Schema::table('cmrs', function (Blueprint $table) {
                if (!Schema::hasColumn('cmrs', 'bl_date')) {
                    $table->date('bl_date')->nullable()->after('tgl_delivery');
                }
                if (!Schema::hasColumn('cmrs', 'ar_date')) {
                    $table->date('ar_date')->nullable()->after('bl_date');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('cmrs')) {
            Schema::table('cmrs', function (Blueprint $table) {
                if (Schema::hasColumn('cmrs', 'ar_date')) {
                    $table->dropColumn('ar_date');
                }
                if (Schema::hasColumn('cmrs', 'bl_date')) {
                    $table->dropColumn('bl_date');
                }
            });
        }
    }
};
