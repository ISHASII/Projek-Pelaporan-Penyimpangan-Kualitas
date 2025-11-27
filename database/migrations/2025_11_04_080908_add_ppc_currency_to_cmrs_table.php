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
            $table->string('ppc_currency', 10)->nullable()->after('ppchead_note')->comment('Currency for pay compensation: IDR, JPY, USD');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cmrs', function (Blueprint $table) {
            $table->dropColumn('ppc_currency');
        });
    }
};
