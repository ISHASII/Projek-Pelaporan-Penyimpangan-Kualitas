<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lpk;
use App\Models\Nqr;
use App\Models\Cmr;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $rawRole = auth()->user()->role ?? '';
        $r = strtolower(preg_replace('/[\s_\-]/', '', $rawRole));

        if (str_contains($r, 'sect')) $role = 'secthead';
        elseif (str_contains($r, 'dept')) $role = 'depthead';
            elseif (str_contains($r, 'ppc') || str_contains($r, 'ppchead')) $role = 'ppchead';
            elseif (str_contains($r, 'foreman')) $role = 'foreman';
        elseif (str_contains($r, 'vdd')) $role = 'vdd';
        elseif (str_contains($r, 'qc')) $role = 'qc';
        else $role = $r;

        return match ($role) {
            'qc' => $this->qcDashboard(),
            'foreman' => redirect()->route('foreman.dashboard'),
            'secthead' => view('secthead.dashboard', $this->prepareDashboardData()),
            'depthead' => view('depthead.dashboard', $this->prepareDashboardData()),
            'ppchead' => view('ppchead.dashboard', $this->prepareDashboardData()),
            'agm' => view('agm.dashboard', $this->prepareDashboardData()),
            'procurement' => view('procurement.dashboard', $this->prepareDashboardData()),
            'vdd' => view('vdd.dashboard', $this->prepareDashboardData()),
            default => abort(403, 'Role tidak dikenali'),
        };
    }

    private function qcDashboard()
    {
        $data = $this->prepareDashboardData();
        return view('qc.dashboard', $data);
    }

    private function prepareDashboardData(): array
    {
        $lpkStats = [
            'total' => Lpk::count(),
            'approved' => Lpk::where('secthead_status', 'approved')
                        ->where('depthead_status', 'approved')
                        ->where('ppchead_status', 'approved')
                        ->count(),
            'pending' => Lpk::whereNotNull('requested_at_qc')
                        ->where(function($query) {
                            $query->where(function($subQuery) {
                                $subQuery->whereNull('secthead_status')
                                         ->orWhereNull('depthead_status')
                                         ->orWhereNull('ppchead_status')
                                         ->orWhere('secthead_status', '!=', 'approved')
                                         ->orWhere('depthead_status', '!=', 'approved')
                                         ->orWhere('ppchead_status', '!=', 'approved');
                            })
                            ->whereNotIn('secthead_status', ['rejected'])
                            ->whereNotIn('depthead_status', ['rejected'])
                            ->whereNotIn('ppchead_status', ['rejected']);
                        })->count(),
            'rejected' => Lpk::where(function($query) {
                        $query->where('secthead_status', 'rejected')
                              ->orWhere('depthead_status', 'rejected')
                              ->orWhere('ppchead_status', 'rejected');
                    })->count(),
        ];

        $nqrStats = [
            'total' => Nqr::count(),
            'completed' => Nqr::where('status_approval', 'Selesai')->count(),
            'rejected' => Nqr::whereIn('status_approval', [
                        'Ditolak Foreman',
                        'Ditolak Sect Head',
                        'Ditolak Dept Head',
                        'Ditolak PPC Head'
                    ])->count(),
            'pending' => Nqr::whereNotIn('status_approval', [
                        'Selesai',
                        'Ditolak Foreman',
                        'Ditolak Sect Head',
                        'Ditolak Dept Head',
                        'Ditolak PPC Head'
                    ])->orWhereNull('status_approval')->count(),
        ];

        $cmrStats = [
            'total' => Cmr::count(),
            'completed' => Cmr::where('status_approval', 'Completed')->count(),
            'rejected' => Cmr::where(function($query) {
                        $query->where('secthead_status', 'rejected')
                              ->orWhere('depthead_status', 'rejected')
                              ->orWhere('agm_status', 'rejected')
                              ->orWhere('ppchead_status', 'rejected')
                              ->orWhere('procurement_status', 'rejected');
                    })->count(),
            'pending' => Cmr::where(function($query) {
                        $query->whereNull('status_approval')
                              ->orWhere('status_approval', '!=', 'Completed');
                    })
                    ->where(function($query) {
                        $query->whereNull('secthead_status')->orWhere('secthead_status', '!=', 'rejected');
                    })
                    ->where(function($query) {
                        $query->whereNull('depthead_status')->orWhere('depthead_status', '!=', 'rejected');
                    })
                    ->where(function($query) {
                        $query->whereNull('agm_status')->orWhere('agm_status', '!=', 'rejected');
                    })
                    ->where(function($query) {
                        $query->whereNull('ppchead_status')->orWhere('ppchead_status', '!=', 'rejected');
                    })
                    ->where(function($query) {
                        $query->whereNull('procurement_status')->orWhere('procurement_status', '!=', 'rejected');
                    })->count(),
        ];

        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');

            $monthlyData['labels'][] = $month;
            $monthlyData['lpk'][] = Lpk::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
            $monthlyData['nqr'][] = Nqr::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
            $monthlyData['cmr'][] = Cmr::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
        }

        $statusDistribution = [
            'approved' => $lpkStats['approved'] + $nqrStats['completed'] + $cmrStats['completed'],
            'pending' => $lpkStats['pending'] + $nqrStats['pending'] + $cmrStats['pending'],
            'rejected' => $lpkStats['rejected'] + $nqrStats['rejected'] + $cmrStats['rejected'],
        ];

        return compact('lpkStats', 'nqrStats', 'cmrStats', 'monthlyData', 'statusDistribution');
    }

    public function getMonthlyData(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $monthParam = $request->input('month');

        // If a specific month is requested, return per-day counts for that month
        if ($monthParam) {
            $month = (int) $monthParam;
            $lastDay = Carbon::create($year, $month, 1)->daysInMonth;

            $dailyData = ['labels' => [], 'lpk' => [], 'nqr' => [], 'cmr' => []];

            for ($day = 1; $day <= $lastDay; $day++) {
                $dailyData['labels'][] = (string) $day;

                $dailyData['lpk'][] = Lpk::whereDay('created_at', $day)
                                        ->whereMonth('created_at', $month)
                                        ->whereYear('created_at', $year)
                                        ->count();

                $dailyData['nqr'][] = Nqr::whereDay('created_at', $day)
                                        ->whereMonth('created_at', $month)
                                        ->whereYear('created_at', $year)
                                        ->count();

                $dailyData['cmr'][] = Cmr::whereDay('created_at', $day)
                                        ->whereMonth('created_at', $month)
                                        ->whereYear('created_at', $year)
                                        ->count();
            }

            return response()->json($dailyData);
        }
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthName = Carbon::create($year, $m, 1)->format('M Y');

            $monthlyData['labels'][] = $monthName;
            $monthlyData['lpk'][] = Lpk::whereMonth('created_at', $m)
                                    ->whereYear('created_at', $year)
                                    ->count();
            $monthlyData['nqr'][] = Nqr::whereMonth('created_at', $m)
                                    ->whereYear('created_at', $year)
                                    ->count();
            $monthlyData['cmr'][] = Cmr::whereMonth('created_at', $m)
                                    ->whereYear('created_at', $year)
                                    ->count();
        }

        return response()->json($monthlyData);
    }

    // Foreman dashboard that uses the shared prepared dashboard data
    public function foremanDashboard()
    {
        $data = $this->prepareDashboardData();
        return view('foreman.dashboard', $data);
    }

    // VDD dashboard using the shared prepared dashboard data
    public function vddDashboard()
    {
        $data = $this->prepareDashboardData();
        return view('vdd.dashboard', $data);
    }
}
