<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * DESTRUCTIVE migration: only run if you are sure it's safe to remove email column
 * - Drops `email` and `email_verified_at` from `users`
 * - Drops `email` primary from `password_reset_tokens` and sets `nohp` as primary
 */
return new class extends Migration
{
    public function up(): void
    {
        // users table alterations
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email')) {
                // drop unique index first if exists
                try {
                    $table->dropUnique(['email']);
                } catch (\Throwable $e) {
                    // ignore if index doesn't exist
                }

                if (Schema::hasColumn('users', 'email_verified_at')) {
                    $table->dropColumn('email_verified_at');
                }

                $table->dropColumn('email');
            }
        });

        // password_reset_tokens adjustments
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // drop existing primary/email column
            if (Schema::hasColumn('password_reset_tokens', 'email')) {
                $table->dropPrimary(['email']);
                $table->dropColumn('email');
            }

            if (!Schema::hasColumn('password_reset_tokens', 'nohp')) {
                $table->string('nohp')->primary();
            } else {
                // if present, make it primary
                $table->primary('nohp');
            }
        });
    }

    public function down(): void
    {
        // restore email columns
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            if (Schema::hasColumn('password_reset_tokens', 'nohp')) {
                try {
                    $table->dropPrimary(['nohp']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('nohp');
            }

            if (!Schema::hasColumn('password_reset_tokens', 'email')) {
                $table->string('email')->primary();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
        });
    }
};