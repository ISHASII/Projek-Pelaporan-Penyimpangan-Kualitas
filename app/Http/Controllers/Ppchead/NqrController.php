<?php

namespace App\Http\Controllers\Ppchead;

use App\Http\Controllers\Controller;
use App\Models\Nqr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NqrController extends Controller
{
    /**
     * Preview NQR as PDF (FPDF) for PPC Head
     */
    public function previewFpdf($id)
    {
        // Reuse logic from QC if possible, or call a service
        // For now, just call the QC controller's previewFpdf if available
        $qcController = app(\App\Http\Controllers\QC\NqrController::class);
        return $qcController->previewFpdf($id);
    }
    /**
     * Display a listing of NQR for PPC to review and add disposition
     */
    public function index(Request $request)
    {
        $query = Nqr::with(['creator', 'updater']);

        // Search by various fields
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('no_reg_nqr', 'like', "%{$search}%")
                  ->orWhere('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('nama_part', 'like', "%{$search}%")
                  ->orWhere('nomor_po', 'like', "%{$search}%");
            });
        }

        // Filter by date (format dd-mm-yyyy)
        if ($request->filled('date')) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
                $query->whereDate('tgl_terbit_nqr', $date);
            } catch (\Exception $e) {
                // Invalid date format, skip filter
            }
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('tgl_terbit_nqr', $request->year);
        }

        // Filter by status NQR
        if ($request->filled('status_nqr')) {
            // DB enum may have been migrated to 'Complaint (Informasi)'.
            // Accept old 'Complaint' value from UI and map to current enum.
            $status = $request->status_nqr;
            if ($status === 'Complaint') {
                $status = 'Complaint (Informasi)';
            }
            $query->where('status_nqr', $status);
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $statusMap = [
                'menunggu_request' => 'Menunggu request dikirimkan',
                'menunggu_foreman' => 'Menunggu Approval Foreman',
                'menunggu_sect' => 'Menunggu Approval Sect Head',
                'menunggu_dept' => 'Menunggu Approval Dept Head',
                'menunggu_ppc' => 'Menunggu Approval PPC Head',
                'ditolak_foreman' => 'Ditolak Foreman',
                'ditolak_sect' => 'Ditolak Sect Head',
                'ditolak_dept' => 'Ditolak Dept Head',
                'ditolak_ppc' => 'Ditolak PPC Head',
                'selesai' => 'Selesai',
            ];
            if (isset($statusMap[$request->approval_status])) {
                $query->where('status_approval', $statusMap[$request->approval_status]);
            }
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('ppchead.nqr.index', compact('nqrs'));
    }

    /**
     * Show the form for editing PPC data
     */
    public function edit(Nqr $nqr)
    {
        return view('ppchead.nqr.ppc_form', compact('nqr'));
    }

    /**
     * Update PPC data only
     */
    public function update(Request $request, Nqr $nqr)
    {
        $validated = $request->validate([
            'disposition_claim' => 'required|in:Pay Compensation,Send the Replacement',
            'pay_compensation_value' => [
                'nullable', 'string', 'max:255',
                function($attribute, $value, $fail) use ($request) {
                    if ($request->disposition_claim === 'Pay Compensation' && empty($value)) {
                        $fail('Nilai Pay Compensation wajib diisi jika memilih Pay Compensation.');
                    }
                }
            ],
            'pay_compensation_currency' => [
                'nullable', 'string', 'max:10',
                function($attribute, $value, $fail) use ($request) {
                    if ($request->disposition_claim === 'Pay Compensation' && empty($value)) {
                        $fail('Mata Uang wajib dipilih jika memilih Pay Compensation.');
                    }
                }
            ],
            'pay_compensation_currency_symbol' => [
                'nullable', 'string', 'max:10',
                function($attribute, $value, $fail) use ($request) {
                    if ($request->disposition_claim === 'Pay Compensation' && $request->pay_compensation_currency === 'CUSTOM' && empty($value)) {
                        $fail('Simbol Mata Uang wajib diisi jika memilih Custom.');
                    }
                }
            ],
            'send_replacement_method' => 'nullable|in:By Air,By Sea',
        ]);

        // Set updated_by
        $validated['updated_by'] = Auth::id();

        // Format: simpan ke DB sebagai angka (strip titik/koma)
        if (!empty($validated['pay_compensation_value'])) {
            // Hanya ambil angka dan koma, lalu ganti koma jadi titik jika ada
            $raw = preg_replace('/[^\d,]/', '', $validated['pay_compensation_value']);
            // Jika ada koma, ganti jadi titik (untuk desimal)
            $raw = str_replace(',', '.', $raw);
            $validated['pay_compensation_value'] = $raw;
        }

        // Kosongkan field yang tidak relevan
        if ($validated['disposition_claim'] === 'Pay Compensation') {
            $validated['send_replacement_method'] = null;
        } elseif ($validated['disposition_claim'] === 'Send the Replacement') {
            $validated['pay_compensation_value'] = null;
            $validated['pay_compensation_currency'] = null;
            $validated['pay_compensation_currency_symbol'] = null;
        }

        $nqr->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data PPC berhasil diupdate untuk NQR: ' . $nqr->no_reg_nqr,
            ]);
        }

        return redirect()->route('ppchead.nqr.index')
                        ->with('success', 'Data PPC berhasil diupdate untuk NQR: ' . $nqr->no_reg_nqr);
    }

    /**
     * Display the specified NQR
     */
    public function show(Nqr $nqr)
    {
        $nqr->load(['creator', 'updater']);
        return view('ppchead.nqr.show', compact('nqr'));
    }
}
