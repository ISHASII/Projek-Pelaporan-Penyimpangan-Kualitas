<?php

namespace App\Http\Controllers\Depthead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cmr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\CmrStatusChanged;
use App\Notifications\CmrApprovalRequested;
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

        // Default: Depthead should see all requested items (including those rejected by Sect/Dept)
        // Some filters (like pending_request) show non-requested items, so we may override below.
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
                    // show items that have not been requested yet
                    $query = Cmr::whereNull('requested_at_qc');
                    break;
                case 'waiting_foreman':
                    if (Schema::hasColumn('cmrs', 'foreman_status')) {
                        $query->where('foreman_status', 'pending');
                    }
                    break;
                case 'waiting_sect':
                    $query->where('secthead_status', 'pending');
                    break;
                case 'waiting_dept':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'pending');
                    break;
                case 'waiting_ppc':
                    // PPC waiting: AGM must have approved and PPC not yet approved
                    if (Schema::hasColumn('cmrs', 'agm_status')) {
                        $query->where('agm_status', 'approved')
                              ->where(function($sub) {
                                  $sub->where('ppchead_status', 'pending')
                                      ->orWhereNull('ppchead_status');
                              });
                    } else {
                        // fallback to status_approval text
                        $query->where('status_approval', 'like', '%PPC%');
                    }
                    break;
                case 'rejected_foreman':
                    if (Schema::hasColumn('cmrs', 'foreman_status')) {
                        $query->where('foreman_status', 'rejected');
                    }
                    break;
                case 'rejected_sect':
                    $query->where('secthead_status', 'rejected');
                    break;
                case 'rejected_dept':
                    $query->where('depthead_status', 'rejected');
                    break;
                case 'rejected_ppc':
                    $query->where('ppchead_status', 'rejected');
                    break;
                case 'rejected_agm':
                    $query->where('agm_status', 'rejected');
                    break;
                case 'waiting_agm':
                    if (Schema::hasColumn('cmrs', 'agm_status')) {
                        $query->where('secthead_status', 'approved')
                              ->where('depthead_status', 'approved')
                              ->where('agm_status', 'pending');
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
                    // Completed if all approved and procurement approved or empty
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
                    break;
                // legacy short keys (existing behavior)
                case 'ditolak_dept':
                    $query->where('depthead_status', 'rejected');
                    break;
                case 'menunggu_dept':
                    $query->where('depthead_status', 'pending');
                    break;
                case 'selesai':
                    $query->where('depthead_status', 'approved')
                          ->where('ppchead_status', 'approved');
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

        // Fetch AGM approvers from lembur database for approval modal
        // Role mapping: AGM = dept=AGM, golongan=4, acting=1
        $agmApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'AGM')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $agmApprovers->push((object)[
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with agm role
            $agmApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%agm%'])->get()->map(function ($u) {
                return (object)[
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                ];
            });
        }

        return view('depthead.cmr.index', compact('cmrs', 'years', 'agmApprovers'));
    }

    public function approve(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        if (($cmr->secthead_status ?? '') !== 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot approve before Sect Head approval.'], 400);
            }
            return redirect()->route('depthead.cmr.index');
        }

        $current = strtolower($cmr->depthead_status ?? 'pending');
        if ($current === 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already approved by Dept Head.'], 400);
            }
            return redirect()->route('depthead.cmr.index');
        }
        if ($current === 'rejected') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already rejected by Dept Head; cannot approve.'], 400);
            }
            return redirect()->route('depthead.cmr.index');
        }

        $cmr->depthead_status = 'approved';
        $cmr->depthead_note = $request->input('note');
        $cmr->depthead_approver_id = auth()->id();
        $cmr->depthead_approved_at = now();

        // set AGM stage to pending so AGM can take action next
        if (Schema::hasColumn('cmrs', 'agm_status')) {
            $cmr->agm_status = 'pending';
            // clear any previous AGM approver/timestamps
            if (Schema::hasColumn('cmrs', 'agm_approver_id')) {
                $cmr->agm_approver_id = null;
            }
            if (Schema::hasColumn('cmrs', 'agm_approved_at')) {
                $cmr->agm_approved_at = null;
            }
        }

        // ensure PPC stage is not active yet (clear PPC status so it won't appear in PPC queue)
        if (Schema::hasColumn('cmrs', 'ppchead_status')) {
            $cmr->ppchead_status = null;
            if (Schema::hasColumn('cmrs', 'ppchead_approver_id')) {
                $cmr->ppchead_approver_id = null;
            }
            if (Schema::hasColumn('cmrs', 'ppchead_approved_at')) {
                $cmr->ppchead_approved_at = null;
            }
        }

        // human-readable summary status
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Waiting for AGM approval';
        }

        $cmr->save();

        // Get selected recipients from request (NPKs)
        $selectedRecipients = $request->input('recipients', []);

        // Fetch AGM approvers from lembur database
        // Role mapping: AGM = dept=AGM, golongan=4, acting=1
        $emailRecipients = [];
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'AGM')
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
            Log::warning('Failed to fetch AGM from lembur for CMR', ['error' => $e->getMessage()]);
        }

        // Send email notifications to AGM approvers
        foreach ($emailRecipients as $recipient) {
            if (!empty($recipient->email)) {
                try {
                    Mail::send('emails.cmr_approval_requested', [
                        'cmr' => $cmr,
                        'recipientName' => $recipient->name,
                        'targetRole' => 'AGM',
                    ], function ($message) use ($recipient, $cmr) {
                        $message->to($recipient->email, $recipient->name)
                            ->subject('Permintaan Persetujuan CMR: ' . $cmr->no_reg);
                    });
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send CMR approval email to AGM', ['email' => $recipient->email, 'error' => $mailErr->getMessage()]);
                }

                // Store to notification_push table
                try {
                    $message = \App\Services\NotificationPushService::formatCmrMessage($cmr, 'approved', 'Dept Head');
                    \App\Services\NotificationPushService::store($recipient->npk, $recipient->email, $message);
                } catch (\Throwable $e) {
                    Log::warning('Failed to store notification_push', ['error' => $e->getMessage()]);
                }
            }
        }

        // Send web notifications to local users with AGM role
        $agmApprovers = User::all()->filter(function($u){
            return $u->hasRole('agm');
        });

        if ($agmApprovers->count()) {
            Notification::send($agmApprovers, new CmrApprovalRequested($cmr, 'AGM'));
        }

        $actorName = auth()->user()->name ?? auth()->id();

        // Notify the CMR creator (QC user) and AGM users
        $usersToNotify = collect();

        // Add CMR creator
        if ($cmr->user_id) {
            $creator = User::find($cmr->user_id);
            if ($creator) {
                $usersToNotify->push($creator);
            }
        }

        // Add AGM users (role contains 'agm' or 'assistantgeneralmanager')
        $agmUsers = User::where('role', 'like', '%agm%')
                        ->orWhere('role', 'like', '%assistantgeneralmanager%')
                        ->get();

        $usersToNotify = $usersToNotify->merge($agmUsers)->unique('id');

        if ($usersToNotify->count()) {
            $notification = new CmrStatusChanged($cmr, 'Dept Head', 'approved', $cmr->depthead_note, $actorName);
            Notification::send($usersToNotify, $notification);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR approved by Dept Head.',
                'new_status' => 'Waiting for AGM approval',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('depthead.cmr.index');
    }

    public function reject(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        if (($cmr->secthead_status ?? '') !== 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot reject before Sect Head approval.'], 400);
            }
            return redirect()->route('depthead.cmr.index');
        }

        $current = strtolower($cmr->depthead_status ?? 'pending');
        if ($current === 'rejected') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already rejected by Dept Head.'], 400);
            }
            return redirect()->route('depthead.cmr.index');
        }
        if ($current === 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already approved by Dept Head; cannot reject.'], 400);
            }
            return redirect()->route('depthead.cmr.index');
        }

        $cmr->depthead_status = 'rejected';
        $cmr->depthead_note = $request->input('note');
        $cmr->depthead_approver_id = auth()->id();
        $cmr->depthead_approved_at = now();
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Ditolak Dept Head';
        }
        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();

        // Notify the CMR creator (QC user), Sect Head, and relevant stakeholders
        $usersToNotify = collect();

        // Add CMR creator
        if ($cmr->user_id) {
            $creator = User::find($cmr->user_id);
            if ($creator) {
                $usersToNotify->push($creator);
            }
        }

        // Add Sect Head who approved it
        if ($cmr->secthead_approver_id) {
            $sectHead = User::find($cmr->secthead_approver_id);
            if ($sectHead) {
                $usersToNotify->push($sectHead);
            }
        }

        $usersToNotify = $usersToNotify->unique('id');

        if ($usersToNotify->count()) {
            $notification = new CmrStatusChanged($cmr, 'Dept Head', 'rejected', $cmr->depthead_note, $actorName);
            Notification::send($usersToNotify, $notification);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR rejected by Dept Head.',
                'new_status' => 'Rejected by Dept Head',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('depthead.cmr.index');
    }

    // keep other resource methods as stubs
    public function create()
    {
        return view('depthead.cmr.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('depthead.cmr.index')->with('status', 'CMR created.');
    }

    public function show($id)
    {
        return view('depthead.cmr.show', compact('id'));
    }

    public function edit($id)
    {
        return view('depthead.cmr.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('depthead.cmr.show', $id)->with('status', 'CMR updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('depthead.cmr.index')->with('status', 'CMR deleted.');
    }

    public function previewFpdf($id)
    {
        $qcController = new \App\Http\Controllers\QC\CmrController();
        return $qcController->previewFpdf($id);
    }
}
