<?php

namespace App\Http\Controllers\Secthead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nqr;

class NqrController extends Controller
{
    /**
     * Preview NQR PDF (FPDF/FPDI) untuk Sect Head (reuse logic QC)
     */
    public function previewFpdf($id)
    {
        // Reuse logic dari QC\NqrController
        $qcController = app(\App\Http\Controllers\QC\NqrController::class);
        return $qcController->previewFpdf($id);
    }
    public function index(Request $request)
    {
        $query = Nqr::with(['creator', 'updater']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('no_reg_nqr', 'like', "%{$search}%")
                  ->orWhere('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('nama_part', 'like', "%{$search}%")
                  ->orWhere('nomor_po', 'like', "%{$search}%")
                  ->orWhere('nomor_part', 'like', "%{$search}%");
            });
        }

        // Primary: look for canonical 'date' param (hidden ISO or synced)
        $dateParam = null;
        if ($request->filled('date')) {
            $dateParam = $request->date;
        } elseif ($request->filled('date_display')) {
            // Fallback: visible field from the client
            $dateParam = $request->date_display;
        }

        if (!empty($dateParam)) {
            $dateParam = trim($dateParam);
            try {
                // Try d-m-Y first (common display format)
                $date = \Carbon\Carbon::createFromFormat('d-m-Y', $dateParam)->format('Y-m-d');
                $query->whereDate('tgl_terbit_nqr', $date);
            } catch (\Exception $e) {
                try {
                    // Fallback to generic parse (ISO or other)
                    $date = \Carbon\Carbon::parse($dateParam)->format('Y-m-d');
                    $query->whereDate('tgl_terbit_nqr', $date);
                } catch (\Exception $e) {
                    // Invalid date - ignore filter
                }
            }
        }

        if ($request->filled('year')) {
            $query->whereYear('tgl_terbit_nqr', $request->year);
        }

        if ($request->filled('status_nqr')) {
            $query->where('status_nqr', $request->status_nqr);
        }

        if ($request->filled('approval_status')) {
            $status = $request->approval_status;

            switch ($status) {
                case 'menunggu_request':
                    $query->where('status_approval', 'Menunggu Request dikirimkan');
                    break;
                case 'menunggu_foreman':
                    $query->where('status_approval', 'Menunggu Approval Foreman');
                    break;
                case 'menunggu_sect':
                    $query->where('status_approval', 'Menunggu Approval Sect Head');
                    break;
                case 'menunggu_dept':
                    $query->where('status_approval', 'Menunggu Approval Dept Head');
                    break;
                case 'menunggu_ppc':
                    $query->where('status_approval', 'Menunggu Approval PPC Head');
                    break;
                case 'ditolak_foreman':
                    $query->where('status_approval', 'Ditolak Foreman');
                    break;
                case 'ditolak_sect':
                    $query->where('status_approval', 'Ditolak Sect Head');
                    break;
                case 'ditolak_dept':
                    $query->where('status_approval', 'Ditolak Dept Head');
                    break;
                case 'ditolak_ppc':
                    $query->where('status_approval', 'Ditolak PPC Head');
                    break;
                case 'selesai':
                    $query->where('status_approval', 'Selesai');
                    break;
            }
        }

        $nqrs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('secthead.nqr.index', compact('nqrs'));
    }

    public function create()
    {
        return view('secthead.nqr.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('secthead.nqr.index')->with('status', 'NQR created.');
    }

    public function show($id)
    {
        return view('secthead.nqr.show', compact('id'));
    }

    public function edit($id)
    {
        return view('secthead.nqr.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('secthead.nqr.show', $id)->with('status', 'NQR updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('secthead.nqr.index')->with('status', 'NQR deleted.');
    }
}