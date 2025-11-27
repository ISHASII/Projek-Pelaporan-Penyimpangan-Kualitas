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
        Schema::table('cmrs', function (Blueprint $table) {
            if (! Schema::hasColumn('cmrs', 'agm_status')) {
                $table->string('agm_status')->nullable();
                $table->text('agm_note')->nullable();
                $table->unsignedBigInteger('agm_approver_id')->nullable();
                $table->timestamp('agm_approved_at')->nullable();
            }

            if (! Schema::hasColumn('cmrs', 'procurement_status')) {
                $table->string('procurement_status')->nullable();
                $table->text('procurement_note')->nullable();
                $table->unsignedBigInteger('procurement_approver_id')->nullable();
                $table->timestamp('procurement_approved_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cmrs', function (Blueprint $table) {
            if (Schema::hasColumn('cmrs', 'agm_status')) {
                $table->dropColumn(['agm_status', 'agm_note', 'agm_approver_id', 'agm_approved_at']);
            }
            if (Schema::hasColumn('cmrs', 'procurement_status')) {
                $table->dropColumn(['procurement_status', 'procurement_note', 'procurement_approver_id', 'procurement_approved_at']);
            }
        });
    }
};
