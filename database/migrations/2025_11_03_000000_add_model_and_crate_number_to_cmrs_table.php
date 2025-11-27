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
                if (!Schema::hasColumn('cmrs', 'model')) {
                    $table->string('model')->nullable()->after('product');
                }
                if (!Schema::hasColumn('cmrs', 'crate_number')) {
                    $table->string('crate_number')->nullable()->after('model');
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
                if (Schema::hasColumn('cmrs', 'crate_number')) {
                    $table->dropColumn('crate_number');
                }
                if (Schema::hasColumn('cmrs', 'model')) {
                    $table->dropColumn('model');
                }
            });
        }
    }
};
