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
        // Update ENUM status_nqr dari 'Complaint' menjadi 'Complaint (Informasi)'
        DB::statement("ALTER TABLE nqrs MODIFY COLUMN status_nqr ENUM('Claim', 'Complaint (Informasi)')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke nilai lama jika rollback
        DB::statement("ALTER TABLE nqrs MODIFY COLUMN status_nqr ENUM('Claim', 'Complaint')");
    }
};
