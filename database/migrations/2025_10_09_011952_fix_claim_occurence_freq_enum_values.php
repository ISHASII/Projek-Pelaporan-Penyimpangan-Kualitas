<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah nilai ENUM claim_occurence_freq dengan ejaan yang benar
        DB::statement("ALTER TABLE nqrs MODIFY COLUMN claim_occurence_freq ENUM('First Time', 'Reoccurred/Routine')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke nilai lama jika rollback
        DB::statement("ALTER TABLE nqrs MODIFY COLUMN claim_occurence_freq ENUM('First Time', 'Reoccured/Routin')");
    }
};