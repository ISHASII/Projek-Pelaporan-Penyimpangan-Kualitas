<?php

namespace App\Http\Controllers\Secthead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cmr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\CmrStatusChanged;
use Illuminate\Support\Facades\Schema;

class CmrController extends Controller
{
    public function index()
    {
        $q = request()->query('q');
        $date = request()->query('date');
        $year = request()->query('year');
        $approval_status = request()->query('approval_status');

        $query = Cmr::whereNotNull('requested_at_qc');

        if (!empty($q)) {
            $query->where(function($sub) use ($q) {
                $sub->where('no_reg', 'like', "%{$q}%")
                    ->orWhere('nama_supplier', 'like', "%{$q}%")
                    ->orWhere('nama_part', 'like', "%{$q}%")
                    ->orWhere('nomor_part', 'like', "%{$q}%")
                    ->orWhere('order_no', 'like', "%{$q}%")
                    ->orWhere('invoice_no', 'like', "%{$q}%");
            });
        }

        if (!empty($date)) {
            try {
                if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $date)) {
                    $iso = \Carbon\Carbon::createFromFormat('d-m-Y', $date)->toDateString();
                } else {
                    $iso = \Carbon\Carbon::parse($date)->toDateString();
                }

                $query->where(function($sub) use ($iso) {
                    $sub->whereDate('tgl_terbit_cmr', $iso);
                    if (Schema::hasColumn('cmrs', 'tgl_terbit_nqr')) {
                        $sub->orWhereDate('tgl_terbit_nqr', $iso);
                    }
                });
            } catch (\Exception $e) {
                $query->whereDate('tgl_terbit_cmr', $date);
            }
        }

        if (!empty($year)) {
            $query->whereYear('tgl_terbit_cmr', intval($year));
        }

        $product = request()->query('product');
        if (!empty($product)) {
            $query->where('product', $product);
        }

        if (!empty($approval_status)) {
            switch ($approval_status) {
                // Rejected by Sect Head
                case 'rejected_sect':
                case 'ditolak_sect':
                    $query->where('secthead_status', 'rejected');
                    break;

                // Rejected by Dept Head
                case 'rejected_dept':
                case 'ditolak_dept':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', 'rejected');
                    break;

                // Rejected by PPC Head
                case 'rejected_ppc':
                case 'ditolak_ppc':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', 'rejected');
                    break;

                // Waiting for Sect Head
                case 'waiting_sect':
                case 'menunggu_sect':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'pending');
                    break;

                // Waiting for Dept Head
                case 'waiting_dept':
                case 'menunggu_dept':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'approved')
                          ->where('depthead_status', 'pending');
                    break;

                // Waiting for PPC Head
                case 'waiting_ppc':
                case 'menunggu_ppc':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('ppchead_status', 'pending');
                    break;

                // Waiting for AGM
                case 'waiting_agm':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('agm_status', 'pending');
                    break;

                // Rejected by AGM
                case 'rejected_agm':
                    $query->where('agm_status', 'rejected');
                    break;

                // Waiting for Procurement
                case 'waiting_procurement':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('agm_status', 'approved')
                          ->where('ppchead_status', 'approved')
                          ->where('procurement_status', 'pending');
                    break;
                case 'waiting_vdd':
                case 'menunggu_vdd':
                    if (Schema::hasColumn('cmrs', 'vdd_status')) {
                        $query->where('ppchead_status', 'approved')
                              ->where('vdd_status', 'pending');
                    }
                    break;

                // Rejected by Procurement
                case 'rejected_procurement':
                    $query->where('procurement_status', 'rejected');
                    break;
                case 'rejected_vdd':
                    if (Schema::hasColumn('cmrs', 'vdd_status')) {
                        $query->where('vdd_status', 'rejected');
                    }
                    break;

                // Pending Request
                case 'pending_request':
                case 'menunggu_request':
                    $query->whereNull('requested_at_qc');
                    break;

                // Completed
                case 'completed':
                case 'selesai':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('agm_status', 'approved')
                          ->where('ppchead_status', 'approved')
                          ->where(function($q) {
                              $q->where('procurement_status', 'approved')
                                ->orWhereNull('procurement_status')
                                ->orWhere('procurement_status', '');
                          });
                    break;
            }
        }

        // years for dropdown (based on tgl_terbit_cmr)
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $yearsQuery = Cmr::whereNotNull('tgl_terbit_cmr')->selectRaw("strftime('%Y', tgl_terbit_cmr) as year");
        } elseif ($driver === 'mysql') {
            $yearsQuery = Cmr::whereNotNull('tgl_terbit_cmr')->selectRaw('YEAR(tgl_terbit_cmr) as year');
        } elseif ($driver === 'pgsql') {
            $yearsQuery = Cmr::whereNotNull('tgl_terbit_cmr')->selectRaw('EXTRACT(YEAR FROM tgl_terbit_cmr) as year');
        } else {
            $yearsQuery = Cmr::whereNotNull('tgl_terbit_cmr')->selectRaw('YEAR(tgl_terbit_cmr) as year');
        }

        $years = $yearsQuery->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->filter()
                    ->map(function($y){ return (int)$y; })
                    ->values();

        $cmrs = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('secthead.cmr.index', compact('cmrs', 'years'));
    }

    public function approve(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);
        $current = strtolower($cmr->secthead_status ?? 'pending');
        if ($current === 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already approved by Sect Head.'], 400);
            }
            return redirect()->route('secthead.cmr.index')->with('status', 'CMR already approved by Sect Head.');
        }
        if ($current === 'rejected') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already rejected by Sect Head; cannot approve.'], 400);
            }
            return redirect()->route('secthead.cmr.index')->with('status', 'CMR already rejected by Sect Head; cannot approve.');
        }

        $cmr->secthead_status = 'approved';
        $cmr->secthead_note = $request->input('note');
        $cmr->secthead_approver_id = auth()->id();
        $cmr->secthead_approved_at = now();
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Waiting for Dept Head approval';
        }
        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'Sect Head', 'approved', $cmr->secthead_note, $actorName);
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR approved by Sect Head.',
                'new_status' => 'Waiting for Dept Head approval',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('secthead.cmr.index')->with('status', 'CMR approved.');
    }

    public function reject(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);
        $current = strtolower($cmr->secthead_status ?? 'pending');
        if ($current === 'rejected') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already rejected by Sect Head.'], 400);
            }
            return redirect()->route('secthead.cmr.index')->with('status', 'CMR already rejected by Sect Head.');
        }
        if ($current === 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already approved by Sect Head; cannot reject.'], 400);
            }
            return redirect()->route('secthead.cmr.index')->with('status', 'CMR already approved by Sect Head; cannot reject.');
        }

        $cmr->secthead_status = 'rejected';
        $cmr->secthead_note = $request->input('note');
        $cmr->secthead_approver_id = auth()->id();
        $cmr->secthead_approved_at = now();
        // set human-readable approval status
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Ditolak Sect Head';
        }
        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'Sect Head', 'rejected', $cmr->secthead_note, $actorName);
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR rejected by Sect Head.',
                'new_status' => 'Rejected by Sect Head',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('secthead.cmr.index')->with('status', 'CMR rejected.');
    }

    // keep other resource methods as stubs or default views if needed
    public function create()
    {
        return view('secthead.cmr.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('secthead.cmr.index')->with('status', 'CMR created.');
    }

    public function show($id)
    {
        return view('secthead.cmr.show', compact('id'));
    }

    public function edit($id)
    {
        return view('secthead.cmr.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('secthead.cmr.show', $id)->with('status', 'CMR updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('secthead.cmr.index')->with('status', 'CMR deleted.');
    }

    public function previewFpdf($id)
    {
        $qcController = new \App\Http\Controllers\QC\CmrController();
        return $qcController->previewFpdf($id);
    }
}
