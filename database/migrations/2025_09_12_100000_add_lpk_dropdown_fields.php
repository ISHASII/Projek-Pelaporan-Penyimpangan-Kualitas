<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lpks', function (Blueprint $table) {
            $table->string('perlakuan_terhadap_part')->nullable();
            $table->string('frekuensi_claim')->nullable();
            $table->string('perlakuan_part_defect')->nullable();
            $table->string('lokasi_penemuan_claim')->nullable();
            $table->string('status_repair')->nullable();
        });
    }

    public function down()
    {
        Schema::table('lpks', function (Blueprint $table) {
            $table->dropColumn([
                'perlakuan_terhadap_part',
                'frekuensi_claim',
                'perlakuan_part_defect',
                'lokasi_penemuan_claim',
                'status_repair',
            ]);
        });
    }
};
