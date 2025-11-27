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
        Schema::table('lpks', function (Blueprint $table) {
            $table->string('customer_pt_name')->nullable()->after('lokasi_penemuan_claim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpks', function (Blueprint $table) {
            $table->dropColumn('customer_pt_name');
        });
    }
};
