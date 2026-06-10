<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\KirHistory;
use App\Exports\KendaraanExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function export()
    {
        return Excel::download(new KendaraanExport, 'laporan_monitoring_kir.xlsx');
    }

    public function rekapBiaya(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // Query database to aggregate cost reports grouped by month for the selected year
        $rekap = KirHistory::selectRaw('
                MONTH(tanggal_proses) as bulan, 
                SUM(biaya) as total_biaya, 
                SUM(jasa) as total_jasa, 
                SUM(total) as total_pengeluaran,
                COUNT(id) as total_kendaraan
            ')
            ->whereYear('tanggal_proses', $year)
            ->groupByRaw('MONTH(tanggal_proses)')
            ->orderBy('bulan', 'asc')
            ->get();

        $yearlyTotal = KirHistory::whereYear('tanggal_proses', $year)->sum('total');

        return view('pages.report.rekap-biaya', [
            'title' => 'Rekap Biaya KIR - Tahun ' . $year,
            'rekap' => $rekap,
            'year' => $year,
            'yearlyTotal' => $yearlyTotal,
        ]);
    }
}
