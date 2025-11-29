<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nqr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NqrStatusChanged;

class NqrController extends Controller
{
    /**
     * Show input form for Procurement to set pay_compensation for NQR
     */
    public function showInputPayCompensation($id)
    {
        $nqr = Nqr::findOrFail($id);
        return view('procurement.nqr.input_pay_compensation', compact('nqr'));
    }

    /**
     * Store pay_compensation and mark NQR approved by Procurement
     */
    public function storePayCompensation(Request $request, $id)
    {
        $nqr = Nqr::findOrFail($id);

        $rules = [
            // Make these fields optional. Procurement can approve without inputting value/currency.
            'pay_compensation_currency' => 'nullable|string',
            'pay_compensation_currency_symbol' => 'nullable|string|max:10',
            'pay_compensation_value' => 'nullable|numeric|min:0.01',
        ];

        $validated = $request->validate($rules);

        $ppc_currency = $request->input('pay_compensation_currency');
        $ppc_symbol = $request->input('pay_compensation_currency_symbol');
        $ppc_value_raw = $request->input('pay_compensation_value');

        // sanitize numeric value and store as string to match DB column type
        $ppc_numeric = is_numeric($ppc_value_raw) ? (float) $ppc_value_raw : null;
        $ppc_value_to_store = $ppc_numeric !== null ? (string) $ppc_numeric : null;

        // derive symbol if not provided
        if (empty($ppc_symbol) && !empty($ppc_currency)) {
            switch (strtoupper($ppc_currency)) {
                case 'IDR':
                    $ppc_symbol = 'Rp';
                    break;
                case 'USD':
                    $ppc_symbol = '$';
                    break;
                case 'JPY':
                    $ppc_symbol = 'JPY';
                    break;
                case 'MYR':
                    $ppc_symbol = 'RM';
                    break;
                case 'VND':
                    $ppc_symbol = 'VND';
                    break;
                case 'THB':
                    $ppc_symbol = 'THB';
                    break;
                case 'KRW':
                    $ppc_symbol = 'KRW';
                    break;
                case 'INR':
                    $ppc_symbol = 'INR';
                    break;
                case 'CNY':
                    $ppc_symbol = 'CNY';
                    break;
                default:
                    // keep whatever symbol was provided (may be null)
                    break;
            }
        }

        $nqr->pay_compensation_currency = $ppc_currency ?: null;
        $nqr->pay_compensation_currency_symbol = $ppc_symbol ?: null;
        $nqr->pay_compensation_value = $ppc_value_to_store;

        // ensure the disposition flag is set so PDF shows the Pay Compensation section
        $nqr->disposition_claim = 'Pay Compensation';

        // mark approved by procurement
        $nqr->status_approval = 'Selesai';
        $nqr->approved_by_procurement = Auth::id();
        $nqr->approved_at_procurement = now();

        $nqr->save();

        try {
            $actorName = Auth::user()->name ?? Auth::id();
            $notification = new NqrStatusChanged($nqr, 'Procurement', 'approved', null, $actorName);
            $recipients = \App\Models\User::whereRaw('LOWER(role) NOT LIKE ? AND LOWER(role) NOT LIKE ?', ['%agm%', '%procurement%'])->get();
            Notification::send($recipients, $notification);
        } catch (\Throwable $e) {
            // ignore notification failures
        }

        return redirect()->route('procurement.nqr.index')->with('success', 'NQR approved by Procurement and compensation saved.');
    }
}
