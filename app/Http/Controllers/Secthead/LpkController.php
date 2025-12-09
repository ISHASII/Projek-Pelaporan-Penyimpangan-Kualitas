<?php

namespace App\Http\Controllers\Secthead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\LpkStatusChanged;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LpkController extends Controller
{
    public function index()
    {
        // Filters: q (search), date, year, approval_status, status_lpk
        $q = request()->query('q');
        $date = request()->query('date');
        $year = request()->query('year');
        $approval_status = request()->query('approval_status');
        $status_lpk = request()->query('status_lpk');

        // Only show LPKs that QC has requested approval for
        $query = \App\Models\Lpk::whereNotNull('requested_at_qc');

        // Global text search across several columns
        if (!empty($q)) {
            $query->where(function($sub) use ($q) {
                $sub->where('no_reg', 'like', "%{$q}%")
                    ->orWhere('nama_supply', 'like', "%{$q}%")
                    ->orWhere('nama_part', 'like', "%{$q}%")
                    ->orWhere('nomor_part', 'like', "%{$q}%")
                    ->orWhere('nomor_po', 'like', "%{$q}%")
                    ->orWhere('problem', 'like', "%{$q}%");
            });
        }

        // Exact date filter (tgl_terbit)
        if (!empty($date)) {
            $query->whereDate('tgl_terbit', $date);
        }

        // Year filter
        if (!empty($year)) {
            $query->whereYear('tgl_terbit', intval($year));
        }

        // Approval status filter
        if (!empty($approval_status)) {
            switch ($approval_status) {
                case 'ditolak_sect':
                    $query->where('secthead_status', 'rejected');
                    break;
                case 'ditolak_dept':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', 'rejected');
                    break;
                case 'ditolak_ppc':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', 'rejected');
                    break;
                case 'menunggu_sect':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'pending');
                    break;
                case 'menunggu_dept':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'approved')
                          ->where('depthead_status', 'pending');
                    break;
                case 'menunggu_ppc':
                    $query->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('ppchead_status', 'pending');
                    break;
                case 'selesai':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('ppchead_status', 'approved');
                    break;
            }
        }

        // Status LPK filter (Claim / Complaint-Informasi)
        if (!empty($status_lpk)) {
            if (strtolower($status_lpk) === 'claim') {
                $query->where('status', 'Claim');
            } elseif (strtolower($status_lpk) === 'complaint') {
                // Complaint atau Informasi (bukan Claim)
                $query->where('status', '!=', 'Claim');
            }
        }

        // paginate and preserve query string for pagination links
        $lpks = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Provide available years from existing records for the year dropdown
        $driver = \Illuminate\Support\Facades\DB::getDriverName();
        if ($driver === 'sqlite') {
            $yearsQuery = \App\Models\Lpk::whereNotNull('tgl_terbit')->selectRaw("strftime('%Y', tgl_terbit) as year");
        } elseif ($driver === 'mysql') {
            $yearsQuery = \App\Models\Lpk::whereNotNull('tgl_terbit')->selectRaw('YEAR(tgl_terbit) as year');
        } elseif ($driver === 'pgsql') {
            $yearsQuery = \App\Models\Lpk::whereNotNull('tgl_terbit')->selectRaw('EXTRACT(YEAR FROM tgl_terbit) as year');
        } else {
            // Generic fallback: use DATE_FORMAT if available, otherwise attempt YEAR()
            $yearsQuery = \App\Models\Lpk::whereNotNull('tgl_terbit')->selectRaw('YEAR(tgl_terbit) as year');
        }

        $years = $yearsQuery->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->filter()
                    ->map(function($y){ return (int)$y; })
                    ->values();

        // Provide list of Dept Head approvers from lembur
        // Role mapping: Dept Head = dept=QA, golongan=4, acting=1
        $deptApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'QA')
                ->where('golongan', 4)
                ->where('acting', 1)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                $localUser = User::where('npk', $ext->npk)->first();
                $deptApprovers->push((object)[
                    'id' => $localUser ? $localUser->id : null,
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                    'golongan' => $ext->golongan,
                    'acting' => $ext->acting,
                ]);
            }
        } catch (\Throwable $e) {
            $deptApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%dept%'])->get()->map(function ($u) {
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

        return view('secthead.lpk.index', compact('lpks', 'years', 'deptApprovers'));
    }

    public function create()
    {
        return view('secthead.lpk.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('secthead.lpk.index')->with('status', 'LPK created.');
    }

    public function show($id)
    {
        // Secthead no longer has a dedicated show page
        abort(404);
    }

    public function edit($id)
    {
        return view('secthead.lpk.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('secthead.lpk.index')->with('status', 'LPK updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('secthead.lpk.index')->with('status', 'LPK deleted.');
    }

    // Approve or reject from Sect Head
    public function approve(Request $request, $id)
    {
        // Validate recipients if provided (NPKs from lembur)
        $request->validate([
            'recipients' => 'nullable|array',
            'recipients.*' => 'string',
        ]);
        $lpk = \App\Models\Lpk::findOrFail($id);
        $isAjax = $request->ajax() || $request->wantsJson();

        // Prevent re-approving or changing after decision
        $current = strtolower($lpk->secthead_status ?? '');
        if ($current === 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-approve oleh Sect Head.'], 400);
            }
            return redirect()->route('secthead.lpk.index')->with('status', 'LPK already approved by Sect Head.');
        }
        if ($current === 'rejected') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-reject oleh Sect Head; tidak bisa approve.'], 400);
            }
            return redirect()->route('secthead.lpk.index')->with('status', 'LPK already rejected by Sect Head; cannot approve.');
        }

    $lpk->secthead_status = 'approved';
    $lpk->secthead_note = $request->input('note');
    $lpk->secthead_approver_id = auth()->id();
    $lpk->secthead_approved_at = now();
    $lpk->save();

    // send notification to all users about status change
    $actorName = auth()->user()->name ?? auth()->id();
    $notification = new LpkStatusChanged($lpk, 'Sect Head', 'approved', $lpk->secthead_note, $actorName);
    $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
    Notification::send($recipients, $notification);

    // If Sect Head selected recipients (Dept Heads) to forward approval request to, send them email and web notification
    if ($request->has('recipients') && is_array($request->input('recipients'))) {
        $selectedNpks = array_filter($request->input('recipients'));

        if (!empty($selectedNpks)) {
            try {
                // Get Dept Head approvers from lembur (golongan=4, acting=1)
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->whereIn('npk', $selectedNpks)
                    ->where('dept', 'QA')
                    ->where('golongan', 4)
                    ->where('acting', 1)
                    ->whereNotNull('user_email')
                    ->where('user_email', '!=', '')
                    ->get();

                foreach ($lemburRecipients as $lr) {
                    // Send email
                    try {
                        \Illuminate\Support\Facades\Mail::send(
                            'emails.lpk_approval_requested',
                            ['lpk' => $lpk, 'recipientName' => $lr->full_name],
                            function ($message) use ($lr, $lpk) {
                                $message->to($lr->user_email, $lr->full_name)
                                        ->subject('Permintaan Persetujuan LPK: ' . $lpk->no_reg);
                            }
                        );
                    } catch (\Throwable $mailErr) {
                        \Illuminate\Support\Facades\Log::warning('Failed to send LPK approval email to Dept Head', [
                            'npk' => $lr->npk,
                            'email' => $lr->user_email,
                            'error' => $mailErr->getMessage()
                        ]);
                    }

                    // Store to notification_push table
                    try {
                        $message = \App\Services\NotificationPushService::formatLpkMessage($lpk, 'approved', 'Sect Head');
                        \App\Services\NotificationPushService::store($lr->npk, $lr->user_email, $message);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning('Failed to store notification_push', ['error' => $e->getMessage()]);
                    }

                    // Also send web notification to local user (if exists)
                    $localUser = User::where('npk', $lr->npk)->first();
                    if ($localUser) {
                        try {
                            $localUser->notify(new \App\Notifications\LpkApprovalRequested($lpk));
                        } catch (\Throwable $notifErr) {
                            \Illuminate\Support\Facades\Log::warning('Failed to send database notification to Dept Head', [
                                'npk' => $lr->npk,
                                'error' => $notifErr->getMessage()
                            ]);
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to fetch Dept Head recipients from lembur', ['error' => $e->getMessage()]);
            }
        }
    }

    if ($isAjax) {
        return response()->json([
            'success' => true,
            'message' => 'LPK berhasil di-approve.',
            'lpk' => [
                'id' => $lpk->id,
                'secthead_status' => $lpk->secthead_status,
                'depthead_status' => $lpk->depthead_status,
                'ppchead_status' => $lpk->ppchead_status,
            ]
        ]);
    }

    return redirect()->route('secthead.lpk.index')->with('status', 'LPK approved.');
    }

    public function reject(Request $request, $id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);
        $isAjax = $request->ajax() || $request->wantsJson();

        $current = strtolower($lpk->secthead_status ?? '');
        if ($current === 'rejected') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-reject oleh Sect Head.'], 400);
            }
            return redirect()->route('secthead.lpk.index')->with('status', 'LPK already rejected by Sect Head.');
        }
        if ($current === 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-approve oleh Sect Head; tidak bisa reject.'], 400);
            }
            return redirect()->route('secthead.lpk.index')->with('status', 'LPK already approved by Sect Head; cannot reject.');
        }

    $lpk->secthead_status = 'rejected';
    $lpk->secthead_note = $request->input('note');
    $lpk->secthead_approver_id = auth()->id();
    $lpk->secthead_approved_at = now();
    $lpk->save();

    // Store to notification_push table for rejection
    try {
        $message = \App\Services\NotificationPushService::formatLpkMessage($lpk, 'rejected', 'Sect Head');
        // Notify QC creator if available
        $qcUser = $lpk->creator ?? null;
        if ($qcUser && $qcUser->npk) {
            \App\Services\NotificationPushService::store($qcUser->npk, $qcUser->email, $message);
        }
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::warning('Failed to store notification_push for rejection', ['error' => $e->getMessage()]);
    }

    // send notification to all users except AGM and Procurement (they should only receive CMR notifications)
    $actorName = auth()->user()->name ?? auth()->id();
    $notification = new LpkStatusChanged($lpk, 'Sect Head', 'rejected', $lpk->secthead_note, $actorName);
    $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
    Notification::send($recipients, $notification);

    if ($isAjax) {
        return response()->json([
            'success' => true,
            'message' => 'LPK berhasil di-reject.',
            'lpk' => [
                'id' => $lpk->id,
                'secthead_status' => $lpk->secthead_status,
                'depthead_status' => $lpk->depthead_status,
                'ppchead_status' => $lpk->ppchead_status,
            ]
        ]);
    }

    return redirect()->route('secthead.lpk.index')->with('status', 'LPK rejected.');
    }

    // Download PDF
    public function downloadPdf($id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('qc.lpk.export_pdf', compact('lpk'));
        $pdf->setPaper('a5', 'landscape');
        $raw = 'LPK-'.$lpk->no_reg.'-'.date('Ymd').'.pdf';
        $filename = $this->sanitizeFilename($raw);
        return $pdf->download($filename);
    }

    // Preview PDF (display in browser)
    public function previewPdf($id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('qc.lpk.export_pdf', compact('lpk'));
        $pdf->setPaper('a5', 'landscape');
        $raw = 'LPK-'.$lpk->no_reg.'-preview.pdf';
        $filename = $this->sanitizeFilename($raw);
        return $pdf->stream($filename);
    }

    // Download Excel
    public function downloadExcel($id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);
        $export = new \App\Exports\LpkExport($lpk);
        $raw = 'LPK-'.$lpk->no_reg.'-'.date('Ymd').'.xlsx';
        $filename = $this->sanitizeFilename($raw);
        return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
    }

    /**
     * Make a safe filename for Content-Disposition header
     * Removes path separators and percent characters which are disallowed.
     */
    protected function sanitizeFilename(string $name): string
    {
        // Replace path separators and percent sign with a dash
        $safe = str_replace(['/', '\\', '%'], '-', $name);
        // Remove any control characters
        $safe = preg_replace('/[\x00-\x1F\x7F]+/u', '', $safe);
        // Trim whitespace
        $safe = trim($safe);
        // As a last resort, fallback to a simple name
        if ($safe === '') {
            $safe = 'lpk_export_'.date('Ymd');
        }
        return $safe;
    }
}
