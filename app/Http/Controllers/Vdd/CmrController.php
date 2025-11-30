<?php

namespace App\Http\Controllers\Vdd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cmr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
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
                case 'waiting_vdd':
                    if (Schema::hasColumn('cmrs', 'vdd_status')) {
                        $query->where('vdd_status', 'pending');
                    }
                    break;
                case 'rejected_vdd':
                    if (Schema::hasColumn('cmrs', 'vdd_status')) {
                        $query->where('vdd_status', 'rejected');
                    }
                    break;
                // keep other commonly used filters
                case 'waiting_ppc':
                    if (Schema::hasColumn('cmrs', 'ppchead_status')) {
                        $query->where('ppchead_status', 'pending');
                    }
                    break;
                case 'waiting_procurement':
                    if (Schema::hasColumn('cmrs', 'procurement_status')) {
                        $query->where('procurement_status', 'pending');
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
                              ->where('vdd_status', 'approved')
                              ->where(function($q) {
                                  $q->where('procurement_status', 'approved')
                                    ->orWhereNull('procurement_status')
                                    ->orWhere('procurement_status', '');
                              });
                    }
                    break;
            }
        }

        $cmrs = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('vdd.cmr.index', compact('cmrs'));
    }

    public function approve(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        if (Auth::user()->role !== 'vdd') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Hanya VDD yang dapat melakukan approval ini.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya VDD yang dapat melakukan approval ini.');
        }

        if (Schema::hasColumn('cmrs', 'vdd_status')) {
            $cmr->vdd_status = 'approved';
        }
        if (Schema::hasColumn('cmrs', 'vdd_approver_id')) {
            $cmr->vdd_approver_id = auth()->id();
        }
        if (Schema::hasColumn('cmrs', 'vdd_approved_at')) {
            $cmr->vdd_approved_at = now();
        }

        // For new flow, set procurement to pending so procurement can act next
        if (Schema::hasColumn('cmrs', 'procurement_status')) {
            $cmr->procurement_status = 'pending';
            if (Schema::hasColumn('cmrs', 'procurement_approver_id')) {
                $cmr->procurement_approver_id = null;
            }
            if (Schema::hasColumn('cmrs', 'procurement_approved_at')) {
                $cmr->procurement_approved_at = null;
            }
        }

        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Waiting for Procurement approval';
        }

        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'VDD', 'approved', null, $actorName);
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR approved by VDD and forwarded to Procurement.',
                'new_status' => 'Waiting for Procurement approval',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('vdd.cmr.index')->with('status', 'CMR approved by VDD and forwarded to Procurement.');
    }

    public function reject(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        if (Auth::user()->role !== 'vdd') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Hanya VDD yang dapat melakukan approval ini.'], 403);
            }
            return redirect()->back()->with('error', 'Hanya VDD yang dapat melakukan approval ini.');
        }

        if (Schema::hasColumn('cmrs', 'vdd_status')) {
            $cmr->vdd_status = 'rejected';
        }
        if (Schema::hasColumn('cmrs', 'vdd_approver_id')) {
            $cmr->vdd_approver_id = auth()->id();
        }
        if (Schema::hasColumn('cmrs', 'vdd_approved_at')) {
            $cmr->vdd_approved_at = now();
        }
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Rejected by VDD';
        }

        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'VDD', 'rejected', null, $actorName);
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR rejected by VDD.',
                'new_status' => 'Rejected by VDD',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('vdd.cmr.index')->with('status', 'CMR rejected by VDD.');
    }

    public function previewFpdf($id)
    {
        $qcController = new \App\Http\Controllers\QC\CmrController();
        return $qcController->previewFpdf($id);
    }
}
