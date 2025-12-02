<?php

namespace App\Http\Controllers\Vdd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nqr;

class NqrController extends Controller
{
    public function showInputPayCompensation($id)
    {
        $nqr = Nqr::findOrFail($id);
        // Set form action to VDD approve route (which will accept PPC fields)
        $formAction = route('vdd.nqr.approve', $nqr->id);
        $backRoute = route('vdd.nqr.index');
        $previewRoute = route('vdd.nqr.previewFpdf', $nqr->id);
        $roleLabel = 'VDD';
        return view('vdd.nqr.input_pay_compensation', compact('nqr', 'formAction', 'backRoute', 'previewRoute', 'roleLabel'));
    }
}
