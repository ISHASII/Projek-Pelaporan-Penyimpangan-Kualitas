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
        Schema::create('lpk_detail_gambar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpk_id')->constrained('lpks')->onDelete('cascade');
            $table->string('gambar_path')->nullable(); // Path gambar
            $table->string('original_name')->nullable(); // Nama file asli
            $table->string('mime_type')->nullable(); // Tipe file
            $table->integer('file_size')->nullable(); // Ukuran file dalam bytes
            $table->text('keterangan')->nullable(); // Keterangan gambar
            $table->enum('type', ['detail_gambar', 'gambar_utama'])->default('detail_gambar'); // Tipe gambar
            $table->integer('urutan')->default(1); // Urutan gambar jika ada multiple
            $table->timestamps();

            // Index untuk performance
            $table->index(['lpk_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpk_detail_gambar');
    }
};
