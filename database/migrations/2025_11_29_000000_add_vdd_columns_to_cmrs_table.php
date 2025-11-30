<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('cmrs')) {
            Schema::table('cmrs', function (Blueprint $table) {
                if (!Schema::hasColumn('cmrs', 'vdd_status')) {
                    $table->string('vdd_status')->nullable()->after('ppchead_status');
                }
                if (!Schema::hasColumn('cmrs', 'vdd_note')) {
                    $table->text('vdd_note')->nullable()->after('vdd_status');
                }
                if (!Schema::hasColumn('cmrs', 'vdd_approver_id')) {
                    $table->unsignedBigInteger('vdd_approver_id')->nullable()->after('vdd_note');
                }
                if (!Schema::hasColumn('cmrs', 'vdd_approved_at')) {
                    $table->timestamp('vdd_approved_at')->nullable()->after('vdd_approver_id');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('cmrs')) {
            Schema::table('cmrs', function (Blueprint $table) {
                if (Schema::hasColumn('cmrs', 'vdd_approved_at')) {
                    $table->dropColumn('vdd_approved_at');
                }
                if (Schema::hasColumn('cmrs', 'vdd_approver_id')) {
                    $table->dropColumn('vdd_approver_id');
                }
                if (Schema::hasColumn('cmrs', 'vdd_note')) {
                    $table->dropColumn('vdd_note');
                }
                if (Schema::hasColumn('cmrs', 'vdd_status')) {
                    $table->dropColumn('vdd_status');
                }
            });
        }
    }
};
