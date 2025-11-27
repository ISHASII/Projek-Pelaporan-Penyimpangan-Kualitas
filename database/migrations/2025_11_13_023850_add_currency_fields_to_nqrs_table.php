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
            $table->string('pay_compensation_currency', 10)->nullable()->after('pay_compensation_value');
            $table->string('pay_compensation_currency_symbol', 10)->nullable()->after('pay_compensation_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nqrs', function (Blueprint $table) {
            $table->dropColumn(['pay_compensation_currency', 'pay_compensation_currency_symbol']);
        });
    }
};
