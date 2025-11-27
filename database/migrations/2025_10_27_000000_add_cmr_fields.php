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
        Schema::table('cmrs', function (Blueprint $table) {
            $table->date('tgl_terbit_cmr')->nullable()->after('deskripsi');
            $table->date('tgl_delivery')->nullable()->after('tgl_terbit_cmr');
            $table->string('nama_supplier')->nullable()->after('tgl_delivery');
            $table->string('nama_part')->nullable()->after('nama_supplier');
            $table->string('nomor_part')->nullable()->after('nama_part');
            $table->string('invoice_no')->nullable()->after('nomor_part');
            $table->string('order_no')->nullable()->after('invoice_no');
            $table->string('product')->nullable()->after('order_no');
            $table->string('location_claim_occurrence')->nullable()->after('product');
            $table->string('disposition_inventory_type')->nullable()->after('location_claim_occurrence');
            $table->string('disposition_inventory_choice')->nullable()->after('disposition_inventory_type');
            $table->string('claim_occurrence_frequency')->nullable()->after('disposition_inventory_choice');
            $table->string('dispatch_defective_parts')->nullable()->after('claim_occurrence_frequency');
            $table->string('disposition_defect_parts')->nullable()->after('dispatch_defective_parts');
            $table->integer('qty_deliv')->nullable()->after('disposition_defect_parts');
            $table->integer('qty_problem')->nullable()->after('qty_deliv');
            $table->string('gambar')->nullable()->after('qty_problem');
            $table->text('input_problem')->nullable()->after('gambar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cmrs', function (Blueprint $table) {
            $table->dropColumn([
                'tgl_terbit_cmr',
                'tgl_delivery',
                'nama_supplier',
                'nama_part',
                'nomor_part',
                'invoice_no',
                'order_no',
                'product',
                'location_claim_occurrence',
                'disposition_inventory_type',
                'disposition_inventory_choice',
                'claim_occurrence_frequency',
                'dispatch_defective_parts',
                'disposition_defect_parts',
                'qty_deliv',
                'qty_problem',
                'gambar',
                'input_problem'
            ]);
        });
    }
};