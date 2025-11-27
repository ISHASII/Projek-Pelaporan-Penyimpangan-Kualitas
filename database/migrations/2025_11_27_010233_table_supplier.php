<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('por_supplier', function (Blueprint $table) {
            $table->id();
            $table->string('por_kode')->unique();
            $table->string('por_nama');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('por_supplier');
    }
};
