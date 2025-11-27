<?php

namespace Database\Factories;

use App\Models\Lpk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lpk>
 */
class LpkFactory extends Factory
{
    protected $model = Lpk::class;

    public function definition(): array
    {
        $tgl = $this->faker->dateTimeBetween('-2 years', 'now');
        $tgl_delivery = (clone $tgl)->modify('+'.rand(0,10).' days');

        $statuses = ['pending', 'approved', 'rejected'];
        $repairStatuses = ['repaired', 'not_repaired', 'on_hold'];
        $perlakuanOptions = ['Direpair Supplier', 'Replace', 'Dikembalikan ke Supplier', 'Discrap di PT KYBI'];
        $frekuensiOptions = ['sekali', 'berkala', 'sering', 'jarang'];
        $lokasiOptions = ['line produksi', 'gudang', 'pemasangan', 'penerimaan'];

        $total_delivery = $this->faker->numberBetween(1, 1000);
        $total_ng = $this->faker->numberBetween(0, min(50, $total_delivery));
        $total_check = $total_delivery + $this->faker->numberBetween(0, 50);
        $total_claim = $this->faker->numberBetween(0, $total_delivery);
        $percentage = $total_delivery ? round(($total_ng / $total_delivery) * 100, 2) : 0;

        return [
            'no_reg' => strtoupper($this->faker->bothify('LPK-####')),
            'tgl_terbit' => $tgl->format('Y-m-d'),
            'tgl_delivery' => $tgl_delivery->format('Y-m-d'),
            'nama_supply' => $this->faker->company(),
            'nama_part' => $this->faker->word() . ' ' . $this->faker->bothify('??'),
            'nomor_po' => $this->faker->bothify('PO-#####'),
            'status' => $this->faker->randomElement(['open','closed','in_progress']),
            'jenis_ng' => $this->faker->randomElement(['visual','dimensional','functional']),
            'kategori' => $this->faker->randomElement(['Qty Kurang','Subcont Prod','Part Repair','Reject Process','Salah Barang/Label']),
            'gambar' => null,
            'problem' => $this->faker->optional(0.6)->sentence(6),
            'total_check' => $total_check,
            'total_ng' => $total_ng,
            'total_delivery' => $total_delivery,
            'total_claim' => $total_claim,
            'percentage' => $percentage,
            // new dropdown fields
            'perlakuan_terhadap_part' => $this->faker->randomElement(['Sortir Oleh Customer','Sortir Oleh Supplier','Sortir PT KYBI','Part Tetap Dipakai']),
            'frekuensi_claim' => $this->faker->randomElement($frekuensiOptions),
            'perlakuan_part_defect' => $this->faker->randomElement($perlakuanOptions),
            'lokasi_penemuan_claim' => $this->faker->randomElement($lokasiOptions),
            'status_repair' => $this->faker->randomElement($repairStatuses),
            // PPC Head inputs (may be null for some records)
            'ppc_perlakuan_terhadap_part' => $this->faker->optional(0.6)->randomElement($perlakuanOptions),
            'ppc_perlakuan_terhadap_claim' => $this->faker->optional(0.6)->randomElement($perlakuanOptions),
            // approval fields - randomly set some approvals
            'secthead_status' => $this->faker->optional(0.7)->randomElement($statuses),
            'secthead_note' => $this->faker->optional(0.5)->sentence(),
            'secthead_approver_id' => null,
            'secthead_approved_at' => $this->faker->optional(0.7)->dateTimeBetween($tgl, 'now'),

            'depthead_status' => $this->faker->optional(0.6)->randomElement($statuses),
            'depthead_note' => $this->faker->optional(0.4)->sentence(),
            'depthead_approver_id' => null,
            'depthead_approved_at' => $this->faker->optional(0.6)->dateTimeBetween($tgl, 'now'),

            'ppchead_status' => $this->faker->optional(0.5)->randomElement($statuses),
            'ppchead_note' => $this->faker->optional(0.3)->sentence(),
            'ppchead_approver_id' => null,
            'ppchead_approved_at' => $this->faker->optional(0.5)->dateTimeBetween($tgl, 'now'),
        ];
    }
}
