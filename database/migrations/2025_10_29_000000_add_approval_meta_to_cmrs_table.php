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
                if (! Schema::hasColumn('cmrs', 'requested_at_qc')) {
                    $table->timestamp('requested_at_qc')->nullable();
                }
                if (! Schema::hasColumn('cmrs', 'status_approval')) {
                    $table->string('status_approval')->nullable();
                }

                // Approver metadata for sect, dept and ppc (if not already present)
                if (! Schema::hasColumn('cmrs', 'secthead_note')) {
                    $table->text('secthead_note')->nullable();
                }
                if (! Schema::hasColumn('cmrs', 'secthead_approver_id')) {
                    $table->unsignedBigInteger('secthead_approver_id')->nullable();
                }
                if (! Schema::hasColumn('cmrs', 'secthead_approved_at')) {
                    $table->timestamp('secthead_approved_at')->nullable();
                }

                if (! Schema::hasColumn('cmrs', 'depthead_note')) {
                    $table->text('depthead_note')->nullable();
                }
                if (! Schema::hasColumn('cmrs', 'depthead_approver_id')) {
                    $table->unsignedBigInteger('depthead_approver_id')->nullable();
                }
                if (! Schema::hasColumn('cmrs', 'depthead_approved_at')) {
                    $table->timestamp('depthead_approved_at')->nullable();
                }

                if (! Schema::hasColumn('cmrs', 'ppchead_note')) {
                    $table->text('ppchead_note')->nullable();
                }
                if (! Schema::hasColumn('cmrs', 'ppchead_approver_id')) {
                    $table->unsignedBigInteger('ppchead_approver_id')->nullable();
                }
                if (! Schema::hasColumn('cmrs', 'ppchead_approved_at')) {
                    $table->timestamp('ppchead_approved_at')->nullable();
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
                if (Schema::hasColumn('cmrs', 'ppchead_approved_at')) {
                    $table->dropColumn('ppchead_approved_at');
                }
                if (Schema::hasColumn('cmrs', 'ppchead_approver_id')) {
                    $table->dropColumn('ppchead_approver_id');
                }
                if (Schema::hasColumn('cmrs', 'ppchead_note')) {
                    $table->dropColumn('ppchead_note');
                }

                if (Schema::hasColumn('cmrs', 'depthead_approved_at')) {
                    $table->dropColumn('depthead_approved_at');
                }
                if (Schema::hasColumn('cmrs', 'depthead_approver_id')) {
                    $table->dropColumn('depthead_approver_id');
                }
                if (Schema::hasColumn('cmrs', 'depthead_note')) {
                    $table->dropColumn('depthead_note');
                }

                if (Schema::hasColumn('cmrs', 'secthead_approved_at')) {
                    $table->dropColumn('secthead_approved_at');
                }
                if (Schema::hasColumn('cmrs', 'secthead_approver_id')) {
                    $table->dropColumn('secthead_approver_id');
                }
                if (Schema::hasColumn('cmrs', 'secthead_note')) {
                    $table->dropColumn('secthead_note');
                }

                if (Schema::hasColumn('cmrs', 'status_approval')) {
                    $table->dropColumn('status_approval');
                }
                if (Schema::hasColumn('cmrs', 'requested_at_qc')) {
                    $table->dropColumn('requested_at_qc');
                }
            });
        }
    }
};