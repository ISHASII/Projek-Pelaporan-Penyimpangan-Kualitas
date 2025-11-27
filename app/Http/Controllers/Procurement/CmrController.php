<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cmr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\CmrStatusChanged;

class CmrController extends Controller
{
    public function index()
    {
        $q = request()->query('q');
        $date = request()->query('date');
        $year = request()->query('year');

        // Procurement sees all requested CMRs (QC requested)
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
                $query->whereDate('tgl_terbit_cmr', $iso);
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

        if (!empty(request()->query('approval_status'))) {
            $approval_status = request()->query('approval_status');
            switch ($approval_status) {
                case 'pending_request':
                    $query->whereNull('requested_at_qc');
                    break;
                case 'waiting_sect':
                    $query->where('secthead_status', 'pending');
                    break;
                case 'waiting_dept':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'pending');
                    break;
                case 'waiting_agm':
                    if (Schema::hasColumn('cmrs', 'agm_status')) {
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'pending');
                    }
                    break;
                case 'waiting_ppc':
                    if (Schema::hasColumn('cmrs', 'ppchead_status')) {
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'approved')
                              ->where('ppchead_status', 'pending');
                    }
                    break;
                case 'waiting_procurement':
                    if (Schema::hasColumn('cmrs', 'procurement_status')) {
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'approved')
                              ->where('ppchead_status', 'approved')
                              ->where('procurement_status', 'pending');
                    }
                    break;
                case 'rejected_sect':
                    $query->where('secthead_status', 'rejected');
                    break;
                case 'rejected_dept':
                    $query->where('depthead_status', 'rejected');
                    break;
                case 'rejected_agm':
                    if (Schema::hasColumn('cmrs', 'agm_status')) {
                        $query->where('agm_status', 'rejected');
                    }
                    break;
                case 'rejected_ppc':
                    if (Schema::hasColumn('cmrs', 'ppchead_status')) {
                        $query->where('ppchead_status', 'rejected');
                    }
                    break;
                case 'rejected_procurement':
                    if (Schema::hasColumn('cmrs', 'procurement_status')) {
                        $query->where('procurement_status', 'rejected');
                    }
                    break;
                case 'completed':
                    if (Schema::hasColumn('cmrs', 'procurement_status')) {
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'approved')
                              ->where('ppchead_status', 'approved')
                              ->where(function($q) {
                                  $q->where('procurement_status', 'approved')
                                    ->orWhereNull('procurement_status')
                                    ->orWhere('procurement_status', '');
                              });
                    } else {
                        $query->where('status_approval', 'Completed');
                    }
                    break;
            }
        }

        // years for dropdown
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

        // Use a dedicated Procurement view (wraps same layout) so we can customize later
        return view('procurement.cmr.index', compact('cmrs', 'years'));
    }

    public function approve(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        // require PPC already approved
        if (($cmr->ppchead_status ?? '') !== 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot approve before PPC Head approval.'], 400);
            }
            return redirect()->route('procurement.cmr.index')->with('status', 'Cannot approve before PPC Head approval.');
        }

        $current = strtolower($cmr->procurement_status ?? 'pending');
        if ($current === 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already approved by Procurement.'], 400);
            }
            return redirect()->route('procurement.cmr.index')->with('status', 'CMR already approved by Procurement.');
        }
        if ($current === 'rejected') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already rejected by Procurement; cannot approve.'], 400);
            }
            return redirect()->route('procurement.cmr.index')->with('status', 'CMR already rejected by Procurement; cannot approve.');
        }

        if (Schema::hasColumn('cmrs', 'procurement_status')) {
            $cmr->procurement_status = 'approved';
        }
        if (Schema::hasColumn('cmrs', 'procurement_approver_id')) {
            $cmr->procurement_approver_id = auth()->id();
        }
        if (Schema::hasColumn('cmrs', 'procurement_approved_at')) {
            $cmr->procurement_approved_at = now();
        }
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = \App\Models\Cmr::STATUS_COMPLETED;
        }

        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'Procurement', 'approved', null, $actorName);
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR approved by Procurement.',
                'new_status' => 'Completed',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('procurement.cmr.index')->with('status', 'CMR approved by Procurement.');
    }

    public function reject(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        if (($cmr->ppchead_status ?? '') !== 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot reject before PPC Head approval.'], 400);
            }
            return redirect()->route('procurement.cmr.index')->with('status', 'Cannot reject before PPC Head approval.');
        }

        if (Schema::hasColumn('cmrs', 'procurement_status')) {
            $cmr->procurement_status = 'rejected';
        }
        if (Schema::hasColumn('cmrs', 'procurement_approver_id')) {
            $cmr->procurement_approver_id = auth()->id();
        }
        if (Schema::hasColumn('cmrs', 'procurement_approved_at')) {
            $cmr->procurement_approved_at = now();
        }
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = \App\Models\Cmr::STATUS_REJECTED_BY_PROCUREMENT;
        }

        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'Procurement', 'rejected', null, $actorName);
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR rejected by Procurement.',
                'new_status' => 'Rejected by Procurement',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('procurement.cmr.index')->with('status', 'CMR rejected by Procurement.');
    }

    public function previewFpdf($id)
    {
        $cmr = Cmr::findOrFail($id);

        // Use the QC CmrController's previewFpdf method
        $qcController = new \App\Http\Controllers\QC\CmrController();
        return $qcController->previewFpdf($id);
    }
}
