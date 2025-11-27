<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lpks', function (Blueprint $table) {
            $table->id();
            $table->string('no_reg');
            $table->date('tgl_terbit');
            $table->date('tgl_delivery')->nullable();
            $table->string('reciv_no')->nullable();
            $table->string('nama_supply')->nullable();
            $table->string('nama_part')->nullable();
            $table->string('nomor_po')->nullable();
            $table->string('status')->nullable(); // Claim / Informasi
            $table->string('jenis_ng')->nullable(); // Quality / Delivery
            $table->string('gambar')->nullable();
            $table->integer('total_check')->nullable();
            $table->integer('total_ng')->nullable();
            $table->integer('total_delivery')->nullable();
            $table->integer('total_claim')->nullable();
            $table->decimal('percentage', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lpks');
    }
};