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
                if (!Schema::hasColumn('cmrs', 'qty_order')) {
                    $table->integer('qty_order')->nullable()->after('order_no');
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
                if (Schema::hasColumn('cmrs', 'qty_order')) {
                    $table->dropColumn('qty_order');
                }
            });
        }
    }
};
