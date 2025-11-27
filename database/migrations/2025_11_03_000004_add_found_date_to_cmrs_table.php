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
                if (!Schema::hasColumn('cmrs', 'found_date')) {
                    $table->date('found_date')->nullable()->after('ar_date');
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
                if (Schema::hasColumn('cmrs', 'found_date')) {
                    $table->dropColumn('found_date');
                }
            });
        }
    }
};