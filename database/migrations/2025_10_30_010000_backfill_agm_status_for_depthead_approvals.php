<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure AGM columns exist (safe if previous migration already added them)
        Schema::table('cmrs', function (Blueprint $table) {
            if (! Schema::hasColumn('cmrs', 'agm_status')) {
                $table->string('agm_status')->nullable()->after('depthead_status');
            }
            if (! Schema::hasColumn('cmrs', 'agm_note')) {
                $table->text('agm_note')->nullable()->after('agm_status');
            }
            if (! Schema::hasColumn('cmrs', 'agm_approver_id')) {
                $table->unsignedBigInteger('agm_approver_id')->nullable()->after('agm_note');
            }
            if (! Schema::hasColumn('cmrs', 'agm_approved_at')) {
                $table->timestamp('agm_approved_at')->nullable()->after('agm_approver_id');
            }
            if (! Schema::hasColumn('cmrs', 'status_approval')) {
                $table->string('status_approval')->nullable()->after('requested_at_qc');
            }
        });

        // Backfill existing CMRs where Dept Head already approved but AGM stage is empty:
        DB::table('cmrs')
            ->where('depthead_status', 'approved')
            ->whereNull('agm_status')
            ->update([
                'agm_status' => 'pending',
                'agm_approver_id' => null,
                'agm_approved_at' => null,
                'status_approval' => 'Waiting for AGM approval'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't drop the columns in down() to avoid data loss in production.
        // However, to be reversible in development you can manually remove them.
    }
};
