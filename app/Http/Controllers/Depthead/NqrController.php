<?php

namespace App\Http\Controllers\Depthead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Nqr;
use App\Models\User;

class NqrController extends Controller
{
    /**
     * Preview NQR PDF (FPDF/FPDI) untuk Dept Head (reuse logic QC)
     */
    public function previewFpdf($id)
    {
        $qcController = app(\App\Http\Controllers\QC\NqrController::class);
        return $qcController->previewFpdf($id);
    }

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

        // Fetch PPC Head approvers from lembur database (dept=PPC, golongan=4, acting=1)
        $ppcApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'PPC')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $localUser = User::where('npk', $ext->npk)->first();
                $ppcApprovers->push((object)[
                    'id' => $localUser ? $localUser->id : null,
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                    'golongan' => $ext->golongan,
                    'acting' => $ext->acting,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with ppchead role
            $ppcApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%ppc%'])->get()->map(function ($u) {
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

        return view('depthead.nqr.index', compact('nqrs', 'ppcApprovers'));
    }

    public function create() { return view('depthead.nqr.create'); }
    public function store(Request $r) { return redirect()->route('depthead.nqr.index')->with('status','NQR created'); }
    public function show($id) { return view('depthead.nqr.show', compact('id')); }
    public function edit($id) { return view('depthead.nqr.edit', compact('id')); }
    public function update(Request $r,$id) { return redirect()->route('depthead.nqr.show',$id)->with('status','NQR updated'); }
    public function destroy($id) { return redirect()->route('depthead.nqr.index')->with('status','NQR deleted'); }
}
