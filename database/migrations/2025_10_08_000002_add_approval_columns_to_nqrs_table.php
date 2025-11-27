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
        Schema::table('nqrs', function (Blueprint $table) {
            // Status approval utama
            $table->enum('status_approval', ['Draft', 'Requested', 'Approved by QC', 'Approved by Sect Head', 'Approved by Dept Head', 'Approved by PPC', 'Rejected'])->default('Draft')->after('status_nqr');

            // Approval tracking dengan user_id dan timestamp
            $table->unsignedBigInteger('approved_by_qc')->nullable()->after('status_approval');
            $table->timestamp('approved_at_qc')->nullable()->after('approved_by_qc');

            $table->unsignedBigInteger('approved_by_sect_head')->nullable()->after('approved_at_qc');
            $table->timestamp('approved_at_sect_head')->nullable()->after('approved_by_sect_head');

            $table->unsignedBigInteger('approved_by_dept_head')->nullable()->after('approved_at_sect_head');
            $table->timestamp('approved_at_dept_head')->nullable()->after('approved_by_dept_head');

            $table->unsignedBigInteger('approved_by_ppc')->nullable()->after('approved_at_dept_head');
            $table->timestamp('approved_at_ppc')->nullable()->after('approved_by_ppc');

            // Request tracking
            $table->unsignedBigInteger('requested_by')->nullable()->after('approved_at_ppc');
            $table->timestamp('requested_at')->nullable()->after('requested_by');

            // Rejection tracking
            $table->unsignedBigInteger('rejected_by')->nullable()->after('requested_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_reason')->nullable()->after('rejected_at');

            // Foreign keys
            $table->foreign('approved_by_qc')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_sect_head')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_dept_head')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by_ppc')->references('id')->on('users')->onDelete('set null');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nqrs', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['approved_by_qc']);
            $table->dropForeign(['approved_by_sect_head']);
            $table->dropForeign(['approved_by_dept_head']);
            $table->dropForeign(['approved_by_ppc']);
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['rejected_by']);

            // Drop columns
            $table->dropColumn([
                'status_approval',
                'approved_by_qc',
                'approved_at_qc',
                'approved_by_sect_head',
                'approved_at_sect_head',
                'approved_by_dept_head',
                'approved_at_dept_head',
                'approved_by_ppc',
                'approved_at_ppc',
                'requested_by',
                'requested_at',
                'rejected_by',
                'rejected_at',
                'rejection_reason'
            ]);
        });
    }
};
