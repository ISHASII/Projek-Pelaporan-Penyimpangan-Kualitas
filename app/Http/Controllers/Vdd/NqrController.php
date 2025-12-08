<?php

namespace App\Http\Controllers\Vdd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Nqr;
use App\Models\User;

class NqrController extends Controller
{
    /**
     * Display a listing of NQR for VDD to review
     */
    public function index(Request $request)
    {
        $query = Nqr::with(['creator', 'updater']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('no_reg_nqr', 'like', "%{$search}%")
                  ->orWhere('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('nama_part', 'like', "%{$search}%")
                  ->orWhere('nomor_po', 'like', "%{$search}%")
                  ->orWhere('nomor_part', 'like', "%{$search}%");
            });
        }

        if ($request->filled('year')) {
            $query->whereYear('tgl_terbit_nqr', $request->year);
        }

        if ($request->filled('status_nqr')) {
            $query->where('status_nqr', $request->status_nqr);
        }

        if ($request->filled('approval_status')) {
            $status = $request->approval_status;
            $statusMap = [
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
            if (isset($statusMap[$status])) {
                $query->where('status_approval', $statusMap[$status]);
            }
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Fetch Procurement approvers from lembur database (dept=PROCUREMENT, golongan=4, acting=1)
        $procurementApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'PROCUREMENT')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $localUser = User::where('npk', $ext->npk)->first();
                $procurementApprovers->push((object)[
                    'id' => $localUser ? $localUser->id : null,
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                    'golongan' => $ext->golongan,
                    'acting' => $ext->acting,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with procurement role
            $procurementApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%procurement%'])->get()->map(function ($u) {
                return (object)[
                    'id' => $u->id,
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                    'golongan' => null,
                    'acting' => null,
                ];
            });
        }

        return view('vdd.nqr.index', compact('nqrs', 'procurementApprovers'));
    }

    public function showInputPayCompensation($id)
    {
        $nqr = Nqr::findOrFail($id);
        // Set form action to VDD approve route (which will accept PPC fields)
        $formAction = route('vdd.nqr.approve', $nqr->id);
        $backRoute = route('vdd.nqr.index');
        $previewRoute = route('vdd.nqr.previewFpdf', $nqr->id);
        $roleLabel = 'VDD';

        // Fetch Procurement approvers from lembur database
        $procurementApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'PROCUREMENT')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $localUser = User::where('npk', $ext->npk)->first();
                $procurementApprovers->push((object)[
                    'id' => $localUser ? $localUser->id : null,
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                ]);
            }
        } catch (\Throwable $e) {
            $procurementApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%procurement%'])->get()->map(function ($u) {
                return (object)[
                    'id' => $u->id,
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                ];
            });
        }

        return view('vdd.nqr.input_pay_compensation', compact('nqr', 'formAction', 'backRoute', 'previewRoute', 'roleLabel', 'procurementApprovers'));
    }
}
