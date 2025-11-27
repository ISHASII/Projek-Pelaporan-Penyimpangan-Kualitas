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
        Schema::table('lpks', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lpks', function (Blueprint $table) {
            if (Schema::hasColumn('lpks', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};
