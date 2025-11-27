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
        if (Schema::hasTable('lpks') && ! Schema::hasColumn('lpks', 'requested_at_qc')) {
            Schema::table('lpks', function (Blueprint $table) {
                $table->timestamp('requested_at_qc')->nullable()->after('ppchead_approved_at');
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
        if (Schema::hasTable('lpks') && Schema::hasColumn('lpks', 'requested_at_qc')) {
            Schema::table('lpks', function (Blueprint $table) {
                $table->dropColumn('requested_at_qc');
            });
        }
    }
};
