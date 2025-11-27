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
        Schema::create('nqrs', function (Blueprint $table) {
            $table->id();

            // Data yang diinput QC
            $table->string('no_reg_nqr')->unique();
            $table->date('tgl_terbit_nqr');
            $table->date('tgl_delivery');
            $table->string('nama_supplier');
            $table->string('nama_part');
            $table->string('nomor_po');
            $table->string('nomor_part');
            $table->enum('status_nqr', ['Claim', 'Complaint']);
            $table->enum('location_claim_occur', ['Receiving Insp', 'In-Process', 'Customer']);

            // Disposition of Inventory
            $table->enum('disposition_inventory_location', ['At Customer', 'At PT.KYBI']);
            $table->string('disposition_inventory_action')->nullable();

            $table->enum('claim_occurence_freq', ['First Time', 'Reoccured/Routin']);
            $table->enum('disposition_defect_part', ['Keep to Use', 'Return to Supplier', 'Scrapped at PT.KYBI']);
            $table->string('invoice');
            $table->string('order');
            $table->string('total_del');
            $table->string('total_claim');
            $table->string('gambar')->nullable();
            $table->text('detail_gambar')->nullable();

            // Data yang Diinput PPC
            $table->enum('disposition_claim', ['Pay Compensation', 'Send the Replacement'])->nullable();
            $table->string('pay_compensation_value')->nullable();
            $table->enum('send_replacement_method', ['By Air', 'By Sea'])->nullable();

            // Tracking
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nqrs');
    }
};