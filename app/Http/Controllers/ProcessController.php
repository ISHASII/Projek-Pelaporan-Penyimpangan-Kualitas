<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function lpk()
    {
        return view('dashboard.processes.lpk');
    }

    public function nqr()
    {
        return view('dashboard.processes.nqr');
    }

    public function cmr()
    {
        return view('dashboard.processes.cmr');
    }
}
