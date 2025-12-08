<?php

namespace App\Http\Controllers\Depthead;

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

        // Provide list of PPC Head approvers from lembur
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

        return view('depthead.lpk.index', compact('lpks', 'years', 'ppcApprovers'));
    }
    public function create() { return view('depthead.lpk.create'); }
    public function store(Request $r) { return redirect()->route('depthead.lpk.index')->with('status','LPK created'); }
    public function show($id) { abort(404); }
    public function edit($id) { return view('depthead.lpk.edit', compact('id')); }
    public function update(Request $r,$id) { return redirect()->route('depthead.lpk.index')->with('status','LPK updated'); }
    public function destroy($id) { return redirect()->route('depthead.lpk.index')->with('status','LPK deleted'); }

    public function approve(Request $request, $id)
    {
        // Validate optional recipients (NPKs from lembur)
        $request->validate([
            'recipients' => 'nullable|array',
            'recipients.*' => 'string',
        ]);
        $lpk = \App\Models\Lpk::findOrFail($id);
        $isAjax = $request->ajax() || $request->wantsJson();

        // Dept Head hanya bisa approve jika Sect Head sudah approve
        if (strtolower($lpk->secthead_status ?? '') !== 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa approve: menunggu approval Sect Head.'], 400);
            }
            return redirect()->route('depthead.lpk.index')->with('status', 'Cannot approve: waiting for Sect Head approval.');
        }
        $current = strtolower($lpk->depthead_status ?? '');
        if ($current === 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-approve oleh Dept Head.'], 400);
            }
            return redirect()->route('depthead.lpk.index')->with('status', 'LPK already approved by Dept Head.');
        }
        if ($current === 'rejected') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-reject oleh Dept Head; tidak bisa approve.'], 400);
            }
            return redirect()->route('depthead.lpk.index')->with('status', 'LPK already rejected by Dept Head; cannot approve.');
        }

        $lpk->depthead_status = 'approved';
        $lpk->depthead_note = $request->input('note');
        $lpk->depthead_approver_id = auth()->id();
        $lpk->depthead_approved_at = now();
        $lpk->save();

    $actorName = auth()->user()->name ?? auth()->id();
    $notification = new LpkStatusChanged($lpk, 'Dept Head', 'approved', $lpk->depthead_note, $actorName);
    // send to all users about status change
    $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
    Notification::send($recipients, $notification);

    // Optionally send request to PPC Head recipients if provided - email and web notification
    if ($request->has('recipients') && is_array($request->input('recipients'))) {
        $selectedNpks = array_filter($request->input('recipients'));

        if (!empty($selectedNpks)) {
            try {
                // Get PPC Head approvers from lembur (dept=PPC, golongan=4, acting=1)
                $lemburRecipients = DB::connection('lembur')
                    ->table('ct_users_hash')
                    ->whereIn('npk', $selectedNpks)
                    ->where('dept', 'PPC')
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
                        \Illuminate\Support\Facades\Log::warning('Failed to send LPK approval email to PPC Head', [
                            'npk' => $lr->npk,
                            'email' => $lr->user_email,
                            'error' => $mailErr->getMessage()
                        ]);
                    }

                    // Also send web notification to local user (if exists)
                    $localUser = User::where('npk', $lr->npk)->first();
                    if ($localUser) {
                        try {
                            $localUser->notify(new \App\Notifications\LpkApprovalRequested($lpk));
                        } catch (\Throwable $notifErr) {
                            \Illuminate\Support\Facades\Log::warning('Failed to send database notification to PPC Head', [
                                'npk' => $lr->npk,
                                'error' => $notifErr->getMessage()
                            ]);
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to fetch PPC Head recipients from lembur', ['error' => $e->getMessage()]);
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

        return redirect()->route('depthead.lpk.index')->with('status', 'LPK approved.');
    }

    public function reject(Request $request, $id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);
        $isAjax = $request->ajax() || $request->wantsJson();

        // Dept Head hanya bisa reject jika Sect Head sudah approve
        if (strtolower($lpk->secthead_status ?? '') !== 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa reject: menunggu approval Sect Head.'], 400);
            }
            return redirect()->route('depthead.lpk.index')->with('status', 'Cannot reject: waiting for Sect Head approval.');
        }
        $current = strtolower($lpk->depthead_status ?? '');
        if ($current === 'rejected') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-reject oleh Dept Head.'], 400);
            }
            return redirect()->route('depthead.lpk.index')->with('status', 'LPK already rejected by Dept Head.');
        }
        if ($current === 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-approve oleh Dept Head; tidak bisa reject.'], 400);
            }
            return redirect()->route('depthead.lpk.index')->with('status', 'LPK already approved by Dept Head; cannot reject.');
        }

        $lpk->depthead_status = 'rejected';
        $lpk->depthead_note = $request->input('note');
        $lpk->depthead_approver_id = auth()->id();
        $lpk->depthead_approved_at = now();
        $lpk->save();

    $actorName = auth()->user()->name ?? auth()->id();
    $notification = new LpkStatusChanged($lpk, 'Dept Head', 'rejected', $lpk->depthead_note, $actorName);
    // send to all users except AGM and Procurement roles (they should only get CMR notifications)
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

        return redirect()->route('depthead.lpk.index')->with('status', 'LPK rejected.');
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
