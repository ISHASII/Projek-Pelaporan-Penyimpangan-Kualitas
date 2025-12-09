<?php

namespace App\Http\Controllers\Agm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cmr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\CmrStatusChanged;
use App\Notifications\CmrApprovalRequested;
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

        // For AGM, show items that have been requested (QC requested)
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

        if (!empty($approval_status)) {
            switch ($approval_status) {
                case 'pending_request':
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
                    if (Schema::hasColumn('cmrs', 'ppchead_status')) {
                        $query->where('agm_status', 'approved')
                              ->where(function($sub) {
                                  $sub->where('ppchead_status', 'pending')
                                      ->orWhereNull('ppchead_status');
                              });
                    } else {
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
                case 'rejected_agm':
                    $query->where('agm_status', 'rejected');
                    break;
                case 'rejected_ppc':
                    $query->where('ppchead_status', 'rejected');
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

                case 'menunggu_dept':
                    $query->where('depthead_status', 'pending');
                    break;
                case 'ditolak_dept':
                    $query->where('depthead_status', 'rejected');
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

        // Fetch PPC Head approvers from lembur database for approval modal
        // Role mapping: PPC Head = dept=PPC, golongan=4, acting=1
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
                $ppcApprovers->push((object)[
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to local users with ppc role
            $ppcApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%ppc%'])->get()->map(function ($u) {
                return (object)[
                    'npk' => $u->npk,
                    'name' => $u->name,
                    'email' => $u->email,
                ];
            });
        }

        // Use a dedicated AGM view (wraps same layout) so we can customize later
        return view('agm.cmr.index', compact('cmrs', 'years', 'ppcApprovers'));
    }

    public function approve(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        if (($cmr->depthead_status ?? '') !== 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot approve before Dept Head approval.'], 400);
            }
            return redirect()->route('agm.cmr.index')->with('status', 'Cannot approve before Dept Head approval.');
        }

        $current = strtolower($cmr->agm_status ?? 'pending');
        if ($current === 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already approved by AGM.'], 400);
            }
            return redirect()->route('agm.cmr.index')->with('status', 'CMR already approved by AGM.');
        }
        if ($current === 'rejected') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already rejected by AGM; cannot approve.'], 400);
            }
            return redirect()->route('agm.cmr.index')->with('status', 'CMR already rejected by AGM; cannot approve.');
        }

        $cmr->agm_status = 'approved';
        $cmr->agm_note = $request->input('note');
        $cmr->agm_approver_id = auth()->id();
        $cmr->agm_approved_at = now();

        // set PPC stage to pending so PPC Head can take action next
        if (Schema::hasColumn('cmrs', 'ppchead_status')) {
            $cmr->ppchead_status = 'pending';
            if (Schema::hasColumn('cmrs', 'ppchead_approver_id')) {
                $cmr->ppchead_approver_id = null;
            }
            if (Schema::hasColumn('cmrs', 'ppchead_approved_at')) {
                $cmr->ppchead_approved_at = null;
            }
        }

        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Waiting for PPC Head approval';
        }

        $cmr->save();

        // Get selected recipients from request (NPKs)
        $selectedRecipients = $request->input('recipients', []);

        // Fetch PPC Head approvers from lembur database
        // Role mapping: PPC Head = dept=PPC, golongan=4, acting=1
        $emailRecipients = [];
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
            Log::warning('Failed to fetch PPC Head from lembur for CMR', ['error' => $e->getMessage()]);
        }

        // Send email notifications to PPC Head approvers
        foreach ($emailRecipients as $recipient) {
            if (!empty($recipient->email)) {
                try {
                    Mail::send('emails.cmr_approval_requested', [
                        'cmr' => $cmr,
                        'recipientName' => $recipient->name,
                        'targetRole' => 'PPC Head',
                    ], function ($message) use ($recipient, $cmr) {
                        $message->to($recipient->email, $recipient->name)
                            ->subject('Permintaan Persetujuan CMR: ' . $cmr->no_reg);
                    });
                } catch (\Throwable $mailErr) {
                    Log::warning('Failed to send CMR approval email to PPC Head', ['email' => $recipient->email, 'error' => $mailErr->getMessage()]);
                }
            }
        }

        // Send web notifications to local users with PPC role
        $ppcApprovers = User::all()->filter(function($u){
            return $u->hasRole('ppc');
        });

        if ($ppcApprovers->count()) {
            Notification::send($ppcApprovers, new CmrApprovalRequested($cmr, 'PPC Head'));
        }

        $actorName = auth()->user()->name ?? auth()->id();

        // notify PPC users only (role contains 'ppc' or 'ppchead')
        $ppcUsers = User::where('role', 'like', '%ppc%')
                        ->orWhere('role', 'like', '%ppchead%')
                        ->get();

        if ($ppcUsers && $ppcUsers->count()) {
            $notification = new CmrStatusChanged($cmr, 'AGM', 'approved', $cmr->agm_note, $actorName);
            Notification::send($ppcUsers, $notification);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR approved by AGM and forwarded to PPC Head.',
                'new_status' => 'Waiting for PPC Head approval',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('agm.cmr.index')->with('status', 'CMR approved and forwarded to PPC Head.');
    }

    public function reject(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        if (($cmr->depthead_status ?? '') !== 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot reject before Dept Head approval.'], 400);
            }
            return redirect()->route('agm.cmr.index')->with('status', 'Cannot reject before Dept Head approval.');
        }

        $current = strtolower($cmr->agm_status ?? 'pending');
        if ($current === 'rejected') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already rejected by AGM.'], 400);
            }
            return redirect()->route('agm.cmr.index')->with('status', 'CMR already rejected by AGM.');
        }
        if ($current === 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR already approved by AGM; cannot reject.'], 400);
            }
            return redirect()->route('agm.cmr.index')->with('status', 'CMR already approved by AGM; cannot reject.');
        }

        $cmr->agm_status = 'rejected';
        $cmr->agm_note = $request->input('note');
        $cmr->agm_approver_id = auth()->id();
        $cmr->agm_approved_at = now();
        if (Schema::hasColumn('cmrs', 'status_approval')) {
            $cmr->status_approval = 'Rejected by AGM';
        }
        $cmr->save();

        $actorName = auth()->user()->name ?? auth()->id();
        $notification = new CmrStatusChanged($cmr, 'AGM', 'rejected', $cmr->agm_note, $actorName);
        Notification::send(User::all(), $notification);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CMR rejected by AGM.',
                'new_status' => 'Rejected by AGM',
                'hide_actions' => true
            ]);
        }

        return redirect()->route('agm.cmr.index')->with('status', 'CMR rejected.');
    }

    public function previewFpdf($id)
    {
        $qcController = new \App\Http\Controllers\QC\CmrController();
        return $qcController->previewFpdf($id);
    }
}
