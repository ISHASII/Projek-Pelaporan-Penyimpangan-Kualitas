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
        Schema::table('notification_push', function (Blueprint $table) {
            if (!Schema::hasColumn('notification_push', 'user_email')) {
                $table->string('user_email', 150)->nullable()->after('phone_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_push', function (Blueprint $table) {
            if (Schema::hasColumn('notification_push', 'user_email')) {
                $table->dropColumn('user_email');
            }
        });
    }
};
