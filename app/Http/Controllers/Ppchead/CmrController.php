<?php

namespace App\Http\Controllers\Ppchead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cmr;
use App\Models\User;
use App\Notifications\CmrStatusChanged;
use App\Notifications\CmrApprovalRequested;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CmrController extends Controller
{
    public function index()
    {
        $q = request()->query('q');
        $date = request()->query('date');
        $year = request()->query('year');
        $approval_status = request()->query('approval_status');

        // Show all requested CMRs to PPC Head, but only allow actions when
        // the CMR's status_approval indicates it's waiting for PPC Head approval.
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
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'approved')
                              ->where('ppchead_status', 'approved')
                              ->where('vdd_status', 'pending');
                    } elseif (Schema::hasColumn('cmrs', 'status_approval')) {
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'approved')
                              ->where('ppchead_status', 'approved')
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
                    $query->where('agm_status', 'rejected');
                    break;
                case 'rejected_ppc':
                    $query->where('ppchead_status', 'rejected');
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
                        $query->where('ppchead_status', 'approved');
                    }
                    break;
                // legacy keys
                case 'ditolak_ppc':
                    $query->where('ppchead_status', 'rejected');
                    break;
                case 'menunggu_ppc':
                    $query->where('ppchead_status', 'pending');
                    break;
                case 'selesai':
                    $query->where('ppchead_status', 'approved');
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

        // Fetch VDD approvers from lembur database for approval modal
        // Role mapping: VDD = dept=VDD, golongan=4, acting=1
        $vddApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'VDD')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $vddApprovers->push((object)[
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with vdd role
            $vddApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%vdd%'])->get()->map(function ($u) {
                return (object)[
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                ];
            });
        }

        return view('ppchead.cmr.index', compact('cmrs', 'years', 'vddApprovers'));
    }

    public function create() { return view('ppchead.cmr.create'); }
    public function store(Request $r) { return redirect()->route('ppchead.cmr.index')->with('status','CMR created'); }
    public function show($id) { return view('ppchead.cmr.show', compact('id')); }
    public function edit($id) { return view('ppchead.cmr.edit', compact('id')); }
    public function update(Request $r,$id) { return redirect()->route('ppchead.cmr.show',$id)->with('status','CMR updated'); }
    public function destroy($id) { return redirect()->route('ppchead.cmr.index')->with('status','CMR deleted'); }

    public function previewFpdf($id)
    {
        $qcController = new \App\Http\Controllers\QC\CmrController();
        return $qcController->previewFpdf($id);
    }

    /**
     * Show standalone PPC input page for a CMR
     */
    public function showPpcForm($id)
    {
        $cmr = Cmr::findOrFail($id);

        // Fetch VDD approvers from lembur database for approval modal
        // Role mapping: VDD = dept=VDD, golongan=4, acting=1
        $vddApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'VDD')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $vddApprovers->push((object)[
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with vdd role
            $vddApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%vdd%'])->get()->map(function ($u) {
                return (object)[
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                ];
            });
        }

        return view('ppchead.cmr.ppc_form', compact('cmr', 'vddApprovers'));
    }

    /**
     * Store PPC input for a CMR
     */
    public function storePpcForm(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        $rules = [
            'ppc_disposition' => 'required|string|in:send_replacement',
            'ppc_shipping' => 'nullable|string|in:AIR,SEA',
        ];

        $validated = $request->validate($rules);

        // store PPC data inside ppchead_note as JSON (safe default if specific columns don't exist)
        $ppcData = [
            'disposition' => $validated['ppc_disposition'],
            'shipping' => $request->input('ppc_shipping'),
            'filled_by' => auth()->user()->name ?? auth()->id(),
            'filled_at' => now()->toDateTimeString(),
        ];

        if (Schema::hasColumn('cmrs', 'ppchead_note')) {
            $existing = $cmr->ppchead_note;
            // attempt decode if json
            $existingDecoded = null;
            try { $existingDecoded = json_decode($existing, true); } catch (\Throwable $e) { $existingDecoded = null; }
            if (is_array($existingDecoded)) {
                $ppcData = array_merge($existingDecoded, ['ppc' => $ppcData]);
            } else {
                $ppcData = ['ppc' => $ppcData, 'note' => $existing];
            }
            $cmr->ppchead_note = json_encode($ppcData);
        } else {
            $cmr->depthead_note = ($cmr->depthead_note ?? '') . "\nPPC: " . json_encode($ppcData);
        }

        // save PPC data only — do NOT auto-approve here.
        // The PPC Head should explicitly approve from the index page.
        $cmr->save();

        return redirect()->route('ppchead.cmr.index')->with('status', 'PPC data saved. Please approve the CMR when ready.');
    }

    /**
     * Approve CMR by PPC Head
     */
    public function approve(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);
        // ensure AGM already approved before PPC can approve
        if (($cmr->agm_status ?? '') !== 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot approve before AGM approval.'], 400);
            }
            return redirect()->route('ppchead.cmr.index')->with('status', 'Cannot approve before AGM approval.');
        }

        if (Schema::hasColumn('cmrs', 'ppchead_status')) {
            $cmr->ppchead_status = 'approved';
        }
        if (Schema::hasColumn('cmrs', 'ppchead_approver_id')) {
            $cmr->ppchead_approver_id = auth()->id();
        }
        if (Schema::hasColumn('cmrs', 'ppchead_approved_at')) {
            $cmr->ppchead_approved_at = now();
        }

        // set VDD stage to pending so VDD can take action next (new flow)
        if (Schema::hasColumn('cmrs', 'vdd_status')) {
            $cmr->vdd_status = 'pending';
            if (Schema::hasColumn('cmrs', 'vdd_approver_id')) {
                $cmr->vdd_approver_id = null;
            }
            if (Schema::hasColumn('cmrs', 'vdd_approved_at')) {
                $cmr->vdd_approved_at = null;
            }
        }

        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Waiting for VDD approval';
        }

        $cmr->save();

        // Get selected recipients from request (NPKs)
        $selectedRecipients = $request->input('recipients', []);

        // Fetch VDD approvers from lembur database
        // Role mapping: VDD = dept=VDD, golongan=4, acting=1
        $emailRecipients = [];
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'VDD')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                // If specific recipients selected, filter by NPK
                if (!empty($selectedRecipients) && !in_array($ext->npk, $selectedRecipients)) {
                    continue;
                }
                $emailRecipients[] = (object)[
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to fetch VDD from lembur for CMR', ['error' => $e->getMessage()]);
        }

        // Send email notifications to VDD approvers
        foreach ($emailRecipients as $recipient) {
            if (!empty($recipient->email)) {
                try {
                    Mail::send('emails.cmr_approval_requested', [
                        'cmr' => $cmr,
                        'recipientName' => $recipient->name,
                        'targetRole' => 'VDD',
                    ], function ($message) use ($recipient, $cmr) {
                        $message->to($recipient->email, $recipient->name)
                            ->subject('Permintaan Persetujuan CMR: ' . $cmr->no_reg);
                    });
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send CMR approval email to VDD', ['email' => $recipient->email, 'error' => $mailErr->getMessage()]);
                }

                // Store to notification_push table
                try {
                    $message = \App\Services\NotificationPushService::formatCmrMessage($cmr, 'approved', 'PPC Head');
                    \App\Services\NotificationPushService::store($recipient->npk, $recipient->email, $message);
                } catch (\Throwable $e) {
                    Log::warning('Failed to store notification_push', ['error' => $e->getMessage()]);
                }
            }
        }

        // Send web notifications to local users with VDD role
        $vddApprovers = User::all()->filter(function($u){
            return $u->hasRole('vdd');
        });

        if ($vddApprovers->count()) {
            Notification::send($vddApprovers, new CmrApprovalRequested($cmr, 'VDD'));
        }

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'PPC Head', 'approved', null, $actorName);

        // notify all users about the approval
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR approved by PPC Head and forwarded to VDD.',
                'new_status' => 'Waiting for VDD approval',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('ppchead.cmr.index')->with('status', 'CMR approved by PPC Head and forwarded to VDD.');
    }

    /**
     * Reject CMR by PPC Head
     */
    public function reject(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        if (Schema::hasColumn('cmrs', 'ppchead_status')) {
            $cmr->ppchead_status = 'rejected';
        }
        if (Schema::hasColumn('cmrs', 'ppchead_approver_id')) {
            $cmr->ppchead_approver_id = auth()->id();
        }
        if (Schema::hasColumn('cmrs', 'ppchead_approved_at')) {
            $cmr->ppchead_approved_at = now();
        }
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Rejected by PPC Head';
        }

        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'PPC Head', 'rejected', null, $actorName);
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR rejected by PPC Head.',
                'new_status' => 'Rejected by PPC Head',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('ppchead.cmr.index')->with('status', 'CMR rejected by PPC Head.');
    }
}
