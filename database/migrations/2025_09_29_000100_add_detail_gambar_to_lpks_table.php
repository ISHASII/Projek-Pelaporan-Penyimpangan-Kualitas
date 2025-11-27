<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lpks') && ! Schema::hasColumn('lpks', 'detail_gambar')) {
            Schema::table('lpks', function (Blueprint $table) {
                $table->string('detail_gambar')->nullable()->after('gambar');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lpks') && Schema::hasColumn('lpks', 'detail_gambar')) {
            Schema::table('lpks', function (Blueprint $table) {
                $table->dropColumn('detail_gambar');
            });
        }
    }
};
