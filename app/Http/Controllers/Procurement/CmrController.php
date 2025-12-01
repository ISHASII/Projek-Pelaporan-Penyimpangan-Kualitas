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
                case 'waiting_vdd':
                    if (Schema::hasColumn('cmrs', 'vdd_status')) {
                        $query->where('ppchead_status', 'approved')
                              ->where('vdd_status', 'pending');
                    } elseif (Schema::hasColumn('cmrs', 'status_approval')) {
                        $query->where('ppchead_status', 'approved')
                              ->where('status_approval', 'like', '%VDD%')
                              ->where(function($sub) {
                                  $sub->where('status_approval', 'like', '%Waiting%')
                                      ->orWhere('status_approval', 'like', '%Menunggu%');
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
                case 'completed':
                    if (Schema::hasColumn('cmrs', 'procurement_status')) {
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'approved')
                              ->where('ppchead_status', 'approved');

                        if (Schema::hasColumn('cmrs', 'vdd_status')) {
                            $query->where('vdd_status', 'approved');
                        }

                        $query->where(function($q) {
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

        // require VDD approved in new flow (if column exists)
        if (Schema::hasColumn('cmrs', 'vdd_status') && (($cmr->vdd_status ?? '') !== 'approved')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot approve before VDD approval.'], 400);
            }
            return redirect()->route('procurement.cmr.index')->with('status', 'Cannot approve before VDD approval.');
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

    /**
     * Show input form for Procurement to set pay_compensation
     */
    public function showInputCompensation($id)
    {
        $cmr = Cmr::findOrFail($id);
        return view('procurement.cmr.input_compensation', compact('cmr'));
    }

    /**
     * Store pay_compensation set by Procurement and approve the CMR
     */
    public function storeCompensation(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        // require PPC already approved
        if (($cmr->ppchead_status ?? '') !== 'approved') {
            return redirect()->route('procurement.cmr.index')->with('status', 'Cannot set compensation before PPC Head approval.');
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
                // helper: recursively find a value by key in array (returns first found)
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

                // preserve previous PPC disposition if present so PDF can show both actions
                $prevDisposition = null;
                // prefer explicit ppc.disposition
                if (isset($existingDecoded['ppc']) && is_array($existingDecoded['ppc']) && isset($existingDecoded['ppc']['disposition'])) {
                    $prevDisposition = $existingDecoded['ppc']['disposition'];
                } else {
                    // try root-level or nested disposition anywhere
                    $prevDisposition = $findValueByKey($existingDecoded, 'disposition');
                }

                if ($prevDisposition && $prevDisposition !== ($ppcData['disposition'] ?? null)) {
                    $ppcData['prev_disposition'] = $prevDisposition;
                }

                // Do not overwrite existing PPC keys (e.g. shipping, shipping_detail) with null/empty values
                $ppcDataFiltered = array_filter($ppcData, function ($v) {
                    return !($v === null || $v === '');
                });

                // If existing decoded includes a 'ppc' subarray, merge fields preserving the existing ones
                if (isset($existingDecoded['ppc']) && is_array($existingDecoded['ppc'])) {
                    $ppcMerged = array_merge($existingDecoded['ppc'], $ppcDataFiltered);
                } else {
                    $ppcMerged = $ppcDataFiltered;
                }

                // If prevDisposition exists and differs, ensure it is stored
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

        // Save to dedicated columns when available
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

        // Save compensation first
        $cmr->save();

        // Immediately mark as approved by Procurement (auto-approve on save)
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

        // Send approval notification (same as manual approve)
        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'Procurement', 'approved', null, $actorName);
        Notification::send(User::all(), $notification);

        return redirect()->route('procurement.cmr.index')->with('status', 'CMR approved by Procurement.');
    }
}
