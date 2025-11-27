<?php

namespace App\Http\Controllers;

use App\Models\Nqr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NqrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nqrs = Nqr::with(['creator', 'updater'])
                   ->orderBy('created_at', 'desc')
                   ->paginate(10);

        return view('nqr.index', compact('nqrs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate nomor registrasi otomatis untuk preview
        $noRegNqr = Nqr::generateNoRegNqr();

        return view('nqr.create', compact('noRegNqr'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data QC
            'tgl_terbit_nqr' => 'required|date',
            'tgl_delivery' => 'required|date',
            'nama_supplier' => 'required|string|max:255',
            'nama_part' => 'required|string|max:255',
            'nomor_po' => 'required|string|max:255',
            'nomor_part' => 'required|string|max:255',
            'status_nqr' => 'required|in:Claim,Complaint',
            'location_claim_occur' => 'required|in:Receiving Insp,In-Process,Customer',
            'disposition_inventory_location' => 'required|in:At Customer,At PT.KYBI',
            'disposition_inventory_action' => 'required|string',
            'claim_occurence_freq' => 'required|in:First Time,Reoccured,Routin',
            'disposition_defect_part' => 'required|in:Keep to Use,Return to Supplier,Scrapped at PT.KYBI',
            'invoice' => 'required|string|max:255',
            'total_del' => 'required|string|max:255',
            'total_claim' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'detail_gambar' => 'nullable|string',

            // Data PPC (optional)
            'disposition_claim' => 'nullable|in:Pay Compensation,Send the Replacement',
            'pay_compensation_value' => 'nullable|string|max:255',
            'send_replacement_method' => 'nullable|in:By Air,By Sea',
        ]);

        // Generate nomor registrasi NQR
        $validated['no_reg_nqr'] = Nqr::generateNoRegNqr();

        // Order otomatis dari Nomor PO
        $validated['order'] = $validated['nomor_po'];

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('nqr_images', $filename, 'public');
            $validated['gambar'] = $path;
        }

        // Set created_by
        $validated['created_by'] = Auth::id();

        $nqr = Nqr::create($validated);

        return redirect()->route('nqr.index')
                        ->with('success', 'NQR berhasil dibuat dengan nomor: ' . $nqr->no_reg_nqr);
    }

    /**
     * Display the specified resource.
     */
    public function show(Nqr $nqr)
    {
        $nqr->load(['creator', 'updater']);
        return view('nqr.show', compact('nqr'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nqr $nqr)
    {
        return view('nqr.edit', compact('nqr'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nqr $nqr)
    {
        $validated = $request->validate([
            // Data QC
            'tgl_terbit_nqr' => 'required|date',
            'tgl_delivery' => 'required|date',
            'nama_supplier' => 'required|string|max:255',
            'nama_part' => 'required|string|max:255',
            'nomor_po' => 'required|string|max:255',
            'nomor_part' => 'required|string|max:255',
            'status_nqr' => 'required|in:Claim,Complaint',
            'location_claim_occur' => 'required|in:Receiving Insp,In-Process,Customer',
            'disposition_inventory_location' => 'required|in:At Customer,At PT.KYBI',
            'disposition_inventory_action' => 'required|string',
            'claim_occurence_freq' => 'required|in:First Time,Reoccured,Routin',
            'disposition_defect_part' => 'required|in:Keep to Use,Return to Supplier,Scrapped at PT.KYBI',
            'invoice' => 'required|string|max:255',
            'total_del' => 'required|string|max:255',
            'total_claim' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'detail_gambar' => 'nullable|string',

            // Data PPC (optional)
            'disposition_claim' => 'nullable|in:Pay Compensation,Send the Replacement',
            'pay_compensation_value' => 'nullable|string|max:255',
            'send_replacement_method' => 'nullable|in:By Air,By Sea',
        ]);

        // Order otomatis dari Nomor PO
        $validated['order'] = $validated['nomor_po'];

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($nqr->gambar) {
                Storage::disk('public')->delete($nqr->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('nqr_images', $filename, 'public');
            $validated['gambar'] = $path;
        }

        // Set updated_by
        $validated['updated_by'] = Auth::id();

        $nqr->update($validated);

        return redirect()->route('nqr.index')
                        ->with('success', 'NQR berhasil diupdate: ' . $nqr->no_reg_nqr);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nqr $nqr)
    {
        // Hapus gambar jika ada
        if ($nqr->gambar) {
            Storage::disk('public')->delete($nqr->gambar);
        }

        $nqr->delete();

        return redirect()->route('nqr.index')
                        ->with('success', 'NQR berhasil dihapus');
    }
}