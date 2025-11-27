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
            // Modify detail_gambar column to allow up to 300 characters
            $table->string('detail_gambar', 300)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpks', function (Blueprint $table) {
            // Revert detail_gambar column back to default 255 characters
            $table->string('detail_gambar', 255)->nullable()->change();
        });
    }
};