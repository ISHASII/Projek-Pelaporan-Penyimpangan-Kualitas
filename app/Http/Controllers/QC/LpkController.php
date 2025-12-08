<?php

namespace App\Http\Controllers\QC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lpk;
use App\Models\LpkSequence;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LpkExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\LpkApprovalRequested;

class LpkController extends Controller
{
    public function downloadPdf($id)
    {
        $lpk = Lpk::findOrFail($id);
    $pdf = Pdf::loadView('qc.lpk.export_pdf', compact('lpk'));
    $pdf->setPaper('a5', 'landscape');
    $raw = 'LPK-'.$lpk->no_reg.'-'.date('Ymd').'.pdf';
    $filename = $this->sanitizeFilename($raw);
    return $pdf->download($filename);
    }

    public function previewPdf($id)
    {
        $lpk = Lpk::findOrFail($id);
        $pdf = Pdf::loadView('qc.lpk.export_pdf', compact('lpk'));
        $pdf->setPaper('a5', 'landscape');
        $raw = 'LPK-'.$lpk->no_reg.'-preview.pdf';
        $filename = $this->sanitizeFilename($raw);
        return $pdf->stream($filename);
    }

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
    public function index()
    {
        $q = request()->query('q');
        $date = request()->query('date');
        $month = request()->query('month');
        $year = request()->query('year');
        $approval_status = request()->query('approval_status');
        $status_lpk = request()->query('status_lpk');

        $query = Lpk::query();

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

        if (!empty($date)) {
            // Accept user input in d-m-Y (from Flatpickr) or other parseable formats.
            try {
                if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $date)) {
                    $parsed = Carbon::createFromFormat('d-m-Y', $date);
                } else {
                    $parsed = Carbon::parse($date);
                }

                // Use Y-m-d for whereDate comparison
                $query->whereDate('tgl_terbit', $parsed->toDateString());
            } catch (\Exception $e) {
                // If parsing fails, fall back to raw value. This may still work if DB accepts it.
                $query->whereDate('tgl_terbit', $date);
            }
        }

        if (!empty($month)) {
            $query->whereMonth('tgl_terbit', intval($month));
        }

        if (!empty($year)) {
            $query->whereYear('tgl_terbit', intval($year));
        }

        if (!empty($approval_status)) {
            switch ($approval_status) {
                case 'menunggu_request':
                    $query->whereNull('requested_at_qc');
                    break;
                case 'ditolak_sect':
                    $query->whereNotNull('requested_at_qc')
                          ->where('secthead_status', 'rejected');
                    break;
                case 'ditolak_dept':
                    $query->whereNotNull('requested_at_qc')
                          ->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', 'rejected');
                    break;
                case 'ditolak_ppc':
                    $query->whereNotNull('requested_at_qc')
                          ->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', 'rejected');
                    break;
                case 'menunggu_sect':
                    $query->whereNotNull('requested_at_qc')
                          ->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'pending');
                    break;
                case 'menunggu_dept':
                    $query->whereNotNull('requested_at_qc')
                          ->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'approved')
                          ->where('depthead_status', 'pending');
                    break;
                case 'menunggu_ppc':
                    $query->whereNotNull('requested_at_qc')
                          ->where('secthead_status', '!=', 'rejected')
                          ->where('depthead_status', '!=', 'rejected')
                          ->where('ppchead_status', '!=', 'rejected')
                          ->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('ppchead_status', 'pending');
                    break;
                case 'selesai':
                    $query->whereNotNull('requested_at_qc')
                          ->where('secthead_status', 'approved')
                          ->where('depthead_status', 'approved')
                          ->where('ppchead_status', 'approved');
                    break;
            }
        }

        if (!empty($status_lpk)) {
            if (strtolower($status_lpk) === 'claim') {
                $query->where('status', 'Claim');
            } elseif (strtolower($status_lpk) === 'complaint') {
                $query->where('status', '!=', 'Claim');
            }
        }

        $lpks = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $yearsQuery = Lpk::whereNotNull('tgl_terbit')->selectRaw("strftime('%Y', tgl_terbit) as year");
        } elseif ($driver === 'mysql') {
            $yearsQuery = Lpk::whereNotNull('tgl_terbit')->selectRaw('YEAR(tgl_terbit) as year');
        } elseif ($driver === 'pgsql') {
            $yearsQuery = Lpk::whereNotNull('tgl_terbit')->selectRaw('EXTRACT(YEAR FROM tgl_terbit) as year');
        } else {
            $yearsQuery = Lpk::whereNotNull('tgl_terbit')->selectRaw('YEAR(tgl_terbit) as year');
        }

        $years = $yearsQuery->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->filter()
                    ->map(function($y){ return (int)$y; })
                    ->values();

        // Get available Sect Head approvers from lembur
        // Role mapping: Sect Head = dept=QA, golongan=4, acting=2
        $sectApprovers = collect();
        try {
            $lemburUsers = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('dept', 'QA')
                ->where('golongan', 4)
                ->where('acting', 2)
                ->whereNotNull('user_email')
                ->where('user_email', '!=', '')
                ->get();

            foreach ($lemburUsers as $ext) {
                // Cari local user berdasarkan NPK jika ada
                $localUser = User::where('npk', $ext->npk)->first();
                $sectApprovers->push((object)[
                    'id' => $localUser ? $localUser->id : null,
                    'npk' => $ext->npk,
                    'name' => $ext->full_name,
                    'email' => $ext->user_email,
                    'golongan' => $ext->golongan,
                    'acting' => $ext->acting,
                ]);
            }
        } catch (\Throwable $e) {
            // If lembur is unavailable, fallback to local sect users only
            $sectApprovers = User::whereRaw('LOWER(role) LIKE ?', ['%sect%'])->get()->map(function ($u) {
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

        return view('qc.lpk.index', compact('lpks', 'years', 'sectApprovers'));
    }

    public function create()
    {
        $nextNoReg = $this->previewNextNoReg();
        // Fetch supplier master data for dropdown
        $suppliers = DB::table('por_supplier')->orderBy('por_nama')->get();
        // Fetch part master data for nomor_part -> description mapping
        $items = DB::table('por_item')->select('kode', 'description')->orderBy('kode')->get();
        return view('qc.lpk.create', compact('nextNoReg', 'suppliers', 'items'));
    }

    protected function previewNextNoReg()
    {
        $year = date('Y');
        $month = (int)date('n');

        if (Schema::hasTable('lpk_sequences')) {
            try {
                $seq = LpkSequence::where('year', $year)->first();
                $next = ($seq ? $seq->current : 0) + 1;
                $number = str_pad($next, 4, '0', STR_PAD_LEFT);
                $romanMonth = $this->toRoman($month);
                return sprintf('%s/LPK/%s/%s', $number, $romanMonth, $year);
            } catch (\Exception $e) {

            }
        }

        try {
                if (Schema::hasTable('lpks')) {
                    $latest = Lpk::whereYear('created_at', $year)
                        ->whereNotNull('no_reg')
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($latest && preg_match('/^(\d{4})\/LPK\//', $latest->no_reg, $m)) {
                        $currentNum = (int)$m[1];
                        $next = $currentNum + 1;
                    } else {
                        $next = 1;
                    }

                    $number = str_pad($next, 4, '0', STR_PAD_LEFT);
                    $romanMonth = $this->toRoman($month);
                    return sprintf('%s/LPK/%s/%s', $number, $romanMonth, $year);
                }
            } catch (\Exception $e) {

        }

        $number = str_pad(1, 4, '0', STR_PAD_LEFT);
        $romanMonth = $this->toRoman($month);
        return sprintf('%s/LPK/%s/%s', $number, $romanMonth, $year);
    }

    public function store(Request $request)
    {
        $noReg = $this->generateNoReg();

        $request->merge(['no_reg' => $noReg]);

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

        $validated = $request->validate([
            'no_reg'        => 'required|string|max:255|unique:lpks,no_reg',
            'tgl_terbit'    => 'required|date',
            'tgl_delivery'  => 'required|date',
            'nama_supply'   => 'required|string|max:255',
            'nama_part'     => 'required|string|max:255',
            'nomor_part'    => 'required|string|max:255',
            'nomor_po'      => 'required|string|max:255',
            'status'        => 'required|in:Claim,Informasi,Complaint (Informasi)',
            'jenis_ng'      => 'required|in:Quality,Delivery',
            'kategori'      => 'required|in:Qty Kurang,Subcont Prod,Part Repair,Reject Process,Salah Barang/Label',
            'gambar'        => 'required|image|max:2048',
            'detail_gambar' => 'nullable|string|max:300',
            'problem'       => 'required|string|max:150',
            'total_check'   => 'required|integer|min:0',
            'total_ng'      => 'required|integer|min:0',
            'total_delivery'=> 'required|integer|min:0',
            'total_claim'   => 'required|integer|min:0',
            'perlakuan_terhadap_part' => 'required|in:Sortir Oleh Customer,Sortir Oleh Supplier,Sortir PT KYBI,Part Tetap Dipakai',
            'frekuensi_claim' => 'required|in:Pertama Kali,Berulang Kali atau Rutin',
            'perlakuan_part_defect' => 'required|in:Direpair Supplier,Replace,Dikembalikan ke Supplier,Discrap di PT KYBI,Discrap PT KYBI',
            'lokasi_penemuan_claim' => 'required|string|max:255',
            'customer_pt_name' => 'required_if:lokasi_penemuan_claim,Customer PT|nullable|string|max:255',
            'status_repair' => 'required|in:Bisa Repair,Tidak Repair',
            'referensi_lka' => 'required|string|max:255',
            'tgl_terbit_lka' => 'required|date',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('lpk_gambar', 'public');
            $validated['gambar'] = Storage::url($path);
        }

        if (!empty($validated['perlakuan_terhadap_part'])) {
            $ptp = strtolower(trim($validated['perlakuan_terhadap_part']));
            if (strpos($ptp, 'customer') !== false) {
                $validated['perlakuan_terhadap_part'] = 'Sortir Oleh Customer';
            } elseif (strpos($ptp, 'supplier') !== false) {
                $validated['perlakuan_terhadap_part'] = 'Sortir Oleh Supplier';
            } elseif (strpos($ptp, 'kybi') !== false) {
                $validated['perlakuan_terhadap_part'] = 'Sortir PT KYBI';
            } elseif (strpos($ptp, 'tetap') !== false || strpos($ptp, 'dipakai') !== false) {
                $validated['perlakuan_terhadap_part'] = 'Part Tetap Dipakai';
            }
        }

        if (!empty($validated['frekuensi_claim'])) {
            $fq = strtolower(trim($validated['frekuensi_claim']));
            if (strpos($fq, 'pertama') !== false || strpos($fq, 'sekali') !== false) {
                $validated['frekuensi_claim'] = 'Pertama Kali';
            } elseif (strpos($fq, 'berulang') !== false || strpos($fq, 'rutin') !== false || strpos($fq, 'berkala') !== false) {
                $validated['frekuensi_claim'] = 'Berulang Kali atau Rutin';
            }
        }

        if (!empty($validated['status_repair'])) {
            $sr = strtolower(trim($validated['status_repair']));
            if (strpos($sr, 'bisa') !== false) {
                $validated['status_repair'] = 'Bisa Repair';
            } elseif (strpos($sr, 'tidak') !== false) {
                $validated['status_repair'] = 'Tidak Repair';
            }
        }

        if (!empty($validated['perlakuan_part_defect'])) {
            $pdef = strtolower(trim($validated['perlakuan_part_defect']));
            if (strpos($pdef, 'repair') !== false) {
                $validated['perlakuan_part_defect'] = 'Direpair Supplier';
            } elseif (strpos($pdef, 'replace') !== false) {
                $validated['perlakuan_part_defect'] = 'Replace';
            } elseif (strpos($pdef, 'kembali') !== false || strpos($pdef, 'return') !== false) {
                $validated['perlakuan_part_defect'] = 'Dikembalikan ke Supplier';
            } elseif (strpos($pdef, 'scrap') !== false || strpos($pdef, 'discrap') !== false || strpos($pdef, 'serap') !== false || strpos($pdef, 'discrap pt kybi') !== false) {
                $validated['perlakuan_part_defect'] = 'Discrap di PT KYBI';
            }
        }

        if (empty($validated['percentage']) && !empty($validated['total_check'])) {
            $validated['percentage'] = $validated['total_check'] ? round(($validated['total_ng'] / $validated['total_check']) * 100, 2) : 0;
        }

        if (!empty($validated['lokasi_penemuan_claim'])) {
            $normalized = preg_replace('/[\x{2010}-\x{2015}]+/u', '-', $validated['lokasi_penemuan_claim']);
            $normalized = preg_replace('/\s*-\s*/', '-', $normalized);
            $validated['lokasi_penemuan_claim'] = trim($normalized);
        }

        if (!empty($validated['status'])) {
            $st = strtolower(trim($validated['status']));
            if ($st === 'informasi' || strpos($st, 'complaint') !== false) {
                $validated['status'] = 'Complaint (Informasi)';
            } elseif ($st === 'claim') {
                $validated['status'] = 'Claim';
            }
        }

        Log::debug('LPK store attempt', $validated);

        $validated['depthead_status'] = 'pending';
        $validated['secthead_status'] = 'pending';
        $validated['ppchead_status'] = 'pending';

        try {
            Lpk::create($validated);
        } catch (\Exception $e) {
            Log::error('LPK create failed', ['error' => $e->getMessage(), 'data' => $validated]);
            return redirect()->back()->withInput()->withErrors(['store' => 'Failed to save LPK. Check server log for details.']);
        }

        return redirect()->route('qc.lpk.index')->with('success', 'Data LPK berhasil disimpan');
    }

    protected function generateNoReg()
    {
        $year = date('Y');
        $month = (int)date('n');

        if (!Schema::hasTable('lpk_sequences')) {
            $next = 1;
            $number = str_pad($next, 4, '0', STR_PAD_LEFT);
            $romanMonth = $this->toRoman($month);
            return sprintf('%s/LPK/%s/%s', $number, $romanMonth, $year);
        }

        return DB::transaction(function () use ($year, $month) {

            $seq = LpkSequence::where('year', $year)->lockForUpdate()->first();
            if (!$seq) {
                $seq = LpkSequence::create(['year' => $year, 'current' => 0]);
            }

            $seq->current = $seq->current + 1;
            $seq->save();

            $number = str_pad($seq->current, 4, '0', STR_PAD_LEFT);
            $romanMonth = $this->toRoman($month);
            return sprintf('%s/LPK/%s/%s', $number, $romanMonth, $year);
        });
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

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        $lpk = Lpk::findOrFail($id);

        $sect = strtolower($lpk->secthead_status ?? 'pending');
        $dept = strtolower($lpk->depthead_status ?? 'pending');
        $ppc = strtolower($lpk->ppchead_status ?? 'pending');
        $anyRejected = in_array('rejected', [$sect, $dept, $ppc]);
        $allApproved = ($sect === 'approved' && $dept === 'approved' && $ppc === 'approved');
        if ($anyRejected || $allApproved) {
            return redirect()->route('qc.lpk.index')->with('status', 'LPK cannot be edited because it has already entered the approval workflow.');
    }

        $suppliers = DB::table('por_supplier')->orderBy('por_nama')->get();
        $items = DB::table('por_item')->select('kode', 'description')->orderBy('kode')->get();
        return view('qc.lpk.edit', compact('lpk', 'suppliers', 'items'));
    }

    public function update(Request $request, $id)
    {
        $lpk = Lpk::findOrFail($id);

        $sect = strtolower($lpk->secthead_status ?? 'pending');
        $dept = strtolower($lpk->depthead_status ?? 'pending');
        $ppc = strtolower($lpk->ppchead_status ?? 'pending');
        $anyRejected = in_array('rejected', [$sect, $dept, $ppc]);
        $allApproved = ($sect === 'approved' && $dept === 'approved' && $ppc === 'approved');

        if ($anyRejected || $allApproved) {
            return redirect()->route('qc.lpk.index')->with('status', 'LPK cannot be updated because it is under or past approval.');
        }

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

    $data = $request->validate([
            'no_reg' => 'sometimes|string|max:255',
            'tgl_terbit' => 'required|date',
            'tgl_delivery' => 'required|date',
            'nama_supply' => 'required|string|max:255',
            'nama_part' => 'required|string|max:255',
            'nomor_part' => 'required|string|max:255',
            'nomor_po' => 'required|string|max:255',
            'status' => 'required|in:Claim,Informasi,Complaint (Informasi)',
            'jenis_ng' => 'required|in:Quality,Delivery',
            'kategori' => 'required|in:Qty Kurang,Subcont Prod,Part Repair,Reject Process,Salah Barang/Label',
            'gambar' => 'nullable|image|max:2048',
            'detail_gambar' => 'nullable|string|max:300',
            'problem' => 'required|string|max:150',
            'total_check' => 'required|integer|min:0',
            'total_ng' => 'required|integer|min:0',
            'total_delivery' => 'required|integer|min:0',
            'total_claim' => 'required|integer|min:0',
            'percentage' => 'nullable|numeric|min:0',
            'perlakuan_terhadap_part' => 'required|in:Sortir Oleh Customer,Sortir Oleh Supplier,Sortir PT KYBI,Part Tetap Dipakai',
            'frekuensi_claim' => 'required|in:Pertama Kali,Berulang Kali atau Rutin',
            'perlakuan_part_defect' => 'required|in:Direpair Supplier,Replace,Dikembalikan ke Supplier,Discrap di PT KYBI,Discrap PT KYBI',
            'lokasi_penemuan_claim' => 'required|string|max:255',
            'customer_pt_name' => 'required_if:lokasi_penemuan_claim,Customer PT|nullable|string|max:255',
            'status_repair' => 'required|in:Bisa Repair,Tidak Repair',
            'referensi_lka' => 'nullable|string|max:255',
            'tgl_terbit_lka' => 'nullable|date',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('lpk_gambar', 'public');
            $data['gambar'] = Storage::url($path);
        }

        if (empty($data['percentage']) && !empty($data['total_check']) && isset($data['total_ng'])) {
            $data['percentage'] = $data['total_check'] ? round(($data['total_ng'] / $data['total_check']) * 100, 2) : 0;
        }

        if (!empty($data['perlakuan_terhadap_part'])) {
            $ptp = strtolower(trim($data['perlakuan_terhadap_part']));
            if (strpos($ptp, 'customer') !== false) {
                $data['perlakuan_terhadap_part'] = 'Sortir Oleh Customer';
            } elseif (strpos($ptp, 'supplier') !== false) {
                $data['perlakuan_terhadap_part'] = 'Sortir Oleh Supplier';
            } elseif (strpos($ptp, 'kybi') !== false) {
                $data['perlakuan_terhadap_part'] = 'Sortir PT KYBI';
            } elseif (strpos($ptp, 'tetap') !== false || strpos($ptp, 'dipakai') !== false) {
                $data['perlakuan_terhadap_part'] = 'Part Tetap Dipakai';
            }
        }

        if (!empty($data['frekuensi_claim'])) {
            $fq = strtolower(trim($data['frekuensi_claim']));
            if (strpos($fq, 'pertama') !== false || strpos($fq, 'sekali') !== false) {
                $data['frekuensi_claim'] = 'Pertama Kali';
            } elseif (strpos($fq, 'berulang') !== false || strpos($fq, 'rutin') !== false || strpos($fq, 'berkala') !== false) {
                $data['frekuensi_claim'] = 'Berulang Kali atau Rutin';
            }
        }

        if (!empty($data['status_repair'])) {
            $sr = strtolower(trim($data['status_repair']));
            if (strpos($sr, 'bisa') !== false) {
                $data['status_repair'] = 'Bisa Repair';
            } elseif (strpos($sr, 'tidak') !== false) {
                $data['status_repair'] = 'Tidak Repair';
            }
        }

        if (!empty($data['perlakuan_part_defect'])) {
            $pdef = strtolower(trim($data['perlakuan_part_defect']));
            if (strpos($pdef, 'repair') !== false) {
                $data['perlakuan_part_defect'] = 'Direpair Supplier';
            } elseif (strpos($pdef, 'replace') !== false) {
                $data['perlakuan_part_defect'] = 'Replace';
            } elseif (strpos($pdef, 'kembali') !== false || strpos($pdef, 'return') !== false) {
                $data['perlakuan_part_defect'] = 'Dikembalikan ke Supplier';
            } elseif (strpos($pdef, 'scrap') !== false || strpos($pdef, 'discrap') !== false || strpos($pdef, 'serap') !== false || strpos($pdef, 'discrap pt kybi') !== false) {
                $data['perlakuan_part_defect'] = 'Discrap di PT KYBI';
            }
        }

        if (!empty($data['lokasi_penemuan_claim'])) {
            $normalized = preg_replace('/[\x{2010}-\x{2015}]+/u', '-', $data['lokasi_penemuan_claim']);
            $normalized = preg_replace('/\s*-\s*/', '-', $normalized);
            $data['lokasi_penemuan_claim'] = trim($normalized);
        }

        Log::info('LPK update request', ['id' => $lpk->id, 'request_all' => $request->all(), 'validated' => $data]);

        try {
            $lpk->fill($data);
            $wasDirty = $lpk->isDirty();
            $saved = $lpk->save();

            if (! $saved) {
                Log::error('LPK update save returned false', ['id' => $lpk->id, 'data' => $data]);
                return redirect()->back()->withInput()->withErrors(['update' => 'Gagal menyimpan perubahan LPK. Periksa log server.']);
            }

            if (! $wasDirty) {
                return redirect()->route('qc.lpk.index')->with('status', 'Tidak ada perubahan yang terdeteksi.');
            }
        } catch (\Exception $e) {
            Log::error('LPK update failed', ['id' => $lpk->id, 'error' => $e->getMessage(), 'data' => $data]);
            return redirect()->back()->withInput()->withErrors(['update' => 'Failed to update LPK: ' . $e->getMessage()]);
        }

        return redirect()->route('qc.lpk.index')->with('status', 'LPK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lpk = Lpk::find($id);
        if (! $lpk) {
            return redirect()->route('qc.lpk.index')->with('status', 'LPK not found.');
        }

        if (!is_null($lpk->requested_at_qc) && strtolower($lpk->secthead_status ?? '') !== 'pending') {
            return redirect()->route('qc.lpk.index')->with('status', 'LPK tidak dapat dihapus karena sudah diproses oleh Sect Head.');
        }

        if (strtolower($lpk->secthead_status ?? '') === 'approved' || strtolower($lpk->secthead_status ?? '') === 'rejected' ||
            strtolower($lpk->depthead_status ?? '') === 'approved' || strtolower($lpk->depthead_status ?? '') === 'rejected' ||
            strtolower($lpk->ppchead_status ?? '') === 'approved' || strtolower($lpk->ppchead_status ?? '') === 'rejected') {
            return redirect()->route('qc.lpk.index')->with('status', 'LPK tidak dapat dihapus karena sudah ada keputusan approval.');
        }
        try {
            if ($lpk->gambar) {
                $storagePath = null;
                if (strpos($lpk->gambar, '/storage/') === 0) {
                    $storagePath = substr($lpk->gambar, strlen('/storage/'));
                } elseif (strpos($lpk->gambar, Storage::url('')) === 0) {
                    $storagePath = str_replace(Storage::url(''), '', $lpk->gambar);
                }

                if ($storagePath && Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                }
            }

            $lpk->delete();
        } catch (\Exception $e) {
            Log::error('LPK delete failed', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('qc.lpk.index')->with('status', 'Gagal menghapus LPK.');
        }

        return redirect()->route('qc.lpk.index')->with('status', 'LPK deleted.');
    }

    public function requestApproval($id)
    {
        $lpk = Lpk::findOrFail($id);
        $isAjax = request()->ajax() || request()->wantsJson();

        // Validate optional recipients list (user ids)
        request()->validate([
            'recipients' => 'nullable|array',
            'recipients.*' => 'string', // NPK from lembur
        ]);

        $sect = strtolower($lpk->secthead_status ?? '');
        $dept = strtolower($lpk->depthead_status ?? '');
        $ppc = strtolower($lpk->ppchead_status ?? '');

        if (in_array($sect, ['approved','rejected']) || in_array($dept, ['approved','rejected']) || in_array($ppc, ['approved','rejected'])) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'LPK tidak dapat diminta persetujuannya karena sudah dalam proses atau selesai.'], 400);
            }
            return redirect()->route('qc.lpk.index')->with('status', 'LPK tidak dapat diminta persetujuannya karena sudah dalam proses atau selesai.');
        }

        try {
            $changed = false;
            if (empty($lpk->secthead_status)) { $lpk->secthead_status = 'pending'; $changed = true; }
            if (empty($lpk->depthead_status)) { $lpk->depthead_status = 'pending'; $changed = true; }
            if (empty($lpk->ppchead_status)) { $lpk->ppchead_status = 'pending'; $changed = true; }

            if (Schema::hasColumn('lpks', 'requested_at_qc')) {
                $lpk->requested_at_qc = Carbon::now();
                $changed = true;
            }

            if ($changed) {
                $lpk->save();

                // Accept optional recipients[] array (NPKs from lembur) to select which approver(s) should receive the request.
                $req = request();
                $selectedNpks = [];
                if ($req->has('recipients') && is_array($req->input('recipients'))) {
                    $selectedNpks = array_filter($req->input('recipients'));
                }

                // Get emails from lembur based on selected NPKs
                $emailsToNotify = collect();
                try {
                    if (!empty($selectedNpks)) {
                        // Get selected Sect Head approvers from lembur
                        $lemburRecipients = DB::connection('lembur')
                            ->table('ct_users_hash')
                            ->whereIn('npk', $selectedNpks)
                            ->where('dept', 'QA')
                            ->where('golongan', 4)
                            ->where('acting', 2)
                            ->whereNotNull('user_email')
                            ->where('user_email', '!=', '')
                            ->get();

                        foreach ($lemburRecipients as $lr) {
                            $emailsToNotify->push((object)[
                                'npk' => $lr->npk,
                                'name' => $lr->full_name,
                                'email' => $lr->user_email,
                            ]);
                        }
                    } else {
                        // Fallback: get all Sect Head QA approvers
                        $lemburRecipients = DB::connection('lembur')
                            ->table('ct_users_hash')
                            ->where('dept', 'QA')
                            ->where('golongan', 4)
                            ->where('acting', 2)
                            ->whereNotNull('user_email')
                            ->where('user_email', '!=', '')
                            ->get();

                        foreach ($lemburRecipients as $lr) {
                            $emailsToNotify->push((object)[
                                'npk' => $lr->npk,
                                'name' => $lr->full_name,
                                'email' => $lr->user_email,
                            ]);
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to fetch recipients from lembur', ['error' => $e->getMessage()]);
                }

                // Send notification via Mail directly using lembur emails AND save to database notifications
                if ($emailsToNotify->count()) {
                    foreach ($emailsToNotify as $recipient) {
                        // Send email
                        try {
                            \Illuminate\Support\Facades\Mail::send(
                                'emails.lpk_approval_requested',
                                ['lpk' => $lpk, 'recipientName' => $recipient->name],
                                function ($message) use ($recipient, $lpk) {
                                    $message->to($recipient->email, $recipient->name)
                                            ->subject('Permintaan Persetujuan LPK: ' . $lpk->no_reg);
                                }
                            );
                        } catch (\Throwable $mailErr) {
                            Log::warning('Failed to send LPK approval email', [
                                'npk' => $recipient->npk,
                                'email' => $recipient->email,
                                'error' => $mailErr->getMessage()
                            ]);
                        }

                        // Also send notification to local user (if exists) for web notification
                        $localUser = User::where('npk', $recipient->npk)->first();
                        if ($localUser) {
                            try {
                                $localUser->notify(new LpkApprovalRequested($lpk));
                            } catch (\Throwable $notifErr) {
                                Log::warning('Failed to send database notification', [
                                    'npk' => $recipient->npk,
                                    'user_id' => $localUser->id,
                                    'error' => $notifErr->getMessage()
                                ]);
                            }
                        } else {
                            // If no local user, create a database notification entry manually
                            try {
                                DB::table('notifications')->insert([
                                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                                    'type' => LpkApprovalRequested::class,
                                    'notifiable_type' => User::class,
                                    'notifiable_id' => 0, // System notification
                                    'data' => json_encode([
                                        'lpk_id' => $lpk->id,
                                        'no_reg' => $lpk->no_reg,
                                        'message' => 'Permintaan persetujuan LPK ' . $lpk->no_reg,
                                        'recipient_npk' => $recipient->npk,
                                        'recipient_name' => $recipient->name,
                                        'recipient_email' => $recipient->email,
                                    ]),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            } catch (\Throwable $e) {
                                // Ignore if notifications table issue
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to request approval for LPK', ['id' => $lpk->id, 'error' => $e->getMessage()]);
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Gagal mengirim permintaan persetujuan.'], 500);
            }
            return redirect()->route('qc.lpk.index')->with('status', 'Gagal mengirim permintaan persetujuan.');
        }

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => 'Permintaan persetujuan berhasil dikirim ke Sect Head, Dept Head dan PPC Head.',
                'lpk' => [
                    'id' => $lpk->id,
                    'secthead_status' => $lpk->secthead_status,
                    'depthead_status' => $lpk->depthead_status,
                    'ppchead_status' => $lpk->ppchead_status,
                    'requested_at_qc' => $lpk->requested_at_qc,
                ]
            ]);
        }

        return redirect()->route('qc.lpk.index')->with('success', 'Permintaan persetujuan berhasil dikirim ke Sect Head, Dept Head dan PPC Head.');
    }

    public function cancelApproval($id)
    {
        $lpk = Lpk::findOrFail($id);

        $sect = strtolower($lpk->secthead_status ?? 'pending');
        $dept = strtolower($lpk->depthead_status ?? 'pending');
        $ppc = strtolower($lpk->ppchead_status ?? 'pending');

        $isSelesai = ($sect === 'approved' && $dept === 'approved' && $ppc === 'approved');
        $hasRejected = in_array('rejected', [$sect, $dept, $ppc]);

        if ($isSelesai || $hasRejected) {
            return redirect()->route('qc.lpk.index')->with('status', 'LPK tidak dapat dibatalkan karena sudah dalam proses atau selesai.');
        }

        try {
            $changed = false;
            if ($lpk->secthead_status !== 'canceled') { $lpk->secthead_status = 'canceled'; $changed = true; }
            if ($lpk->depthead_status !== 'canceled') { $lpk->depthead_status = 'canceled'; $changed = true; }
            if ($lpk->ppchead_status !== 'canceled') { $lpk->ppchead_status = 'canceled'; $changed = true; }

            if (Schema::hasColumn('lpks', 'requested_at_qc')) {
                $lpk->requested_at_qc = null;
                $changed = true;
            }

            if ($changed) {
                $lpk->save();
            }
        } catch (\Exception $e) {
            Log::error('Failed to cancel approval for LPK', ['id' => $lpk->id, 'error' => $e->getMessage()]);
            return redirect()->route('qc.lpk.index')->with('status', 'Gagal membatalkan permintaan persetujuan.');
        }

        return redirect()->route('qc.lpk.index')->with('success', 'LPK berhasil dibatalkan.');
    }
}
