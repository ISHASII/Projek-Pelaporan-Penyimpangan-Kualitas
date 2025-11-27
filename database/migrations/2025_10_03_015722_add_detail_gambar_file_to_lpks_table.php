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
            $table->string('detail_gambar_file')->nullable()->after('detail_gambar'); // Path file gambar detail
            $table->string('detail_gambar_original_name')->nullable()->after('detail_gambar_file'); // Nama file asli
            $table->string('detail_gambar_mime_type')->nullable()->after('detail_gambar_original_name'); // Tipe MIME
            $table->integer('detail_gambar_file_size')->nullable()->after('detail_gambar_mime_type'); // Ukuran file
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpks', function (Blueprint $table) {
            $table->dropColumn([
                'detail_gambar_file',
                'detail_gambar_original_name',
                'detail_gambar_mime_type',
                'detail_gambar_file_size'
            ]);
        });
    }
};
