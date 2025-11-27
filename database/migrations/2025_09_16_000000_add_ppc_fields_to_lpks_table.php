<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('lpks')) {
            Schema::table('lpks', function (Blueprint $table) {
                if (! Schema::hasColumn('lpks', 'ppc_perlakuan_terhadap_part')) {
                    $table->string('ppc_perlakuan_terhadap_part')->nullable()->after('ppchead_approved_at');
                }
                if (! Schema::hasColumn('lpks', 'ppc_perlakuan_terhadap_claim')) {
                    $table->string('ppc_perlakuan_terhadap_claim')->nullable()->after('ppc_perlakuan_terhadap_part');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('lpks')) {
            Schema::table('lpks', function (Blueprint $table) {
                if (Schema::hasColumn('lpks', 'ppc_perlakuan_terhadap_claim')) {
                    $table->dropColumn('ppc_perlakuan_terhadap_claim');
                }
                if (Schema::hasColumn('lpks', 'ppc_perlakuan_terhadap_part')) {
                    $table->dropColumn('ppc_perlakuan_terhadap_part');
                }
            });
        }
    }
};