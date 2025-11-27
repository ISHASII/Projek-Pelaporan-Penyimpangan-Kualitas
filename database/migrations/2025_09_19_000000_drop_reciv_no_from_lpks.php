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
        if (Schema::hasTable('lpks')) {
            Schema::table('lpks', function (Blueprint $table) {
                if (Schema::hasColumn('lpks', 'reciv_no')) {
                    $table->dropColumn('reciv_no');
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
        if (Schema::hasTable('lpks')) {
            Schema::table('lpks', function (Blueprint $table) {
                if (! Schema::hasColumn('lpks', 'reciv_no')) {
                    $table->string('reciv_no')->nullable();
                }
            });
        }
    }
};
