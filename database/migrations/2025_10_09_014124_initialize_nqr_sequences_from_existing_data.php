<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Nqr;
use App\Models\NqrSequence;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ambil semua tahun yang ada di data NQR
        $years = DB::table('nqrs')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year')
            ->pluck('year');

        foreach ($years as $year) {
            // Hitung jumlah NQR per tahun untuk mendapatkan nomor urut terakhir
            $count = DB::table('nqrs')
                ->whereYear('created_at', $year)
                ->count();

            // Buat atau update sequence untuk tahun tersebut
            NqrSequence::updateOrCreate(
                ['year' => $year],
                ['current' => $count]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus semua data sequence
        DB::table('nqr_sequences')->truncate();
    }
};
