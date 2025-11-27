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

        return view('depthead.lpk.index', compact('lpks', 'years'));
    }
    public function create() { return view('depthead.lpk.create'); }
    public function store(Request $r) { return redirect()->route('depthead.lpk.index')->with('status','LPK created'); }
    public function show($id) { abort(404); }
    public function edit($id) { return view('depthead.lpk.edit', compact('id')); }
    public function update(Request $r,$id) { return redirect()->route('depthead.lpk.index')->with('status','LPK updated'); }
    public function destroy($id) { return redirect()->route('depthead.lpk.index')->with('status','LPK deleted'); }

    public function approve(Request $request, $id)
    {
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
    // send to all users except AGM and Procurement roles (they should only get CMR notifications)
    $recipients = User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
    Notification::send($recipients, $notification);

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
