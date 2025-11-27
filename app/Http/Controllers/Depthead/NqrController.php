<?php

namespace App\Http\Controllers\Depthead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NqrController extends Controller
{
    /**
     * Preview NQR PDF (FPDF/FPDI) untuk Dept Head (reuse logic QC)
     */
    public function previewFpdf($id)
    {
        $qcController = app(\App\Http\Controllers\QC\NqrController::class);
        return $qcController->previewFpdf($id);
    }
    public function index() { return view('depthead.nqr.index'); }
    public function create() { return view('depthead.nqr.create'); }
    public function store(Request $r) { return redirect()->route('depthead.nqr.index')->with('status','NQR created'); }
    public function show($id) { return view('depthead.nqr.show', compact('id')); }
    public function edit($id) { return view('depthead.nqr.edit', compact('id')); }
    public function update(Request $r,$id) { return redirect()->route('depthead.nqr.show',$id)->with('status','NQR updated'); }
    public function destroy($id) { return redirect()->route('depthead.nqr.index')->with('status','NQR deleted'); }
}
