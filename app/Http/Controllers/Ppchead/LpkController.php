<?php

namespace App\Http\Controllers\Ppchead;

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

        return view('ppchead.lpk.index', compact('lpks', 'years'));
    }
    public function create() { return view('ppchead.lpk.create'); }
    public function store(Request $r) { return redirect()->route('ppchead.lpk.index')->with('status','LPK created'); }
    public function show($id) { abort(404); }
    public function edit($id) { return view('ppchead.lpk.edit', compact('id')); }
    public function update(Request $r,$id) { return redirect()->route('ppchead.lpk.index')->with('status','LPK updated'); }
    public function destroy($id) { return redirect()->route('ppchead.lpk.index')->with('status','LPK deleted'); }

    public function approve(Request $request, $id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);
        $isAjax = $request->ajax() || $request->wantsJson();

        // Block if prior step rejected
        if (strtolower($lpk->depthead_status ?? '') === 'rejected') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa approve: LPK sudah di-reject oleh Dept Head.'], 400);
            }
            return redirect()->route('ppchead.lpk.index')->with('status', 'Cannot approve: LPK has been rejected by Dept Head.');
        }
        if (($lpk->depthead_status ?? '') !== 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa approve: menunggu approval Dept Head.'], 400);
            }
            return redirect()->route('ppchead.lpk.index')->with('status', 'Cannot approve: waiting for Dept Head approval.');
        }

        // ensure PPC inputs were filled before approving
        if (empty($lpk->ppc_perlakuan_terhadap_part) || empty($lpk->ppc_perlakuan_terhadap_claim)) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Lengkapi PPC Input terlebih dahulu.', 'redirect' => route('ppchead.lpk.ppcForm', $id)], 400);
            }
            // Redirect user to the PPC form so they can complete inputs before approving
            return redirect()->route('ppchead.lpk.ppcForm', $id)->with('status', 'Lengkapi PPC Input terlebih dahulu.');
        }

        $current = strtolower($lpk->ppchead_status ?? '');
        if ($current === 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-approve oleh PPC Head.'], 400);
            }
            return redirect()->route('ppchead.lpk.index')->with('status', 'LPK already approved by PPC Head.');
        }
        if ($current === 'rejected') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-reject oleh PPC Head; tidak bisa approve.'], 400);
            }
            return redirect()->route('ppchead.lpk.index')->with('status', 'LPK already rejected by PPC Head; cannot approve.');
        }

    $lpk->ppchead_status = 'approved';
    $lpk->ppchead_note = $request->input('note');
    $lpk->ppchead_approver_id = auth()->id();
    $lpk->ppchead_approved_at = now();
    $lpk->save();

    $actorName = auth()->user()->name ?? auth()->id();
    $notification = new LpkStatusChanged($lpk, 'PPC Head', 'approved', $lpk->ppchead_note, $actorName);
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

    return redirect()->route('ppchead.lpk.index')->with('status', 'LPK approved.');
    }

    public function reject(Request $request, $id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);
        $isAjax = $request->ajax() || $request->wantsJson();

        // Block if prior step rejected
        if (strtolower($lpk->depthead_status ?? '') === 'rejected') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa reject: LPK sudah di-reject oleh Dept Head.'], 400);
            }
            return redirect()->route('ppchead.lpk.index')->with('status', 'Cannot reject: LPK has been rejected by Dept Head.');
        }
        if (($lpk->depthead_status ?? '') !== 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa reject: menunggu approval Dept Head.'], 400);
            }
            return redirect()->route('ppchead.lpk.index')->with('status', 'Cannot reject: waiting for Dept Head approval.');
        }

        $current = strtolower($lpk->ppchead_status ?? '');
        if ($current === 'rejected') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-reject oleh PPC Head.'], 400);
            }
            return redirect()->route('ppchead.lpk.index')->with('status', 'LPK already rejected by PPC Head.');
        }
        if ($current === 'approved') {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK sudah di-approve oleh PPC Head; tidak bisa reject.'], 400);
            }
            return redirect()->route('ppchead.lpk.index')->with('status', 'LPK already approved by PPC Head; cannot reject.');
        }

    $lpk->ppchead_status = 'rejected';
    $lpk->ppchead_note = $request->input('note');
    $lpk->ppchead_approver_id = auth()->id();
    $lpk->ppchead_approved_at = now();
    $lpk->save();

    $actorName = auth()->user()->name ?? auth()->id();
    $notification = new LpkStatusChanged($lpk, 'PPC Head', 'rejected', $lpk->ppchead_note, $actorName);
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

    return redirect()->route('ppchead.lpk.index')->with('status', 'LPK rejected.');
    }

    /**
     * Show the PPC Head input form (must be completed before approval)
     */
    public function showPpcForm($id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);
        return view('ppchead.lpk.ppc_form', compact('lpk'));
    }

    /**
     * Store PPC Head input data
     */
    public function storePpcForm(Request $request, $id)
    {
        $lpk = \App\Models\Lpk::findOrFail($id);

        $data = $request->validate([
            'ppc_perlakuan_terhadap_part' => 'required|string|max:255',
            'ppc_perlakuan_terhadap_claim' => 'required|string|max:255',
        ]);

        $lpk->fill($data);
        $lpk->save();
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data PPC Head berhasil disimpan. Anda sekarang dapat menyetujui LPK.',
                'lpk' => [ 'id' => $lpk->id, 'ppc_perlakuan_terhadap_part' => $lpk->ppc_perlakuan_terhadap_part, 'ppc_perlakuan_terhadap_claim' => $lpk->ppc_perlakuan_terhadap_claim ]
            ]);
        }

        return redirect()->route('ppchead.lpk.index')->with('status', 'Data PPC Head berhasil disimpan. Anda sekarang dapat menyetujui LPK.');
    }
}
