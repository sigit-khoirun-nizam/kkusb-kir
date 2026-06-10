<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\KirHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVehicles = Kendaraan::count();
        $activeKIR = Kendaraan::where('exp_kir', '>=', now()->startOfDay())->count();
        $expiredKIR = Kendaraan::where('exp_kir', '<', now()->startOfDay())->count();
        
        $currentMonthKIR = Kendaraan::whereMonth('exp_kir', now()->month)
            ->whereYear('exp_kir', now()->year)
            ->count();

        $threeMonthsKIR = Kendaraan::whereBetween('exp_kir', [now()->startOfDay(), now()->addMonths(3)->endOfDay()])
            ->count();

        $totalCost = KirHistory::sum('total');

        $activeAlerts = Kendaraan::where('exp_kir', '<=', now()->addDays(60))
            ->orderBy('exp_kir', 'asc')
            ->paginate(5);

        return view('pages.dashboard.kir', [
            'title' => 'KIR Dashboard',
            'totalVehicles' => $totalVehicles,
            'activeKIR' => $activeKIR,
            'expiredKIR' => $expiredKIR,
            'currentMonthKIR' => $currentMonthKIR,
            'threeMonthsKIR' => $threeMonthsKIR,
            'totalCost' => number_format($totalCost, 0, ',', '.'),
            'activeAlerts' => $activeAlerts,
        ]);
    }
}
