<?php

namespace App\Http\Controllers\QC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cmr;
use App\Models\CmrSequence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CmrController extends Controller
{
    public function index()
    {
        $q = request()->query('q');
        $date = request()->query('date');
        $month = request()->query('month');
        $year = request()->query('year');
        $approval_status = request()->query('approval_status');

        $query = Cmr::query();

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
                    if (\Schema::hasColumn('cmrs', 'tgl_terbit_nqr')) {
                        $sub->orWhereDate('tgl_terbit_nqr', $iso);
                    }
                });
            } catch (\Exception $e) {
                $query->whereDate('tgl_terbit_cmr', $date);
            }
        }

        if (!empty($month)) {
            $query->whereMonth('tgl_terbit_cmr', intval($month));
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
                    $query->whereNotNull('requested_at_qc')
                          ->where('secthead_status', 'pending');
                    break;
                case 'waiting_dept':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'pending');
                    break;
                case 'waiting_agm':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('agm_status', 'pending');
                    break;
                case 'waiting_ppc':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('agm_status', 'approved')
                          ->where('ppchead_status', 'pending');
                    break;
                case 'waiting_procurement':
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('agm_status', 'approved')
                          ->where('ppchead_status', 'approved')
                          ->where('procurement_status', 'pending');
                    break;
                case 'waiting_vdd':
                    if (Schema::hasColumn('cmrs', 'vdd_status')) {
                        $query->where('ppchead_status', 'approved')
                              ->where('vdd_status', 'pending');
                    } elseif (Schema::hasColumn('cmrs', 'status_approval')) {
                        // fallback for older schema: match human-readable status text
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
                    $query->where('agm_status', 'rejected');
                    break;
                case 'rejected_ppc':
                    $query->where('ppchead_status', 'rejected');
                    break;
                case 'rejected_procurement':
                    $query->where('procurement_status', 'rejected');
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
                    $query->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('agm_status', 'approved')
                          ->where('ppchead_status', 'approved');

                    // Ensure VDD stage is approved when vdd_status column exists (new workflow)
                    if (Schema::hasColumn('cmrs', 'vdd_status')) {
                        $query->where('vdd_status', 'approved');
                    }

                    // Procurement may be optional (null/empty) or approved
                    $query->where(function($q) {
                        if (Schema::hasColumn('cmrs', 'procurement_status')) {
                            $q->where('procurement_status', 'approved')
                              ->orWhereNull('procurement_status')
                              ->orWhere('procurement_status', '');
                        } else {
                            // fallback to any known 'Completed' textual status if no procurement column
                            $q->where('status_approval', 'Completed')
                              ->orWhere('status_approval', 'LIKE', '%Completed%');
                        }
                    });
                    break;
            }
        }

        $cmrs = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('qc.cmr.index', compact('cmrs'));
    }


    public function create()
    {
        $previewNoReg = $this->previewNextNoReg();
        // provide supplier and part master data for searchable dropdowns
        $suppliers = DB::table('por_supplier')->orderBy('por_nama')->get();
        $items = DB::table('por_item')->select('kode', 'description')->orderBy('kode')->get();
        return view('qc.cmr.create', compact('previewNoReg', 'suppliers', 'items'));
    }

    protected function previewNextNoReg()
    {
        $year = date('Y');
        $month = (int)date('n');

        if (Schema::hasTable('cmr_sequences')) {
            try {
                $seq = CmrSequence::where('year', $year)->first();
                $next = ($seq ? $seq->current : 0) + 1;
                $number = str_pad($next, 4, '0', STR_PAD_LEFT);
                $romanMonth = $this->toRoman($month);
                $yearTwo = sprintf('%02d', $year % 100);
                return sprintf('%s/CMR-4W/%s/%s', $number, $romanMonth, $yearTwo);
            } catch (\Exception $e) {
            }
        }

        $number = str_pad(1, 4, '0', STR_PAD_LEFT);
        $romanMonth = $this->toRoman($month);
        $yearTwo = sprintf('%02d', $year % 100);
        return sprintf('%s/CMR-4W/%s/%s', $number, $romanMonth, $yearTwo);
    }

    public function store(Request $request)
    {
        $noReg = $this->generateNoReg();

        $request->merge(['no_reg' => $noReg, 'order_no' => $request->nomor_po]);
        // If nomor_part provided but nama_part is empty, try to fill nama_part from por_item master
        try {
            if ($request->filled('nomor_part') && !$request->filled('nama_part')) {
                $item = DB::table('por_item')->where('kode', $request->input('nomor_part'))->first();
                if ($item && isset($item->description)) {
                    $request->merge(['nama_part' => $item->description]);
                }
            }
        } catch (\Exception $e) {
            // ignore lookup failures; validation will handle missing fields
        }
        if (!$request->filled('tgl_terbit_cmr') && $request->filled('tgl_terbit_cmr_display')) {
            $iso = $this->parseDmyToIso($request->input('tgl_terbit_cmr_display'));
            if ($iso) $request->merge(['tgl_terbit_cmr' => $iso]);
        }
        if (!$request->filled('tgl_delivery') && $request->filled('tgl_delivery_display')) {
            $iso = $this->parseDmyToIso($request->input('tgl_delivery_display'));
            if ($iso) $request->merge(['tgl_delivery' => $iso]);
        }

        if (!$request->filled('bl_date') && $request->filled('bl_date_display')) {
            $iso = $this->parseDmyToIso($request->input('bl_date_display'));
            if ($iso) $request->merge(['bl_date' => $iso]);
        }
        if (!$request->filled('ar_date') && $request->filled('ar_date_display')) {
            $iso = $this->parseDmyToIso($request->input('ar_date_display'));
            if ($iso) $request->merge(['ar_date' => $iso]);
        }
        if (!$request->filled('found_date') && $request->filled('found_date_display')) {
            $iso = $this->parseDmyToIso($request->input('found_date_display'));
            if ($iso) $request->merge(['found_date' => $iso]);
        }

        try {
            $validated = $request->validate([
            'no_reg' => 'required|string|max:255|unique:cmrs,no_reg',
            'tgl_terbit_cmr' => 'required|date',
            'tgl_delivery' => 'required|date',
            'bl_date' => 'nullable|date',
            'ar_date' => 'nullable|date',
            'found_date' => 'nullable|date',
            'model' => 'nullable|string|max:255',
            'crate_number' => 'nullable|string|max:255',
            'qty_order' => 'nullable|integer|min:0',
            'nama_supplier' => 'required|string|max:255',
            'nama_part' => 'required|string|max:255',
            'nomor_po' => 'required|string|max:255',
            'nomor_part' => 'required|string|max:255',
            'invoice_no' => 'nullable|string|max:255',
            'order_no' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:50',
            'location_claim_occurrence' => 'required|string|max:255',
            'disposition_inventory_type' => 'required|string|max:255',
            'disposition_inventory_choice' => 'required|string|max:255',
            'claim_occurrence_frequency' => 'required|in:First time,Reoccurred,Intermittently,Continuously,Other',
            'dispatch_defective_parts' => 'nullable|in:Dispatch with this report,Dispatch separately',
            'disposition_defect_parts' => 'required|in:Keep to use,Return to KYB,Scrapped at PT.KYB',
            'qty_deliv' => 'nullable|integer|min:0',
            'qty_problem' => 'nullable|integer|min:0',
                'gambar' => 'required|image|max:2048',
            'input_problem' => 'nullable|string|max:75',
            'detail_gambar' => 'nullable|string|max:300',
        ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            try {
                Log::warning('CMR validation failed during store', [
                    'no_reg' => $noReg,
                    'input' => $request->except(['gambar', 'password', '_token']),
                    'has_file_gambar' => $request->hasFile('gambar'),
                    'errors' => $e->errors(),
                ]);
            } catch (\Exception $_) {
                Log::warning('CMR validation failed but logging input failed');
            }
            throw $e;
        }

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('cmr_gambar', 'public');
            $validated['gambar'] = Storage::url($path);
        }

    $validated['nama'] = $validated['nama_supplier'] ?? $validated['nama_part'] ?? ($validated['no_reg'] ?? 'CMR');
    $validated['secthead_status'] = 'pending';
    $validated['depthead_status'] = 'pending';
    $validated['ppchead_status'] = 'pending';

        try {
            Cmr::create($validated);
        } catch (\Exception $e) {
            Log::error('CMR create failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->withErrors(['store' => 'Failed to save CMR.']);
        }

        return redirect()->route('qc.cmr.index')->with('success', 'CMR saved successfully.');
    }

    protected function generateNoReg()
    {
        $year = date('Y');
        $month = (int)date('n');

        if (!Schema::hasTable('cmr_sequences')) {
            $next = 1;
            $number = str_pad($next, 4, '0', STR_PAD_LEFT);
            $romanMonth = $this->toRoman($month);
            $yearTwo = sprintf('%02d', $year % 100);
            return sprintf('%s/CMR-4W/%s/%s', $number, $romanMonth, $yearTwo);
        }

        return DB::transaction(function () use ($year, $month) {
            $seq = CmrSequence::where('year', $year)->lockForUpdate()->first();
            if (!$seq) {
                $seq = CmrSequence::create(['year' => $year, 'current' => 0]);
            }

            $seq->current = $seq->current + 1;
            $seq->save();

            $number = str_pad($seq->current, 4, '0', STR_PAD_LEFT);
            $romanMonth = $this->toRoman($month);
            $yearTwo = sprintf('%02d', $year % 100);
            return sprintf('%s/CMR-4W/%s/%s', $number, $romanMonth, $yearTwo);
        });
    }

     protected function parseDmyToIso(?string $dmy): ?string
    {
        if (!$dmy) return null;
        $dmy = trim($dmy);
        if (preg_match('/^(\d{2})[-\/](\d{2})[-\/](\d{4})$/', $dmy, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }
        return null;
    }

    protected function toRoman($num)
    {
        $map = [1000=>'M',900=>'CM',500=>'D',400=>'CD',100=>'C',90=>'XC',50=>'L',40=>'XL',10=>'X',9=>'IX',5=>'V',4=>'IV',1=>'I'];
        $res = '';
        foreach ($map as $val => $roman) {
            while ($num >= $val) {
                $res .= $roman;
                $num -= $val;
            }
        }
        return $res;
    }

    public function edit($id)
    {
        $cmr = Cmr::findOrFail($id);

        $sect = strtolower($cmr->secthead_status ?? 'pending');
        $dept = strtolower($cmr->depthead_status ?? 'pending');
        $ppc = strtolower($cmr->ppchead_status ?? 'pending');
        $anyRejected = in_array('rejected', [$sect, $dept, $ppc]);
        $allApproved = ($sect === 'approved' && $dept === 'approved' && $ppc === 'approved');

        if ($anyRejected || $allApproved) {
            return redirect()->route('qc.cmr.index')->with('status', 'CMR cannot be edited.');
        }

        // supply master data for dropdowns
        $suppliers = DB::table('por_supplier')->orderBy('por_nama')->get();
        $items = DB::table('por_item')->select('kode', 'description')->orderBy('kode')->get();
        return view('qc.cmr.edit', compact('cmr', 'suppliers', 'items'));
    }

    public function requestApproval(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);
        $sect = strtolower($cmr->secthead_status ?? '');
        $dept = strtolower($cmr->depthead_status ?? '');
        $ppc = strtolower($cmr->ppchead_status ?? '');

        if (in_array($sect, ['approved','rejected']) || in_array($dept, ['approved','rejected']) || in_array($ppc, ['approved','rejected'])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'CMR cannot be requested for approval because it is already in process or completed.'], 400);
            }
            return redirect()->route('qc.cmr.index')->with('status', 'CMR cannot be requested for approval because it is already in process or completed.');
        }

        try {
            $changed = false;

            if (empty($cmr->secthead_status)) { $cmr->secthead_status = 'pending'; $changed = true; }
            if (empty($cmr->depthead_status)) { $cmr->depthead_status = 'pending'; $changed = true; }
            if (empty($cmr->ppchead_status)) { $cmr->ppchead_status = 'pending'; $changed = true; }

            if (Schema::hasColumn('cmrs', 'requested_at_qc')) {
                $cmr->requested_at_qc = \Illuminate\Support\Carbon::now();
                $changed = true;
            }

            if ($changed) {
                $cmr->save();

                $approvers = \App\Models\User::all()->filter(function($u){
                    return $u->hasRole('sect') || $u->hasRole('dept') || $u->hasRole('ppc');
                });

                if ($approvers->count()) {
                    \Illuminate\Support\Facades\Notification::send($approvers, new \App\Notifications\CmrApprovalRequested($cmr));
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to request approval for CMR', ['id' => $cmr->id, 'error' => $e->getMessage()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to send approval request.'], 500);
            }
            return redirect()->back()->with('status', 'Failed to send approval request.');
        }

        // Reload CMR to get fresh status
        $cmr->refresh();

        // Build updated action buttons HTML server-side so frontend can replace the actions cell consistently
        try {
            $actionsHtml = $this->buildActionButtonsHtml($cmr);
        } catch (\Exception $e) {
            Log::error('Failed to build actions HTML for CMR', ['id' => $cmr->id, 'error' => $e->getMessage()]);
            $actionsHtml = null;
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Approval request successfully sent to Sect Head, Dept Head and PPC Head.',
                'new_status' => 'Waiting for Sect Head approval',
                'hide_actions' => true,
                'actions_html' => $actionsHtml
            ]);
        }

        return redirect()->route('qc.cmr.index')->with('success', 'Approval request successfully sent to Sect Head, Dept Head and PPC Head.');
    }

    public function cancelApproval($id)
    {
        $cmr = Cmr::findOrFail($id);
        $sect = strtolower($cmr->secthead_status ?? 'pending');
        $dept = strtolower($cmr->depthead_status ?? 'pending');
        $ppc = strtolower($cmr->ppchead_status ?? 'pending');

        $isSelesai = ($sect === 'approved' && $dept === 'approved' && $ppc === 'approved');
        $hasRejected = in_array('rejected', [$sect, $dept, $ppc]);

        if ($isSelesai || $hasRejected) {
            return redirect()->route('qc.cmr.index')->with('status', 'CMR cannot be canceled because it is already in process or completed.');
        }

        try {
            $changed = false;
            if ($cmr->secthead_status !== 'canceled') { $cmr->secthead_status = 'canceled'; $changed = true; }
            if ($cmr->depthead_status !== 'canceled') { $cmr->depthead_status = 'canceled'; $changed = true; }
            if ($cmr->ppchead_status !== 'canceled') { $cmr->ppchead_status = 'canceled'; $changed = true; }

            if (Schema::hasColumn('cmrs', 'requested_at_qc')) {
                $cmr->requested_at_qc = null;
                $changed = true;
            }

            if ($changed) {
                $cmr->save();
            }
        } catch (\Exception $e) {
            Log::error('Failed to cancel approval for CMR', ['id' => $cmr->id, 'error' => $e->getMessage()]);
            return redirect()->route('qc.cmr.index')->with('status', 'Failed to cancel approval request.');
        }

        return redirect()->route('qc.cmr.index')->with('success', 'CMR canceled successfully.');
    }

    public function previewFpdf($id)
    {
        $cmr = Cmr::findOrFail($id);

        $pdf = new \TCPDF('l', 'mm', 'A4');
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetAutoPageBreak(false, 5);

        $pdf->Cell(50, 5, '様式１', 0, 1);
        $pdf->Cell(50, 5, '(CKD PART ONLY)', 0, 0);
        $pdf->SetFont('cid0jp', '', 12);
        $pdf->SetXY(60, 10);
        $pdf->Cell(110, 15, '', 1, 1, 'C');
        $pdf->SetXY(60, 10);
        $pdf->Cell(110, 7.5, 'Claim Report(CMR)', 0, 1, 'C');
        $pdf->SetXY(60, 17);
        $pdf->Cell(110, 7.5, 'クレーム状況報告書', 0, 1, 'C');

        $pdf->SetFont('cid0jp', '', 8);
        $pdf->SetXY(205, 20);
        $pdf->Cell(40, 5, 'Issue Date (発行日)', 1, 0);
        $formatted_date = \Carbon\Carbon::parse($cmr->tgl_terbit_cmr)->format('M d\' Y');
        $pdf->Cell(40, 5, $formatted_date, 1, 1);

        // $pdf->SetXY(205, 15);
        // $pdf->MultiCell(40, 10, "Company Name\n(拠点名)", 1);
        // $pdf->SetXY(245, 15);
        // $pdf->Cell(40, 10, $cmr->nama_supplier ?? '', 1, 1);

        $pdf->SetXY(150, 23);
        $pdf->Cell(30, 5, '', 0, 0);

    $sectStatus = strtolower($cmr->secthead_status ?? '');
    $sectLabel = '';
    if ($sectStatus === 'approved') $sectLabel = 'Approved';
    elseif (in_array($sectStatus, ['rejected', 'canceled', 'cancel'])) $sectLabel = 'Canceled';

        $sectDate = '';
        if (!empty($cmr->secthead_approved_at)) {
            try {
                $sectDate = \Carbon\Carbon::parse($cmr->secthead_approved_at)->format('d-m-Y');
            } catch (\Exception $_) {
                $sectDate = (string)$cmr->secthead_approved_at;
            }
        }

        $sectApprover = '';
        if (!empty($cmr->secthead_approver_id)) {
            try {
                $approver = \App\Models\User::find($cmr->secthead_approver_id);
                if ($approver) {
                    $sectApprover = $approver->name ?? ($approver->username ?? '');
                }
            } catch (\Throwable $_) {

            }
        }

    $deptStatus = strtolower($cmr->depthead_status ?? '');
    $deptLabel = '';
    if ($deptStatus === 'approved') $deptLabel = 'Approved';
    elseif (in_array($deptStatus, ['rejected', 'canceled', 'cancel'])) $deptLabel = 'Canceled';

        $deptDate = '';
        if (!empty($cmr->depthead_approved_at)) {
            try {
                $deptDate = \Carbon\Carbon::parse($cmr->depthead_approved_at)->format('d-m-Y');
            } catch (\Exception $_) {
                $deptDate = (string)$cmr->depthead_approved_at;
            }
        }

        $deptApprover = '';
        if (!empty($cmr->depthead_approver_id)) {
            try {
                $dapprover = \App\Models\User::find($cmr->depthead_approver_id);
                if ($dapprover) {
                    $deptApprover = $dapprover->name ?? ($dapprover->username ?? '');
                }
            } catch (\Throwable $_) {

            }
        }

        $statusLine = '';
        if ($sectLabel) {
            $statusLine = $sectLabel;
            if ($sectDate) $statusLine .= ' ' . $sectDate;
            if ($sectApprover) $statusLine .= "\n" . $sectApprover;
        }

        $pdf->SetFont('cid0jp', '', 8);
        $boxContent = "KYB\nReceiving";

        $boxX = $pdf->GetX();
        $boxY = $pdf->GetY();
        $boxW = 20;
        $pdf->MultiCell($boxW, 22, $boxContent, 1, 'C');

        if (!empty($sectLabel)) {
            $statusOffset = 8;

            if (stripos($sectLabel, 'approved') !== false) {
                $pdf->SetTextColor(0, 128, 0);
            } elseif (stripos($sectLabel, 'rejected') !== false) {
                $pdf->SetTextColor(220, 20, 60);
            } elseif (stripos($sectLabel, 'canceled') !== false) {
                $pdf->SetTextColor(220, 20, 60);
            } else {
                $pdf->SetTextColor(128, 64, 0);
            }

            $pdf->SetFont('cid0jp', '', 8);
            $pdf->SetXY($boxX, $boxY + $statusOffset);
            $pdf->Cell($boxW, 6, $sectLabel, 0, 1, 'C');

            $pdf->SetTextColor(0, 0, 0);
            if ($sectDate) {
                $pdf->SetXY($boxX, $boxY + $statusOffset + 6);
                $pdf->Cell($boxW, 5, $sectDate, 0, 1, 'C');
            }
            if ($sectApprover) {
                $pdf->SetXY($boxX, $boxY + $statusOffset + 9);
                $pdf->Cell($boxW, 5, $sectApprover, 0, 1, 'C');
            }

            $pdf->SetTextColor(0, 0, 0);
        }

        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetXY(200, 25);
        $pdf->Cell(25, 5, 'Procurement', 1, 0, 'C');
        $pdf->Cell(20, 5, 'PPC', 1, 0, 'C');
        $pdf->Cell(20, 5, 'QA DEPT', 1, 0, 'C');
        $pdf->Cell(20, 5, 'A.G.M', 1, 1, 'C');

        $pdf->SetFont('cid0jp', '', 9);
        $pdf->Cell(100, 5, 'To : Supplier Name (サプライヤ名) : ' . ($cmr->nama_supplier ?? ''), 1, 0);
        $pdf->Cell(70, 5, 'KYB CMR No.            :', 1, 0);
        $pdf->Cell(20, 15, '', 1, 0);
        $pdf->SetFont('cid0jp', '', 9);
        $pdf->Cell(25, 5, 'Approved', 1, 0, 'C');
        $pdf->Cell(20, 5, 'Reviewed', 1, 0, 'C');
        $pdf->Cell(20, 5, 'Written', 1, 0, 'C');
        $pdf->Cell(20, 5, 'Checked', 1, 1, 'C');

        $pdf->SetFont('cid0jp', '', 9);
        $pdf->Cell(100, 5, 'CMR No.(PTKYB CMR No.)            : ' . ($cmr->no_reg ?? ''), 1, 0);
        $formatted_delivery = \Carbon\Carbon::parse($cmr->tgl_delivery)->format('M d\' Y');
        $formatted_bl_date = $cmr->bl_date ? \Carbon\Carbon::parse($cmr->bl_date)->format('M d\' Y') : '';
        $pdf->Cell(70, 5, 'B/L date (船積日)       : ' . $formatted_bl_date, 1, 0);

        $pdf->SetXY(200, 35);
        $pdf->Cell(25, 10, '', 1, 0);
        $pdf->Cell(20, 10, '', 1, 0);
        $pdf->Cell(20, 10, '', 1, 0);
        $pdf->Cell(20, 10, '', 1, 1);

        $deptStatusOffset = 0;

        if (!empty($deptLabel)) {
            $deptBoxX = 245;
            $deptBoxY = 35;
            $deptBoxW = 20;

            $deptStatusOffset = 0;

            if (stripos($deptLabel, 'approved') !== false) {
                $pdf->SetTextColor(0, 128, 0);
            } elseif (stripos($deptLabel, 'rejected') !== false) {
                $pdf->SetTextColor(220, 20, 60);
            } elseif (stripos($deptLabel, 'canceled') !== false) {
                $pdf->SetTextColor(220, 20, 60);
            } else {
                $pdf->SetTextColor(0, 0, 0);
            }

            $pdf->SetFont('cid0jp', '', 6);
            $pdf->SetXY($deptBoxX, $deptBoxY + $deptStatusOffset + 0);
            $pdf->Cell($deptBoxW, 4, $deptLabel, 0, 1, 'C');

            $pdf->SetTextColor(0, 0, 0);
            if ($deptDate) {
                $pdf->SetXY($deptBoxX, $deptBoxY + $deptStatusOffset + 3.5);
                $pdf->Cell($deptBoxW, 3, $deptDate, 0, 1, 'C');
            }
            if ($deptApprover) {
                $pdf->SetXY($deptBoxX, $deptBoxY + $deptStatusOffset + 7);
                $pdf->Cell($deptBoxW, 2, $deptApprover, 0, 1, 'C');
            }

            $pdf->SetTextColor(0, 0, 0);
        }

        // Build Procurement approval label, date and approver name if available
        $procStatus = strtolower($cmr->procurement_status ?? '');
        $procLabel = '';
    if ($procStatus === 'approved') $procLabel = 'Approved';
    elseif ($procStatus === 'rejected') $procLabel = 'Canceled';
    elseif ($procStatus === 'canceled' || $procStatus === 'cancel') $procLabel = 'Canceled';
        $procDate = '';
        if (!empty($cmr->procurement_approved_at)) {
            try {
                $procDate = \Carbon\Carbon::parse($cmr->procurement_approved_at)->format('d-m-Y');
            } catch (\Exception $_) {
                $procDate = (string)$cmr->procurement_approved_at;
            }
        }

        $procApprover = '';
        if (!empty($cmr->procurement_approver_id)) {
            try {
                $uapprover = \App\Models\User::find($cmr->procurement_approver_id);
                if ($uapprover) {
                    $procApprover = $uapprover->name ?? ($uapprover->username ?? '');
                }
            } catch (\Throwable $_) {

            }
        }

        if (!empty($procLabel)) {
            $procBoxX = 200;
            $procBoxY = 35;
            $procBoxW = 25;

            $pdf->SetFont('cid0jp', '', 6);

            if (stripos($procLabel, 'approved') !== false) {
                $pdf->SetTextColor(0, 128, 0);
            } elseif (stripos($procLabel, 'rejected') !== false) {
                $pdf->SetTextColor(220, 20, 60);
            } elseif (stripos($procLabel, 'canceled') !== false) {
                $pdf->SetTextColor(220, 20, 60);
            } else {
                $pdf->SetTextColor(0, 0, 0);
            }

            $statusOffset = -1;
            $pdf->SetXY($procBoxX, $procBoxY + $statusOffset);
            $pdf->Cell($procBoxW, 6, $procLabel, 0, 1, 'C');

            $pdf->SetTextColor(0, 0, 0);
            if ($procDate) {
                $pdf->SetXY($procBoxX, $procBoxY + $statusOffset + 5);
                $pdf->Cell($procBoxW, 2, $procDate, 0, 1, 'C');
            }
            if ($procApprover) {
                $pdf->SetXY($procBoxX, $procBoxY + $statusOffset + 8.5);
                $pdf->Cell($procBoxW, 2, $procApprover, 0, 1, 'C');
            }

            $pdf->SetTextColor(0, 0, 0);
        }

        $ppcStatus = strtolower($cmr->ppchead_status ?? '');
        $ppcLabel = '';
    if ($ppcStatus === 'approved') $ppcLabel = 'Approved';
    elseif ($ppcStatus === 'rejected') $ppcLabel = 'Canceled';
    elseif ($ppcStatus === 'canceled' || $ppcStatus === 'cancel') $ppcLabel = 'Canceled';

        $ppcDate = '';
        if (!empty($cmr->ppchead_approved_at)) {
            try {
                $ppcDate = \Carbon\Carbon::parse($cmr->ppchead_approved_at)->format('d-m-Y');
            } catch (\Exception $_) {
                $ppcDate = (string)$cmr->ppchead_approved_at;
            }
        }

        $ppcApprover = '';
        if (!empty($cmr->ppchead_approver_id)) {
            try {
                $papprover = \App\Models\User::find($cmr->ppchead_approver_id);
                if ($papprover) {
                    $ppcApprover = $papprover->name ?? ($papprover->username ?? '');
                }
            } catch (\Throwable $_) {

            }
        }

        if (!empty($ppcLabel)) {
            $ppcBoxX = 225;
            $ppcBoxY = 35;
            $ppcBoxW = 20;

            $pdf->SetFont('cid0jp', '', 6);

            if (stripos($ppcLabel, 'approved') !== false) {
                $pdf->SetTextColor(0, 128, 0);
            } elseif (stripos($ppcLabel, 'rejected') !== false) {
                $pdf->SetTextColor(220, 20, 60);
            } elseif (stripos($ppcLabel, 'canceled') !== false) {
                $pdf->SetTextColor(128, 64, 0);
            } else {
                $pdf->SetTextColor(0, 0, 0);
            }

            $statusOffset = -1;
            $pdf->SetXY($ppcBoxX, $ppcBoxY + $statusOffset);
            $pdf->Cell($ppcBoxW, 6, $ppcLabel, 0, 1, 'C');

            $pdf->SetTextColor(0, 0, 0);
            if ($ppcDate) {
                $pdf->SetXY($ppcBoxX, $ppcBoxY + $statusOffset + 5);
                $pdf->Cell($ppcBoxW, 2, $ppcDate, 0, 1, 'C');
            }
            if ($ppcApprover) {
                $pdf->SetXY($ppcBoxX, $ppcBoxY + $statusOffset + 8.5);
                $pdf->Cell($ppcBoxW, 2, $ppcApprover, 0, 1, 'C');
            }

            $pdf->SetTextColor(0, 0, 0);
        }

        $agmStatus = strtolower($cmr->agm_status ?? '');
        $agmLabel = '';
        if ($agmStatus === 'approved') $agmLabel = 'Approved';
        elseif ($agmStatus === 'rejected') $agmLabel = 'Canceled';
        elseif ($agmStatus === 'canceled' || $agmStatus === 'cancel') $agmLabel = 'Canceled';

        $agmDate = '';
        if (!empty($cmr->agm_approved_at)) {
            try {
                $agmDate = \Carbon\Carbon::parse($cmr->agm_approved_at)->format('d-m-Y');
            } catch (\Exception $_) {
                $agmDate = (string)$cmr->agm_approved_at;
            }
        }

        $agmApprover = '';
        if (!empty($cmr->agm_approver_id)) {
            try {
                $aapprover = \App\Models\User::find($cmr->agm_approver_id);
                if ($aapprover) {
                    $agmApprover = $aapprover->name ?? ($aapprover->username ?? '');
                }
            } catch (\Throwable $_) {

            }
        }

        if (!empty($agmLabel)) {

            $agmBoxX = 265;
            $agmBoxY = 35;
            $agmBoxW = 20;

            $pdf->SetFont('cid0jp', '', 6);

            if (stripos($agmLabel, 'approved') !== false) {
                $pdf->SetTextColor(0, 128, 0);
            } elseif (stripos($agmLabel, 'rejected') !== false) {
                $pdf->SetTextColor(220, 20, 60);
            } elseif (stripos($agmLabel, 'canceled') !== false) {
                $pdf->SetTextColor(128, 64, 0);
            } else {
                $pdf->SetTextColor(0, 0, 0);
            }

            $statusOffset = -1  ;

            $pdf->SetXY($agmBoxX, $agmBoxY + $statusOffset);
            $pdf->Cell($agmBoxW, 6, $agmLabel, 0, 1, 'C');

            $pdf->SetTextColor(0, 0, 0);
            if ($agmDate) {
                $pdf->SetXY($agmBoxX, $agmBoxY + $statusOffset + 5);
                $pdf->Cell($agmBoxW, 2, $agmDate, 0, 1, 'C');
            }
            if ($agmApprover) {
                $pdf->SetXY($agmBoxX, $agmBoxY + $statusOffset + 8.5);
                $pdf->Cell($agmBoxW, 2, $agmApprover, 0, 1, 'C');
            }

            $pdf->SetTextColor(0, 0, 0);
        }

        $pdf->SetY(40);
    $formatted_found_date = $cmr->found_date ? \Carbon\Carbon::parse($cmr->found_date)->format('M d\' Y') : '';
    // Increase font size for the Found Date / A/R Date fields so they're more readable
    $pdf->SetFont('cid0jp', '', 9);
    $pdf->Cell(100, 5, 'Found Date (発見日)                        : ' . $formatted_found_date, 1, 0);
    $formatted_ar_date = $cmr->ar_date ? \Carbon\Carbon::parse($cmr->ar_date)->format('M d\' Y') : '';
    $pdf->Cell(70, 5, 'A/R Date (到着日)      : ' . $formatted_ar_date, 1, 1);
    // Reset font to previous size used elsewhere (7)
    $pdf->SetFont('cid0jp', '', 7);

        $pdf->SetFont('cid0jp', '', 7);
        $pdf->Cell(50, 10, 'Location claim occur (クレーム発生場所)', 1, 0, 'C');
        $pdf->Cell(75, 10, 'Disposition of inventory (在庫品処理)', 1, 0, 'C');
        $pdf->Cell(45, 10, '', 1, 1, 'C');
        $pdf->SetXY(135, 47.5);
        $pdf->Cell(45, 5, 'Claim occurrence frequency (発生頻度)', 0, 0, 'C');
        $pdf->SetXY(180, 45);
        $pdf->Cell(60, 10, 'Dispatch of defective parts (不良部品の発送)', 1, 0, 'C');
        $pdf->Cell(45, 10, '', 1, 1, 'C');
        $pdf->SetXY(240, 47.5);
        $pdf->SetFont('cid0jp', '', 6);
        $pdf->Cell(45, 5, 'Disposition of Defect parts (不良部品の処分)', 0, 1, 'C');
        $pdf->SetFont('cid0jp', '', 7);

        $checkImgPath = public_path('icon/ceklist.png');
        $hasCheckImg = file_exists($checkImgPath);

        $lco = $cmr->location_claim_occurrence ?? '';
        if ($hasCheckImg) {
            if (stripos($lco, 'receiving') !== false) {
                $pdf->Image($checkImgPath, 11, 63, 5, 5);
            } elseif (stripos($lco, 'process') !== false || stripos($lco, 'in-process') !== false) {
                $pdf->Image($checkImgPath, 11, 73, 5, 5);
            } elseif (stripos($lco, 'customer') !== false) {
                $pdf->Image($checkImgPath, 11, 83, 5, 5);
            }
        }

        $pdf->Cell(50, 52.5, '', 'LRB', 0);
        $pdf->SetXY(10, 63);
        $pdf->SetFont('cid0jp', '', 9);
        $pdf->Cell(50, 5, '□   Receiving Inspect (受入検査)', 0, 0, '');
        $pdf->SetXY(10, 73);
        $pdf->Cell(50, 5, '□   In-Process (工程内)', 0, 0);
        $pdf->SetXY(10, 83);
        $pdf->Cell(50, 5, '□   Customer (客先)', 0, 0);

        $pdf->SetFont('cid0jp', '', 7);

        $doi_type = $cmr->disposition_inventory_type ?? '';
        $doi_choice = $cmr->disposition_inventory_choice ?? '';

        if ($hasCheckImg && stripos($doi_type, 'customer') !== false) {
            if (stripos($doi_choice, 'sorted by customer') !== false) {
                $pdf->Image($checkImgPath, 61, 60, 5, 5);
            } elseif (stripos($doi_choice, 'sorted by pt.kyb') !== false || stripos($doi_choice, 'sorted by kyb') !== false) {
                $pdf->Image($checkImgPath, 61, 70, 5, 5);
            } elseif (stripos($doi_choice, 'keep to use') !== false) {
                $pdf->Image($checkImgPath, 61, 80, 5, 5);
            }
        }

        $pdf->SetXY(60, 55);
        $pdf->Cell(40, 50, '', 1, 0);
        $pdf->SetXY(50, 55);
        $pdf->Cell(50, 5, 'At customer', 0, 0, 'C');
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetXY(60, 60);
        $pdf->Cell(50, 5, '□   Sorted by Customer', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetXY(65, 65);
        $pdf->Cell(50, 5, '(客先による選別)', 0, 1);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetXY(60, 70);
        $pdf->Cell(50, 5, '□   Sorted by PT.KYB', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetXY(65, 75);
        $pdf->Cell(50, 5, '(拠点による選別)', 0, 1);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetXY(60, 80);
        $pdf->Cell(50, 5, '□   Keep to use', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetXY(65, 85);
        $pdf->Cell(50, 5, '(継続使用)', 0, 1);

        // Disposition of inventory - At PT.KYB section
        if ($hasCheckImg && stripos($doi_type, 'pt.kyb') !== false) {
            if (stripos($doi_choice, 'sorted') !== false) {
                $pdf->Image($checkImgPath, 101, 60, 5, 5);
            } elseif (stripos($doi_choice, 'keep') !== false) {
                $pdf->Image($checkImgPath, 101, 70, 5, 5);
            } elseif (stripos($doi_choice, 'return') !== false) {
                $pdf->Image($checkImgPath, 101, 80, 5, 5);
            } elseif (stripos($doi_choice, 'other') !== false) {
                $pdf->Image($checkImgPath, 101, 90, 5, 5);
            }
        }

        $pdf->SetXY(100, 55);
        $pdf->Cell(35, 50, '', 1, 0);
        $pdf->SetXY(95, 55);
        $pdf->Cell(45, 5, 'At PT.KYB', 0, 0, 'C');
        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetXY(100, 60);
        $pdf->Cell(50, 5, '□   Sorted by PT.KYB', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(105);
        $pdf->Cell(50, 5, '(PT.KYBによる選別)', 0, 1);
        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetX(100);
        $pdf->Cell(50, 5, '□   Keep to use', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(105);
        $pdf->Cell(50, 5, '(継続使用)', 0, 1);
        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetX(100);
        $pdf->Cell(50, 5, '□   Return to KYB', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(105);
        $pdf->Cell(50, 5, '(ＫＹＢ返却)', 0, 1);
        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetX(100);
        $pdf->Cell(50, 5, '□   Other', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(105);
        $pdf->Cell(50, 5, '(その他)', 0, 1);

        // Claim occurrence frequency
        $cof = $cmr->claim_occurrence_frequency ?? '';
        if ($hasCheckImg) {
            if (stripos($cof, 'first') !== false) {
                $pdf->Image($checkImgPath, 136, 60, 5, 5);
            } elseif (stripos($cof, 'reoccurred') !== false) {
                $pdf->Image($checkImgPath, 136, 70, 5, 5);
            } elseif (stripos($cof, 'intermittently') !== false) {
                $pdf->Image($checkImgPath, 136, 80, 5, 5);
            } elseif (stripos($cof, 'continuously') !== false) {
                $pdf->Image($checkImgPath, 136, 90, 5, 5);
            } elseif (stripos($cof, 'other') !== false) {
                $pdf->Image($checkImgPath, 136, 100, 5, 5);
            }
        }

        $pdf->SetXY(135, 55);
        $pdf->Cell(45, 50, '', 1, 0);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetXY(135, 60);
        $pdf->Cell(50, 5, '□   First Time', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(140);
        $pdf->Cell(50, 5, '(初回)', 0, 1);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetX(135);
        $pdf->Cell(50, 5, '□   Reoccurred', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(140);
        $pdf->Cell(50, 5, '(再発)', 0, 1);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetX(135);
        $pdf->Cell(50, 5, '□   Intermittently', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(140);
        $pdf->Cell(50, 5, '(断続的)', 0, 1);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetX(135);
        $pdf->Cell(50, 5, '□   Continuously', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(140);
        $pdf->Cell(50, 5, '(継続的)', 0, 1);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetX(135);
        $pdf->Cell(50, 5, '□   Other(その他)', 0, 1);

        // Dispatch of defective parts
        $dispatch = $cmr->dispatch_defective_parts ?? '';
        if ($hasCheckImg) {
            if (stripos($dispatch, 'with this report') !== false || stripos($dispatch, 'with') !== false) {
                $pdf->Image($checkImgPath, 181, 85, 5, 5);
            } elseif (stripos($dispatch, 'separately') !== false) {
                $pdf->Image($checkImgPath, 181, 95, 5, 5);
            }
        }

        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetXY(180, 55);
        $pdf->Cell(60, 50, '', 1, 0);
        $pdf->SetXY(180, 55);
        $pdf->Cell(45, 5, 'In case of rust, mixed parts and', 0, 1);
        $pdf->SetX(180);
        $pdf->Cell(45, 5, 'machining defective claims,', 0, 1);
        $pdf->SetX(180);
        $pdf->Cell(45, 5, 'dispatch of the samples is required to', 0, 1);
        $pdf->SetX(180);
        $pdf->Cell(45, 5, 'investigate at KYB   (n=3 pcs, at least)', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(180);
        $pdf->Cell(45, 5, '(錆、異品、加工不良等のｸﾚｰﾑの場合', 0, 1);
        $pdf->SetX(180);
        $pdf->Cell(45, 5, '現品の送付要。最低３個)', 0, 1);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetX(180);
        $pdf->Cell(45, 5, '□   Dispatch with  this report', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(185);
        $pdf->Cell(45, 5, '(別途送付)', 0, 1);
        $pdf->SetFont('cid0jp', '', 10);
        $pdf->SetX(180);
        $pdf->Cell(45, 5, '□   Dispatch separetely', 0, 1);
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetX(185);
        $pdf->Cell(45, 5, '(別途送付)', 0, 1);

        $dispo = $cmr->disposition_defect_parts ?? '';
        if ($hasCheckImg) {
            if (stripos($dispo, 'keep') !== false) {
                $pdf->Image($checkImgPath, 241, 55, 5, 5);
            } elseif (stripos($dispo, 'return') !== false) {
                $pdf->Image($checkImgPath, 241, 65, 5, 5);
            } elseif (stripos($dispo, 'scrapped') !== false) {
                $pdf->Image($checkImgPath, 241, 75, 5, 5);
            }
        }

        $pdf->SetFont('cid0jp', '', 11);
        $pdf->SetXY(240, 55);
        $pdf->Cell(45, 50, '', 1, 0);
        $pdf->SetX(240);
        $pdf->Cell(45, 5, '□   Keep to use', 0, 1);
        $pdf->SetFont('cid0jp', '', 8);
        $pdf->SetX(245);
        $pdf->Cell(45, 5, '(継続使用)', 0, 1);
        $pdf->SetFont('cid0jp', '', 11);
        $pdf->SetX(240);
        $pdf->Cell(45, 5, '□   Return to KYB', 0, 1);
        $pdf->SetFont('cid0jp', '', 8);
        $pdf->SetX(245);
        $pdf->Cell(45, 5, '(KYB返却)', 0, 1);
        $pdf->SetFont('cid0jp', '', 11);
        $pdf->SetX(240);
        $pdf->Cell(45, 5, '□   Scrapped at PT.KYB', 0, 1);
        $pdf->SetFont('cid0jp', '', 8);
        $pdf->SetX(245);
        $pdf->Cell(45, 5, '(PT.KYBにて廃却)', 0, 1);

        // Table header
        $pdf->SetY(105);
        $pdf->Cell(10, 10, 'NO.', 1, 0, 'C');
        $pdf->SetX(20);
        $pdf->Cell(20, 10, '', 1, 0, 'C');
        $pdf->SetXY(40, 105);
        $pdf->Cell(20, 10, '', 1, 0, 'C');
        $pdf->SetXY(60, 105);
        $pdf->Cell(15, 10, '', 1, 0, 'C');
        $pdf->SetXY(75, 105);
        $pdf->Cell(20, 10, '', 1, 0, 'C');
        $pdf->SetXY(95, 105);
        $pdf->Cell(40, 10, '', 1, 0, 'C');
        $pdf->SetXY(135, 105);
        $pdf->Cell(30, 10, '', 1, 0, 'C');
        $pdf->SetXY(165, 105);
        $pdf->Cell(30, 10, '', 1, 0, 'C');
        $pdf->SetXY(195, 105);
        $pdf->Cell(30, 10, '', 1, 0, 'C');
        $pdf->SetXY(225, 105);
        $pdf->Cell(30, 10, '', 1, 0, 'C');
        $pdf->SetXY(255, 105);
        $pdf->Cell(30, 10, '', 1, 0, 'C');

        $pdf->SetXY(20, 105);
        $pdf->Cell(20, 5, 'Invoice No', 0, 0, 'C');
        $pdf->SetXY(20, 110);
        $pdf->Cell(20, 5, 'インボイスNo.', 0, 0, 'C');
        $pdf->SetXY(40, 105);
        $pdf->Cell(20, 5, 'Order No.', 0, 0, 'C');
        $pdf->SetXY(40, 110);
        $pdf->Cell(20, 5, 'オーダーNo.', 0, 0, 'C');
        $pdf->SetXY(60, 105);
        $pdf->Cell(15, 5, 'Product', 0, 0, 'C');
        $pdf->SetXY(60, 110);
        $pdf->Cell(15, 5, '(製品)', 0, 0, 'C');
        $pdf->SetXY(75, 105);
        $pdf->Cell(20, 5, 'Model', 0, 0, 'C');
        $pdf->SetXY(75, 110);
        $pdf->Cell(20, 5, '(モデル)', 0, 0, 'C');
        $pdf->SetXY(95, 105);
        $pdf->Cell(40, 5, 'Part Name', 0, 0, 'C');
        $pdf->SetXY(95, 110);
        $pdf->Cell(40, 5, '(部品名)', 0, 0, 'C');
        $pdf->SetXY(135, 105);
        $pdf->Cell(30, 5, 'Part Number', 0, 0, 'C');
        $pdf->SetXY(135, 110);
        $pdf->Cell(30, 5, '(部品番号)', 0, 0, 'C');
        $pdf->SetXY(165, 105);
        $pdf->Cell(30, 5, 'Quantity Ordered', 0, 0, 'C');
        $pdf->SetXY(165, 110);
        $pdf->Cell(30, 5, '(オーダー数)', 0, 0, 'C');
        $pdf->SetXY(195, 105);
        $pdf->Cell(30, 5, 'Quantity Delivered', 0, 0, 'C');
        $pdf->SetXY(195, 110);
        $pdf->Cell(30, 5, '(納入数)', 0, 0, 'C');
        $pdf->SetXY(225, 105);
        $pdf->Cell(30, 5, 'Quantity Problem', 0, 0, 'C');
        $pdf->SetXY(225, 110);
        $pdf->Cell(30, 5, '(数量の問題)', 0, 0, 'C');
        $pdf->SetXY(255, 105);
        $pdf->Cell(30, 5, 'Crate Number', 0, 0, 'C');
        $pdf->SetXY(255, 110);
        $pdf->Cell(30, 5, '(ケース番号)', 0, 0, 'C');

        // Table data
        $pdf->SetY(115);
        $pdf->Cell(10, 5, '1', 1, 0, 'C');
        $pdf->SetX(20);
        $pdf->Cell(20, 5, $cmr->invoice_no ?? '', 1, 0, 'C');
        $pdf->SetX(40);
        $pdf->Cell(20, 5, $cmr->order_no ?? '', 1, 0, 'C');
        $pdf->SetX(60);
        $pdf->Cell(15, 5, $cmr->product ?? '', 1, 0, 'C');
        $pdf->SetX(75);
        $pdf->Cell(20, 5, $cmr->model ?? '', 1, 0, 'C');
        $pdf->SetX(95);
        $pdf->Cell(40, 5, $cmr->nama_part ?? '', 1, 0, 'C');
        $pdf->SetX(135);
        $pdf->Cell(30, 5, $cmr->nomor_part ?? '', 1, 0, 'C');
        $pdf->SetX(165);
        $pdf->Cell(30, 5, $cmr->qty_order ?? '', 1, 0, 'C');
        $pdf->SetX(195);
        $pdf->Cell(30, 5, $cmr->qty_deliv ?? '', 1, 0, 'C');
        $pdf->SetX(225);
        $pdf->Cell(30, 5, $cmr->qty_problem ?? '', 1, 0, 'C');
        $pdf->SetX(255);
        $pdf->Cell(30, 5, $cmr->crate_number ?? '', 1, 0, 'C');

        // Empty rows
        $pdf->SetY(120);
        $pdf->Cell(10, 5, '', 1, 0, 'C');
        $pdf->SetX(20);
        $pdf->Cell(20, 5, '', 1, 0, 'C');
        $pdf->SetX(40);
        $pdf->Cell(20, 5, '', 1, 0, 'C');
        $pdf->SetX(60);
        $pdf->Cell(15, 5, '', 1, 0, 'C');
        $pdf->SetX(75);
        $pdf->Cell(20, 5, '', 1, 0, 'C');
        $pdf->SetX(95);
        $pdf->Cell(40, 5, '', 1, 0, 'C');
        $pdf->SetX(135);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetX(165);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetX(195);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetX(225);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetX(255);
        $pdf->Cell(30, 5, '', 1, 0, 'C');

        $pdf->SetY(125);
        $pdf->Cell(10, 5, '', 1, 0, 'C');
        $pdf->SetX(20);
        $pdf->Cell(20, 5, '', 1, 0, 'C');
        $pdf->SetX(40);
        $pdf->Cell(20, 5, '', 1, 0, 'C');
        $pdf->SetX(60);
        $pdf->Cell(15, 5, '', 1, 0, 'C');
        $pdf->SetX(75);
        $pdf->Cell(20, 5, '', 1, 0, 'C');
        $pdf->SetX(95);
        $pdf->Cell(40, 5, '', 1, 0, 'C');
        $pdf->SetX(135);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetX(165);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetX(195);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetX(225);
        $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetX(255);
        $pdf->Cell(30, 5, '', 1, 0, 'C');

        // Disposition section
        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetY(130);
        $pdf->MultiCell(120, 10, "DISPOSITION OF THIS CLAIM (Requirement from PT.KYB.I)\n (本クレームの処理要求)", 1, 'C');
        $boxX = 130;
        $boxY = 130;
        $boxW = 155;
        $boxH = 10;
        $innerPad = 5;
        $shiftRight = 25;

        $pdf->SetXY($boxX, $boxY);
        $pdf->Rect($boxX, $boxY, $boxW, $boxH);

        $pdf->SetFont('cid0jp', '', 9);

        $shiftFirst = 16;
        $pdf->SetXY($boxX + $innerPad + $shiftFirst, $boxY + 1);
        $pdf->Cell($boxW - $innerPad - $shiftFirst, 3, 'Description of the defect', 0, 1, 'L');

        $line2Y = $boxY + 1 + 3;

        $searchWidth = 60;
        $searchX = $boxX + $boxW - $searchWidth - 16;

        $pdf->SetXY($boxX + $innerPad + $shiftRight, $line2Y);

        $leftPortionW = ($searchX - ($boxX + $innerPad + $shiftRight));
        if ($leftPortionW < 20) {
            $leftPortionW = $boxW - $innerPad - $shiftRight - $searchWidth;
        }
        $pdf->Cell($leftPortionW, 5, '(不良状況)', 0, 0, 'L');

        $pdf->SetXY($searchX, $line2Y);
        $pdf->Cell($searchWidth, 5, 'Search of problem', 0, 1, 'C');

        $ppc_data = null;
        $ppc_disposition = '';
        $ppc_currency = $cmr->ppc_currency ?? '';
        $ppc_currency_symbol = $cmr->ppc_currency_symbol ?? '';
        $ppc_nominal = '';
        $ppc_shipping = '';

        if (!empty($cmr->ppchead_note)) {
            try {
                $decoded = json_decode($cmr->ppchead_note, true);
                if (is_array($decoded)) {
                    // helper: recursively search array for a sub-array that contains any of the keys
                    $findArrayWithKeys = function ($arr, $keys) use (&$findArrayWithKeys) {
                        if (!is_array($arr)) return null;
                        foreach ($keys as $k) {
                            if (array_key_exists($k, $arr)) {
                                return $arr;
                            }
                        }
                        foreach ($arr as $v) {
                            if (is_array($v)) {
                                $found = $findArrayWithKeys($v, $keys);
                                if ($found !== null) return $found;
                            }
                        }
                        return null;
                    };

                    // Prefer explicit 'ppc' key when present
                    if (isset($decoded['ppc']) && is_array($decoded['ppc'])) {
                        $ppc_data = $decoded['ppc'];
                    } else {
                        // Find a sub-array that looks like PPC data (has disposition/nominal/shipping/currency)
                        $candidate = $findArrayWithKeys($decoded, ['disposition', 'nominal', 'shipping', 'currency', 'currency_symbol', 'prev_disposition']);
                        if ($candidate !== null) {
                            $ppc_data = $candidate;
                        } else {
                            // fallback to the decoded top-level array
                            $ppc_data = $decoded;
                        }
                    }

                    if ($ppc_data && is_array($ppc_data)) {
                        $ppc_disposition = $ppc_data['disposition'] ?? '';
                        $ppc_nominal = $ppc_data['nominal'] ?? '';
                        $ppc_shipping = $ppc_data['shipping'] ?? '';

                        if (empty($ppc_currency) && isset($ppc_data['currency'])) {
                            $ppc_currency = $ppc_data['currency'];
                        }
                        if (empty($ppc_currency_symbol) && isset($ppc_data['currency_symbol'])) {
                            $ppc_currency_symbol = $ppc_data['currency_symbol'];
                        }
                        // preserve prev_disposition if present in stored ppc_data
                        if (isset($ppc_data['prev_disposition'])) {
                            // leave it in ppc_data for later checks
                        }
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // Disposition details (left side - with dynamic checkmarks and data)
        $pdf->SetY(140);
        $pdf->Cell(120, 65, '', 1, 0);
        $pdf->SetFont('cid0jp', '', 11);
        $pdf->SetX(10);

        // Pay compensation checkbox
        $payCompChecked = ($ppc_disposition === 'pay_compensation');
        $pdf->Cell(45, 5, '□   Pay compensation', 0, 1);
        if ($payCompChecked && file_exists(public_path('icon/ceklist.png'))) {
            $y = $pdf->GetY();
            $pdf->Image(public_path('icon/ceklist.png'), 11, $y - 4.5, 5, 5, 'PNG');
        }

        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetX(20);
        $pdf->Cell(45, 5, 'Items(内訳)', 0, 1);

        // Currency field
        $pdf->SetX(20);
        $currencyLabel = 'Currency: ' . ($ppc_currency ? $ppc_currency : '');
        $pdf->Cell(100, 5, $currencyLabel, 1, 1);

        // Nominal field with currency symbol
        $pdf->SetX(20);
        $nominalFormatted = '';
        $currencySymbol = '';

        if ($ppc_nominal) {
            $nominalFormatted = number_format((float)$ppc_nominal, 0, ',', '.');

            if (!empty($ppc_currency_symbol)) {
                $currencySymbol = $ppc_currency_symbol . ' ';
            } else {
                // Set currency symbol based on selected currency
                switch ($ppc_currency) {
                    case 'IDR':
                        $currencySymbol = 'Rp ';
                        break;
                    case 'USD':
                        $currencySymbol = '$ ';
                        break;
                    case 'JPY':
                        $currencySymbol = '¥ ';
                        break;
                    case 'MYR':
                        $currencySymbol = 'RM ';
                        break;
                    case 'VND':
                        $currencySymbol = '₫ ';
                        break;
                    case 'THB':
                        $currencySymbol = '฿ ';
                        break;
                    case 'KRW':
                        $currencySymbol = '₩ ';
                        break;
                    case 'INR':
                        $currencySymbol = '₹ ';
                        break;
                    case 'CNY':
                        $currencySymbol = '¥ ';
                        break;
                    case 'CUSTOM':
                        $currencySymbol = '';
                        break;
                    default:
                        $currencySymbol = '';
                }
            }
        }

        $nominalText = $nominalFormatted ? $currencySymbol . $nominalFormatted : '';
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->MultiCell(100, 25, $nominalText, 1, 'L');
        $pdf->SetFont('cid0jp', '', 9);

        // Send replacement checkbox
        // Show send-replacement if current disposition is send_replacement,
        // or if a previous PPC disposition indicated send_replacement (preserved by Procurement),
        // or if shipping info exists in the PPC data.
        // Normalize shipping value for checking (case-insensitive compare)
        if (empty($ppc_shipping) && isset($ppc_data['shipping_detail'])) {
            // Sometimes the shipping info may be stored under shipping_detail
            $ppc_shipping = $ppc_data['shipping_detail'];
        }
        // If the DB has a dedicated column for ppc_shipping, prefer it when available
        if (empty($ppc_shipping) && \Illuminate\Support\Facades\Schema::hasColumn('cmrs', 'ppc_shipping')) {
            $ppc_shipping = $cmr->ppc_shipping ?? '';
        }
        // Normalize
        $ppc_shipping = is_string($ppc_shipping) ? strtoupper(trim($ppc_shipping)) : $ppc_shipping;

        $sendReplChecked = (
            $ppc_disposition === 'send_replacement' ||
            (isset($ppc_data['prev_disposition']) && $ppc_data['prev_disposition'] === 'send_replacement') ||
            (!empty($ppc_shipping))
        );
        $pdf->SetFont('cid0jp', '', 11);
        $pdf->SetX(10);
        $pdf->Cell(100, 5, '□   Send the replacement', 0, 1);
        if ($sendReplChecked && file_exists(public_path('icon/ceklist.png'))) {
            $y = $pdf->GetY();
            $pdf->Image(public_path('icon/ceklist.png'), 11, $y - 4.5, 5, 5, 'PNG');
        }

        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetX(20);
        $pdf->Cell(100, 5, '', 0, 1);

        // AIR checkbox
        $airChecked = ($sendReplChecked && is_string($ppc_shipping) && strpos($ppc_shipping, 'AIR') !== false);
        $pdf->SetFont('cid0jp', '', 11);
        $pdf->SetX(20);
        $pdf->Cell(100, 5, '□   AIR (航空便)', 0, 1);
        if ($airChecked && file_exists(public_path('icon/ceklist.png'))) {
            $y = $pdf->GetY();
            $pdf->Image(public_path('icon/ceklist.png'), 21, $y - 4.5, 5, 5, 'PNG');
        }

        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetX(20);
        $pdf->Cell(100, 5, '', 0, 1);

        // SEA checkbox
        $seaChecked = ($sendReplChecked && is_string($ppc_shipping) && strpos($ppc_shipping, 'SEA') !== false);
        $pdf->SetFont('cid0jp', '', 11);
        $pdf->SetX(20);
        $pdf->Cell(100, 5, '□   SEA (船便)', 0, 1);
        if ($seaChecked && file_exists(public_path('icon/ceklist.png'))) {
            $y = $pdf->GetY();
            $pdf->Image(public_path('icon/ceklist.png'), 21, $y - 4.5, 5, 5, 'PNG');
        }

        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetXY(130, 140);
        $pdf->Cell(155, 65, '', 1, 1);
        $pdf->SetXY(135, 140);
        $pdf->Cell(140, 5, 'Part Name                   : ' . ($cmr->nama_part ?? ''), 0, 1);
        $pdf->SetXY(135, 145);
        $pdf->Cell(140, 5, 'Part No.                       : ' . ($cmr->nomor_part ?? ''), 0, 1);
        $pdf->SetXY(135, 150);
        $pdf->Cell(140, 5, 'Product                        : ' . ($cmr->product ?? ''), 0, 1);
        $pdf->SetXY(135, 155);
        $pdf->Cell(140, 5, 'Arrival Date                  : ' . $formatted_ar_date, 0, 1);
        $pdf->SetXY(135, 160);
        $pdf->Cell(140, 5, 'Packing List No            : ' . ($cmr->invoice_no ?? ''), 0, 1);
        $pdf->SetXY(135, 165);
        $pdf->Cell(140, 5, 'Handling Date              : ' . $formatted_date, 0, 1);
        $pdf->SetXY(135, 170);
        $pdf->Cell(140, 5, 'Quantity Delivered       : ' . ($cmr->qty_deliv ?? 0) . ' PCS', 0, 1);
        $pdf->SetXY(135, 175);
        $pdf->Cell(140, 5, 'Quantity Problem         : ' . ($cmr->qty_problem ?? 0) . ' PCS', 0, 1);
        $pdf->SetFont('cid0jp', '', 9);
        $pdf->SetXY(135, 180);

        // Map location claim occurrence
        $location_text = '';
        if (stripos($lco, 'receiving') !== false) {
            $location_text = 'Receiving Inspect';
        } elseif (stripos($lco, 'process') !== false) {
            $location_text = 'In-process';
        } elseif (stripos($lco, 'customer') !== false) {
            $location_text = 'Customer';
        }

        $pdf->Cell(140, 5, 'Location                       : ' . $location_text, 0, 1);
        $pdf->SetFont('cid0jp', '', 9);

        // Display input_problem below Location checkboxes
        $pdf->SetFont('cid0jp', '', 8);
        $pdf->SetXY(135,185);
        $pdf->Cell(40, 4, 'Problem                            :', 0, 0, 'L');
        $pdf->SetXY(169,185);
        $pdf->MultiCell(40, 4, ($cmr->input_problem ?? ''), 0, 'L');

        $imageHeight = 0;
        if (!empty($cmr->gambar)) {
            $imagePath = str_replace('/storage/', '', $cmr->gambar);
            $fullPath = storage_path('app/public/' . $imagePath);

            if (file_exists($fullPath)) {

                $imageInfo = getimagesize($fullPath);

                if ($imageInfo !== false) {
                    $imageWidth = $imageInfo[0];
                    $imageHeight = $imageInfo[1];

                    if ($imageHeight > $imageWidth) {
                        $pdf->Image($fullPath, 235, 142, 35, 0, '', '', '', false, 300, '', false, false, 1);
                    } else {
                        $pdf->Image($fullPath, 215, 142, 50, 0, '', '', '', false, 300, '', false, false, 1);
                    }
                } else {
                    $pdf->Image($fullPath, 215, 142, 50, 0, '', '', '', false, 300, '', false, false, 1);
                }
            }
        }

        // Footer
        $pdf->SetFont('cid0jp', '', 7);
        $pdf->SetXY(130, 198);
        $pdf->Cell(155, 3, 'Corrective & preventive action to be taken by KYB (KYBに要求する是正＆再発防止策)', 1, 1, 'C');

        $no_cmr_sanitized = preg_replace("/[^a-zA-Z0-9]+/", "", $cmr->no_reg);

        return response($pdf->Output('CMR_' . $no_cmr_sanitized . '.pdf', 'I'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="CMR_' . $no_cmr_sanitized . '.pdf"'
        ]);
    }

    public function update(Request $request, $id)
    {
        $cmr = Cmr::findOrFail($id);

        $sect = strtolower($cmr->secthead_status ?? 'pending');
        $dept = strtolower($cmr->depthead_status ?? 'pending');
        $ppc = strtolower($cmr->ppchead_status ?? 'pending');
        $anyRejected = in_array('rejected', [$sect, $dept, $ppc]);
        $allApproved = ($sect === 'approved' && $dept === 'approved' && $ppc === 'approved');

        if ($anyRejected || $allApproved) {
            return redirect()->route('qc.cmr.index')->with('status', 'CMR cannot be updated.');
        }

        $request->merge(['order_no' => $request->nomor_po]);
        // If nomor_part provided but nama_part is empty, try to fill nama_part from por_item master
        try {
            if ($request->filled('nomor_part') && !$request->filled('nama_part')) {
                $item = DB::table('por_item')->where('kode', $request->input('nomor_part'))->first();
                if ($item && isset($item->description)) {
                    $request->merge(['nama_part' => $item->description]);
                }
            }
        } catch (\Exception $e) {
            // ignore lookup failures; validation will handle missing fields
        }

        if (!$request->filled('tgl_terbit_cmr') && $request->filled('tgl_terbit_cmr_display')) {
            $iso = $this->parseDmyToIso($request->input('tgl_terbit_cmr_display'));
            if ($iso) $request->merge(['tgl_terbit_cmr' => $iso]);
        }
        if (!$request->filled('tgl_delivery') && $request->filled('tgl_delivery_display')) {
            $iso = $this->parseDmyToIso($request->input('tgl_delivery_display'));
            if ($iso) $request->merge(['tgl_delivery' => $iso]);
        }

        if (!$request->filled('bl_date') && $request->filled('bl_date_display')) {
            $iso = $this->parseDmyToIso($request->input('bl_date_display'));
            if ($iso) $request->merge(['bl_date' => $iso]);
        }
        if (!$request->filled('ar_date') && $request->filled('ar_date_display')) {
            $iso = $this->parseDmyToIso($request->input('ar_date_display'));
            if ($iso) $request->merge(['ar_date' => $iso]);
        }
        if (!$request->filled('found_date') && $request->filled('found_date_display')) {
            $iso = $this->parseDmyToIso($request->input('found_date_display'));
            if ($iso) $request->merge(['found_date' => $iso]);
        }

        $data = $request->validate([
            'tgl_terbit_cmr' => 'required|date',
            'tgl_delivery' => 'required|date',
            'bl_date' => 'nullable|date',
            'ar_date' => 'nullable|date',
            'found_date' => 'nullable|date',
            'model' => 'nullable|string|max:255',
            'crate_number' => 'nullable|string|max:255',
            'qty_order' => 'nullable|integer|min:0',
            'nama_supplier' => 'required|string|max:255',
            'nama_part' => 'required|string|max:255',
            'nomor_po' => 'required|string|max:255',
            'nomor_part' => 'required|string|max:255',
            'invoice_no' => 'nullable|string|max:255',
            'order_no' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:50',
            'location_claim_occurrence' => 'required|string|max:255',
            'disposition_inventory_type' => 'required|string|max:255',
            'disposition_inventory_choice' => 'required|string|max:255',
            'claim_occurrence_frequency' => 'required|in:First time,Reoccurred,Intermittently,Continuously,Other',
            'dispatch_defective_parts' => 'nullable|in:Dispatch with this report,Dispatch separately',
            'disposition_defect_parts' => 'required|in:Keep to use,Return to KYB,Scrapped at PT.KYB',
            'qty_deliv' => 'nullable|integer|min:0',
            'qty_problem' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|max:2048',
            'input_problem' => 'nullable|string|max:75',
            'detail_gambar' => 'nullable|string|max:300',
        ]);

        if ($request->hasFile('gambar')) {

            if ($cmr->gambar) {
                try {
                    $storedOld = str_replace('/storage/', '', $cmr->gambar);
                    if (Storage::disk('public')->exists($storedOld)) {
                        Storage::disk('public')->delete($storedOld);
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to delete previous CMR image before replace', ['id' => $cmr->id, 'error' => $e->getMessage()]);
                }
            }
            $path = $request->file('gambar')->store('cmr_gambar', 'public');
            $data['gambar'] = Storage::url($path);
        }

        if ($request->input('remove_gambar') && !$request->hasFile('gambar')) {
            if ($cmr->gambar) {
                try {
                    $stored = str_replace('/storage/', '', $cmr->gambar);
                    if (Storage::disk('public')->exists($stored)) {
                        Storage::disk('public')->delete($stored);
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to delete existing CMR image', ['id' => $cmr->id, 'error' => $e->getMessage()]);
                }
            }
            $data['gambar'] = null;
        }

        try {
            $cmr->update($data);
        } catch (\Exception $e) {
            Log::error('CMR update failed', ['id' => $cmr->id, 'error' => $e->getMessage()]);
            return redirect()->back()->withInput()->withErrors(['update' => 'Failed to update CMR.']);
        }

    return redirect()->route('qc.cmr.index')->with('success', 'CMR updated successfully.');
    }

    public function destroy($id)
    {
        $cmr = Cmr::findOrFail($id);

        if ($cmr->requested_at_qc) {
            return redirect()->route('qc.cmr.index')->with('error', 'Cannot delete CMR in approval workflow.');
        }

        try {
            if ($cmr->gambar && Storage::disk('public')->exists(str_replace('/storage/', '', $cmr->gambar))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $cmr->gambar));
            }

            $cmr->delete();
            return redirect()->route('qc.cmr.index')->with('success', 'CMR deleted successfully.');
        } catch (\Exception $e) {
            Log::error('CMR delete failed', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('qc.cmr.index')->with('error', 'Failed to delete CMR.');
        }
    }

    /**
     * Build action buttons HTML server-side (NQR-style) so frontend doesn't depend on a Blade partial.
     */
    protected function buildActionButtonsHtml($cmr)
    {
        $sect = strtolower($cmr->secthead_status ?? 'pending');
        $dept = strtolower($cmr->depthead_status ?? 'pending');
        $agm = strtolower($cmr->agm_status ?? '');
        $ppc = strtolower($cmr->ppchead_status ?? 'pending');
        $proc = strtolower($cmr->procurement_status ?? '');

        $isSelesai = ($sect === 'approved' && $dept === 'approved' && $agm === 'approved' && $ppc === 'approved' && ($proc === 'approved' || empty($proc)));
        $hasRejected = in_array('rejected', [$sect, $dept, $agm, $ppc, $proc]);
        $isCanceled = in_array('canceled', [$sect, $dept, $agm, $ppc, $proc]);
        $locked = $isSelesai || $hasRejected;

        $html = '<div class="flex items-center justify-center gap-1">';

        if ($isCanceled) {
            $html .= '<div class="flex flex-col items-center">'
                . '<button type="button" class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" data-url="' . route('qc.cmr.destroy', $cmr->id) . '" aria-label="Hapus CMR ' . $cmr->no_reg . '" title="Hapus">'
                . '<img src="' . asset('icon/trash.ico') . '" alt="Delete" class="w-4 h-4" />'
                . '</button>'
                . '<span class="text-xs text-gray-500 mt-1">Delete</span></div>';
        } else {
            if (!$locked) {
                if (is_null($cmr->requested_at_qc)) {
                    $tglTerbit = '-';
                    try {
                        $tglTerbit = optional($cmr->tgl_terbit_cmr ?? $cmr->tgl_terbit_nqr)->format('d/m/Y') ?: '-';
                    } catch (\Exception $_) { }

                    $html .= '<div class="flex flex-col items-center">'
                        . '<form id="request-hidden-' . $cmr->id . '" method="POST" action="' . route('qc.cmr.requestApproval', $cmr->id) . '" style="display:none">' . csrf_field() . '</form>'
                        . '<button type="button" data-hidden-form-id="request-hidden-' . $cmr->id . '" data-url="' . route('qc.cmr.requestApproval', $cmr->id) . '" data-cmr-id="' . $cmr->id . '" data-noreg="' . $cmr->no_reg . '" data-tgl-terbit="' . $tglTerbit . '" data-supplier="' . e($cmr->nama_supplier ?? '-') . '" data-nama-part="' . e($cmr->nama_part ?? '-') . '" data-no-part="' . e($cmr->nomor_part ?? '-') . '" data-product="' . e($cmr->product ?? '-') . '" class="open-request-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-blue-50 transition" title="Request Approval for ' . $cmr->no_reg . '">'
                        . '<img src="' . asset('icon/send.ico') . '" alt="Request" class="w-4 h-4" />'
                        . '</button><span class="text-xs text-gray-500 mt-1">Request</span></div>';
                }

                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . route('qc.cmr.edit', $cmr->id) . '" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition" title="Edit CMR ' . $cmr->no_reg . '">'
                    . '<img src="' . asset('icon/edit.ico') . '" alt="Edit" class="w-4 h-4" />'
                    . '</a><span class="text-xs text-gray-500 mt-1">Edit</span></div>';
            }

            $canDelete = is_null($cmr->requested_at_qc) || (!is_null($cmr->requested_at_qc) && ($cmr->secthead_status === 'pending'));
            if ($canDelete) {
                $html .= '<div class="flex flex-col items-center">'
                    . '<button type="button" class="open-delete-modal inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-50 transition" data-url="' . route('qc.cmr.destroy', $cmr->id) . '" aria-label="Delete CMR ' . $cmr->no_reg . '" title="Delete">'
                    . '<img src="' . asset('icon/trash.ico') . '" alt="Delete" class="w-4 h-4" />'
                    . '</button><span class="text-xs text-gray-500 mt-1">Delete</span></div>';
            }

            if (!is_null($cmr->requested_at_qc)) {
                $html .= '<div class="flex flex-col items-center">'
                    . '<a href="' . route('qc.cmr.previewFpdf', $cmr->id) . '" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 transition" title="Preview PDF">'
                    . '<img src="' . asset('icon/pdf.ico') . '" alt="PDF" class="w-4 h-4" />'
                    . '</a><span class="text-xs text-gray-500 mt-1">PDF</span></div>';
            }
        }

        $html .= '</div>';
        return $html;
    }
}
