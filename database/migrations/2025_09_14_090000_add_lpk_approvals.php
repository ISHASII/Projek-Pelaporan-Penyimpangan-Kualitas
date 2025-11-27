<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lpks', function (Blueprint $table) {
            // Sect Head approval
            $table->string('secthead_status')->nullable(); // approved / rejected
            $table->text('secthead_note')->nullable();
            $table->unsignedBigInteger('secthead_approver_id')->nullable();
            $table->timestamp('secthead_approved_at')->nullable();

            // Dept Head approval
            $table->string('depthead_status')->nullable();
            $table->text('depthead_note')->nullable();
            $table->unsignedBigInteger('depthead_approver_id')->nullable();
            $table->timestamp('depthead_approved_at')->nullable();

            // PPC Head approval
            $table->string('ppchead_status')->nullable();
            $table->text('ppchead_note')->nullable();
            $table->unsignedBigInteger('ppchead_approver_id')->nullable();
            $table->timestamp('ppchead_approved_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('lpks', function (Blueprint $table) {
            $table->dropColumn([
                'secthead_status','secthead_note','secthead_approver_id','secthead_approved_at',
                'depthead_status','depthead_note','depthead_approver_id','depthead_approved_at',
                'ppchead_status','ppchead_note','ppchead_approver_id','ppchead_approved_at',
            ]);
        });
    }
};
