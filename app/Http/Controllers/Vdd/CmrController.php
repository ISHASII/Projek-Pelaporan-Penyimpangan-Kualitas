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
                    } elseif (Schema::hasColumn('cmrs', 'status_approval')) {
                        $query->where('status_approval', 'like', '%VDD%')
                              ->where(function($sub) {
                                  $sub->where('status_approval', 'like', '%Waiting%')
                                      ->orWhere('status_approval', 'like', '%Menunggu%');
                              });
                    }
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
                case 'rejected_vdd':
                    if (Schema::hasColumn('cmrs', 'vdd_status')) {
                        $query->where('vdd_status', 'rejected');
                    } elseif (Schema::hasColumn('cmrs', 'status_approval')) {
                        $query->where('status_approval', 'like', '%VDD%')
                              ->where(function($sub) {
                                  $sub->where('status_approval', 'like', '%Rejected%')
                                      ->orWhere('status_approval', 'like', '%Ditolak%')
                                      ->orWhere('status_approval', 'like', '%Rejected by VDD%');
                              });
                    }
                    break;
                case 'rejected_sect':
                    $query->where('secthead_status', 'rejected');
                    break;
                case 'rejected_dept':
                    $query->where('depthead_status', 'rejected');
                    break;
                case 'rejected_agm':
                    $query->where('agm_status', 'rejected');
                    break;
                case 'rejected_ppc':
                    $query->where('ppchead_status', 'rejected');
                    break;
                // keep other commonly used filters
                case 'waiting_procurement':
                    if (Schema::hasColumn('cmrs', 'procurement_status')) {
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'approved')
                              ->where('ppchead_status', 'approved')
                              ->where('vdd_status', 'approved')
                              ->where('procurement_status', 'pending');
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

        // If user submitted PPC fields in the same request (from the input_compensation form), save them
        $hasPpcInput = ($request->filled('ppc_nominal') || $request->filled('ppc_currency') || $request->filled('ppc_shipping') || $request->filled('ppc_currency_symbol'));
        if ($hasPpcInput) {
            // Require PPC Head already approved to allow storing PPC
            if (($cmr->ppchead_status ?? '') !== 'approved') {
                // ignore storing ppc if PPC Head hasn't approved - maintain current behavior
            } else {
                $rules = [
                    'ppc_currency' => 'nullable|string|in:IDR,JPY,USD,MYR,VND,THB,KRW,INR,CNY,CUSTOM',
                    'ppc_nominal' => 'nullable|numeric|min:0.01',
                    'ppc_shipping' => 'nullable|string|in:AIR,SEA',
                    'ppc_shipping_detail' => 'nullable|string|max:255',
                ];
                if ($request->input('ppc_currency') === 'CUSTOM') {
                    $rules['ppc_currency_symbol'] = 'required|string|max:10';
                }
                $validated = $request->validate($rules);
                try {
                    $ppcData = [
                        'disposition' => 'pay_compensation',
                        'nominal' => $request->input('ppc_nominal'),
                        'currency' => $request->input('ppc_currency'),
                        'currency_symbol' => $request->input('ppc_currency_symbol'),
                        'shipping' => $request->input('ppc_shipping'),
                        'shipping_detail' => $request->input('ppc_shipping_detail'),
                        'filled_by' => auth()->user()->name ?? auth()->id(),
                        'filled_at' => now()->toDateTimeString(),
                    ];

                    if (Schema::hasColumn('cmrs', 'ppchead_note')) {
                        $existing = $cmr->ppchead_note;
                        $existingDecoded = null;
                        try { $existingDecoded = json_decode($existing, true); } catch (\Throwable $e) { $existingDecoded = null; }
                        if (is_array($existingDecoded)) {
                            $findValueByKey = function ($arr, $key) use (&$findValueByKey) {
                                if (!is_array($arr)) return null;
                                if (array_key_exists($key, $arr)) return $arr[$key];
                                foreach ($arr as $v) {
                                    if (is_array($v)) {
                                        $found = $findValueByKey($v, $key);
                                        if ($found !== null) return $found;
                                    }
                                }
                                return null;
                            };
                            $prevDisposition = null;
                            if (isset($existingDecoded['ppc']) && is_array($existingDecoded['ppc']) && isset($existingDecoded['ppc']['disposition'])) {
                                $prevDisposition = $existingDecoded['ppc']['disposition'];
                            } else {
                                $prevDisposition = $findValueByKey($existingDecoded, 'disposition');
                            }
                            if ($prevDisposition && $prevDisposition !== ($ppcData['disposition'] ?? null)) {
                                $ppcData['prev_disposition'] = $prevDisposition;
                            }
                            $ppcDataFiltered = array_filter($ppcData, function ($v) { return !($v === null || $v === ''); });
                            if (isset($existingDecoded['ppc']) && is_array($existingDecoded['ppc'])) {
                                $ppcMerged = array_merge($existingDecoded['ppc'], $ppcDataFiltered);
                            } else {
                                $ppcMerged = $ppcDataFiltered;
                            }
                            if ($prevDisposition && $prevDisposition !== ($ppcMerged['disposition'] ?? null)) {
                                $ppcMerged['prev_disposition'] = $prevDisposition;
                            }
                            $merged = $existingDecoded;
                            $merged['ppc'] = $ppcMerged;
                        } else {
                            $merged = ['ppc' => $ppcData];
                        }
                        $cmr->ppchead_note = json_encode($merged);
                    } else {
                        $cmr->depthead_note = ($cmr->depthead_note ?? '') . "\nPPC: " . json_encode($ppcData);
                    }
                    if (Schema::hasColumn('cmrs', 'ppc_currency')) {
                        $cmr->ppc_currency = $request->input('ppc_currency');
                    }
                    if (Schema::hasColumn('cmrs', 'ppc_currency_symbol')) {
                        $cmr->ppc_currency_symbol = $request->input('ppc_currency_symbol');
                    }
                    if (Schema::hasColumn('cmrs', 'ppc_shipping')) {
                        $cmr->ppc_shipping = $request->input('ppc_shipping');
                    }
                    if (Schema::hasColumn('cmrs', 'ppc_shipping_detail')) {
                        $cmr->ppc_shipping_detail = $request->input('ppc_shipping_detail');
                    }

                    $cmr->save();

                    // If PPC saved by VDD, do NOT mark procurement as approved here. Procurement should still approve.
                    // Maintain procurement status as pending and set an appropriate status message.
                    if (Schema::hasColumn('cmrs', 'procurement_status')) {
                        $cmr->procurement_status = 'pending';
                    }
                    if (Schema::hasColumn('cmrs', 'procurement_approver_id')) {
                        $cmr->procurement_approver_id = null;
                    }
                    if (Schema::hasColumn('cmrs', 'procurement_approved_at')) {
                        $cmr->procurement_approved_at = null;
                    }
                    if (Schema::hasColumn('cmrs', 'status_approval')) {
                        $cmr->status_approval = 'Waiting for Procurement approval';
                    }
                    $cmr->save();
                } catch (\Throwable $e) {
                    // swallow to avoid breaking approval flow; log if needed
                }
            }
        }

        // If UI calls this via AJAX, return a redirect URL for client-side to navigate to the compensation input page
        $inputCompRoute = route('vdd.cmr.inputCompensation', $cmr->id);
        $skipInput = $request->boolean('skip_input_compensation');

        if ($request->ajax() || $request->wantsJson()) {
            $response = [
                'success' => true,
                'message' => $skipInput ? 'CMR approved by VDD.' : 'CMR approved by VDD and forwarded to Procurement (pay compensation input).',
                'new_status' => 'Waiting for Procurement approval',
                'hide_actions' => true
            ];
            if (!$skipInput) {
                $response['redirect_to'] = $inputCompRoute;
            }
            return response()->json($response);
        }

        // For non-AJAX requests, either redirect VDD directly to the compensation input form or back to index
        if ($skipInput) {
            return redirect()->route('vdd.cmr.index')->with('status', 'CMR approved by VDD.');
        }
        return redirect()->route('vdd.cmr.inputCompensation', $cmr->id)->with('status', 'CMR approved by VDD. Please input Pay Compensation.');
    }

    /**
     * Show input form for VDD to set pay compensation (uses the same view as Procurement)
     */
    public function showInputCompensation($id)
    {
        $cmr = Cmr::findOrFail($id);
        // We'll reuse the Procurement view, but override the action/back/preview routes & title
        // Set form action to VDD approve route so input page can submit a single Approve request
        $formAction = route('vdd.cmr.approve', $cmr->id);
        $backRoute = route('vdd.cmr.index');
        $previewRoute = route('vdd.cmr.previewFpdf', $cmr->id);
        $roleLabel = 'VDD';
        return view('vdd.cmr.input_compensation', compact('cmr', 'formAction', 'backRoute', 'previewRoute', 'roleLabel'));
    }

    /**
     * Store pay_compensation set by VDD and approve the CMR (mirror Procurement behavior)
     */
    public function storeCompensation(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        // Require PPC already approved
        if (($cmr->ppchead_status ?? '') !== 'approved') {
            return redirect()->route('vdd.cmr.index')->with('status', 'Cannot set compensation before PPC Head approval.');
        }

        $rules = [
            'ppc_currency' => 'nullable|string|in:IDR,JPY,USD,MYR,VND,THB,KRW,INR,CNY,CUSTOM',
            'ppc_nominal' => 'nullable|numeric|min:0.01',
            'ppc_shipping' => 'nullable|string|in:AIR,SEA',
            'ppc_shipping_detail' => 'nullable|string|max:255',
        ];
        if ($request->input('ppc_currency') === 'CUSTOM') {
            $rules['ppc_currency_symbol'] = 'required|string|max:10';
        }

        $validated = $request->validate($rules);

        $ppcData = [
            'disposition' => 'pay_compensation',
            'nominal' => $request->input('ppc_nominal'),
            'currency' => $request->input('ppc_currency'),
            'currency_symbol' => $request->input('ppc_currency_symbol'),
            'shipping' => $request->input('ppc_shipping'),
            'shipping_detail' => $request->input('ppc_shipping_detail'),
            'filled_by' => auth()->user()->name ?? auth()->id(),
            'filled_at' => now()->toDateTimeString(),
        ];

        if (Schema::hasColumn('cmrs', 'ppchead_note')) {
            $existing = $cmr->ppchead_note;
            $existingDecoded = null;
            try { $existingDecoded = json_decode($existing, true); } catch (\Throwable $e) { $existingDecoded = null; }
            if (is_array($existingDecoded)) {
                // preserve previous PPC disposition if present so PDF can show both actions
                $findValueByKey = function ($arr, $key) use (&$findValueByKey) {
                    if (!is_array($arr)) return null;
                    if (array_key_exists($key, $arr)) return $arr[$key];
                    foreach ($arr as $v) {
                        if (is_array($v)) {
                            $found = $findValueByKey($v, $key);
                            if ($found !== null) return $found;
                        }
                    }
                    return null;
                };

                $prevDisposition = null;
                if (isset($existingDecoded['ppc']) && is_array($existingDecoded['ppc']) && isset($existingDecoded['ppc']['disposition'])) {
                    $prevDisposition = $existingDecoded['ppc']['disposition'];
                } else {
                    $prevDisposition = $findValueByKey($existingDecoded, 'disposition');
                }

                if ($prevDisposition && $prevDisposition !== ($ppcData['disposition'] ?? null)) {
                    $ppcData['prev_disposition'] = $prevDisposition;
                }

                $ppcDataFiltered = array_filter($ppcData, function ($v) {
                    return !($v === null || $v === '');
                });

                if (isset($existingDecoded['ppc']) && is_array($existingDecoded['ppc'])) {
                    $ppcMerged = array_merge($existingDecoded['ppc'], $ppcDataFiltered);
                } else {
                    $ppcMerged = $ppcDataFiltered;
                }

                if ($prevDisposition && $prevDisposition !== ($ppcMerged['disposition'] ?? null)) {
                    $ppcMerged['prev_disposition'] = $prevDisposition;
                }

                $merged = $existingDecoded;
                $merged['ppc'] = $ppcMerged;
            } else {
                $merged = ['ppc' => $ppcData];
            }
            $cmr->ppchead_note = json_encode($merged);
        } else {
            $cmr->depthead_note = ($cmr->depthead_note ?? '') . "\nPPC: " . json_encode($ppcData);
        }

        if (Schema::hasColumn('cmrs', 'ppc_currency')) {
            $cmr->ppc_currency = $request->input('ppc_currency');
        }
        if (Schema::hasColumn('cmrs', 'ppc_currency_symbol')) {
            $cmr->ppc_currency_symbol = $request->input('ppc_currency_symbol');
        }
        if (Schema::hasColumn('cmrs', 'ppc_shipping')) {
            $cmr->ppc_shipping = $request->input('ppc_shipping');
        }
        if (Schema::hasColumn('cmrs', 'ppc_shipping_detail')) {
            $cmr->ppc_shipping_detail = $request->input('ppc_shipping_detail');
        }

        $cmr->save();

        // When VDD stores a PPC, do NOT auto-approve as Procurement; mark the process as waiting for Procurement
        if (Schema::hasColumn('cmrs', 'procurement_status')) {
            $cmr->procurement_status = 'pending';
        }
        if (Schema::hasColumn('cmrs', 'procurement_approver_id')) {
            $cmr->procurement_approver_id = null;
        }
        if (Schema::hasColumn('cmrs', 'procurement_approved_at')) {
            $cmr->procurement_approved_at = null;
        }
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Waiting for Procurement approval';
        }

        $cmr->save();

        return redirect()->route('vdd.cmr.index')->with('status', 'CMR compensation saved and processed.');
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
