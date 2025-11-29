<?php

namespace App\Http\Controllers\Foreman;

use App\Http\Controllers\Controller;
use App\Models\Nqr;
use Illuminate\Http\Request;

class NqrController extends Controller
{
    /**
     * Index for Foreman: show NQRs relevant to Foreman approval
     */
    public function index(Request $request)
    {
        $query = Nqr::whereIn('status_approval', [
            'Menunggu Approval Foreman',
            'Menunggu Approval Sect Head',
            'Menunggu Approval Dept Head',
            'Menunggu Approval PPC Head',
            'Menunggu Approval VDD',
            'Menunggu Approval Procurement',
            'Ditolak Foreman',
            'Ditolak Sect Head',
            'Ditolak Dept Head',
            'Ditolak PPC Head',
            'Ditolak VDD',
            'Ditolak Procurement',
            'Selesai'
        ]);

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('no_reg_nqr', 'like', '%' . $request->q . '%')
                  ->orWhere('nama_supplier', 'like', '%' . $request->q . '%')
                  ->orWhere('nama_part', 'like', '%' . $request->q . '%')
                  ->orWhere('nomor_part', 'like', '%' . $request->q . '%');
            });
        }

        $dateParam = null;
        if ($request->filled('date')) {
            $dateParam = $request->date;
        } elseif ($request->filled('date_display')) {
            $dateParam = $request->date_display;
        }

        if (!empty($dateParam)) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dateParam))->format('Y-m-d');
                $query->whereDate('tgl_terbit_nqr', $date);
            } catch (\Exception $e) {
                try {
                    $date = \Carbon\Carbon::parse(trim($dateParam))->format('Y-m-d');
                    $query->whereDate('tgl_terbit_nqr', $date);
                } catch (\Exception $e) {
                }
            }
        }

        if ($request->filled('year')) {
            $query->whereYear('tgl_terbit_nqr', $request->year);
        }

        if ($request->filled('status_nqr')) {
            $query->where('status_nqr', $request->status_nqr);
        }

        if ($request->filled('approval_status')) {
            $approval = $request->approval_status;
            $mapping = [
                'menunggu_request' => 'Menunggu Request dikirimkan',
                'menunggu_foreman' => 'Menunggu Approval Foreman',
                'menunggu_sect' => 'Menunggu Approval Sect Head',
                'menunggu_dept' => 'Menunggu Approval Dept Head',
                'menunggu_ppc' => 'Menunggu Approval PPC Head',
                'menunggu_vdd' => 'Menunggu Approval VDD',
                'menunggu_procurement' => 'Menunggu Approval Procurement',
                'ditolak_foreman' => 'Ditolak Foreman',
                'ditolak_sect' => 'Ditolak Sect Head',
                'ditolak_dept' => 'Ditolak Dept Head',
                'ditolak_ppc' => 'Ditolak PPC Head',
                'ditolak_vdd' => 'Ditolak VDD',
                'ditolak_procurement' => 'Ditolak Procurement',
                'selesai' => 'Selesai',
            ];

            if (isset($mapping[$approval])) {
                $approval = $mapping[$approval];
            }

            $query->where('status_approval', $approval);
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('foreman.nqr.index', compact('nqrs'));
    }
}
