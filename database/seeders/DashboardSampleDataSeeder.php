<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lpk;
use App\Models\Nqr;
use App\Models\Cmr;
use App\Models\User;
use Carbon\Carbon;

class DashboardSampleDataSeeder extends Seeder
{
    public function run()
    {
        // Buat beberapa user sample jika belum ada
        $qcUser = User::firstOrCreate(
            ['email' => 'qc@example.com'],
            [
                'name' => 'QC User',
                'username' => 'qc_user',
                'role' => 'qc',
                'npk' => 'QC001',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        $sectUser = User::firstOrCreate(
            ['email' => 'secthead@example.com'],
            [
                'name' => 'Section Head',
                'username' => 'sect_head',
                'role' => 'secthead',
                'npk' => 'SH001',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        $deptUser = User::firstOrCreate(
            ['email' => 'depthead@example.com'],
            [
                'name' => 'Department Head',
                'username' => 'dept_head',
                'role' => 'depthead',
                'npk' => 'DH001',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        $ppcUser = User::firstOrCreate(
            ['email' => 'ppchead@example.com'],
            [
                'name' => 'PPC Head',
                'username' => 'ppc_head',
                'role' => 'ppchead',
                'npk' => 'PPC001',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Sample LPK data
        for ($i = 0; $i < 20; $i++) {
            $randomDate = Carbon::now()->subDays(rand(1, 180));
            $status = $this->getRandomLpkStatus();

            Lpk::create([
                'no_reg' => 'LPK' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '/2025',
                'tgl_terbit' => $randomDate,
                'tgl_delivery' => $randomDate->copy()->addDays(rand(1, 30)),
                'nama_supply' => 'Supplier ' . chr(65 + ($i % 26)),
                'nama_part' => 'Part Name ' . ($i + 1),
                'nomor_part' => 'PN-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'nomor_po' => 'PO-' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'status' => 'open',
                'total_check' => rand(100, 1000),
                'total_ng' => rand(1, 50),
                'total_delivery' => rand(500, 2000),
                'total_claim' => rand(1, 100),
                'kategori' => ['A', 'B', 'C'][rand(0, 2)],
                'problem' => 'Sample problem description ' . ($i + 1),
                'lokasi_penemuan_claim' => ['Receiving', 'In-Process', 'Customer PT'][rand(0, 2)],
                'secthead_status' => $status['secthead'],
                'depthead_status' => $status['depthead'],
                'ppchead_status' => $status['ppchead'],
                'secthead_approver_id' => $status['secthead'] ? $sectUser->id : null,
                'depthead_approver_id' => $status['depthead'] ? $deptUser->id : null,
                'ppchead_approver_id' => $status['ppchead'] ? $ppcUser->id : null,
                'secthead_approved_at' => $status['secthead'] ? $randomDate->copy()->addHours(rand(1, 24)) : null,
                'depthead_approved_at' => $status['depthead'] ? $randomDate->copy()->addHours(rand(25, 48)) : null,
                'ppchead_approved_at' => $status['ppchead'] ? $randomDate->copy()->addHours(rand(49, 72)) : null,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }

        // Sample NQR data
        for ($i = 0; $i < 15; $i++) {
            $randomDate = Carbon::now()->subDays(rand(1, 180));
            $status = $this->getRandomNqrStatus();

            Nqr::create([
                'no_reg_nqr' => 'NQR' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '/2025',
                'tgl_terbit_nqr' => $randomDate,
                'tgl_delivery' => $randomDate->copy()->addDays(rand(1, 30)),
                'nama_supplier' => 'NQR Supplier ' . chr(65 + ($i % 26)),
                'nama_part' => 'NQR Part ' . ($i + 1),
                'nomor_po' => 'NQR-PO-' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'nomor_part' => 'NQR-PN-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'status_nqr' => 'open',
                'location_claim_occur' => ['Receiving', 'Production', 'Customer'][rand(0, 2)],
                'total_del' => rand(500, 2000),
                'total_claim' => rand(1, 100),
                'status_approval' => $status,
                'created_by' => $qcUser->id,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }

        // Sample CMR data
        for ($i = 0; $i < 12; $i++) {
            $randomDate = Carbon::now()->subDays(rand(1, 180));
            $status = $this->getRandomCmrStatus();

            Cmr::create([
                'no_reg' => 'CMR' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '/2025',
                'nama' => 'CMR Document ' . ($i + 1),
                'deskripsi' => 'CMR description for document ' . ($i + 1),
                'tgl_terbit_cmr' => $randomDate,
                'tgl_delivery' => $randomDate->copy()->addDays(rand(1, 30)),
                'nama_supplier' => 'CMR Supplier ' . chr(65 + ($i % 26)),
                'nama_part' => 'CMR Part ' . ($i + 1),
                'nomor_part' => 'CMR-PN-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'order_no' => 'CMR-ORDER-' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'location_claim_occur' => ['Receiving', 'Production Line', 'Final Inspection'][rand(0, 2)],
                'total_del' => rand(500, 2000),
                'total_claim' => rand(1, 100),
                'qty_deliv' => rand(500, 1500),
                'qty_order' => rand(600, 1600),
                'qty_problem' => rand(1, 50),
                'status_approval' => $status,
                'created_by' => $qcUser->id,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }
    }

    private function getRandomLpkStatus()
    {
        $rand = rand(1, 100);

        if ($rand <= 60) {
            // 60% fully approved
            return [
                'secthead' => 'approved',
                'depthead' => 'approved',
                'ppchead' => 'approved'
            ];
        } elseif ($rand <= 80) {
            // 20% pending somewhere in chain
            $stages = ['secthead', 'depthead', 'ppchead'];
            $pendingStage = rand(0, 2);
            return [
                'secthead' => $pendingStage >= 0 ? ($pendingStage > 0 ? 'approved' : 'pending') : null,
                'depthead' => $pendingStage >= 1 ? ($pendingStage > 1 ? 'approved' : 'pending') : null,
                'ppchead' => $pendingStage >= 2 ? 'pending' : null,
            ];
        } else {
            // 20% rejected
            $rejectStage = rand(0, 2);
            $stages = ['secthead', 'depthead', 'ppchead'];
            $result = ['secthead' => null, 'depthead' => null, 'ppchead' => null];
            $result[$stages[$rejectStage]] = 'rejected';
            return $result;
        }
    }

    private function getRandomNqrStatus()
    {
        $statuses = ['Completed', 'Rejected by Procurement', null, null, null]; // 20% completed, 20% rejected, 60% pending
        return $statuses[rand(0, 4)];
    }

    private function getRandomCmrStatus()
    {
        $statuses = [
            Cmr::STATUS_COMPLETED,
            Cmr::STATUS_REJECTED_BY_PROCUREMENT,
            null, null, null // 20% completed, 20% rejected, 60% pending
        ];
        return $statuses[rand(0, 4)];
    }
}
