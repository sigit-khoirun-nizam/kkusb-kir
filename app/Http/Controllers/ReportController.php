<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\KirHistory;
use App\Exports\KendaraanExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function export(Request $request)
    {
        if (!$request->has('format')) {
            return view('pages.report.export', [
                'title' => 'Export Laporan KIR',
            ]);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Parse date format m/d/Y (from UI) or Y-m-d (from tests) to Y-m-d for database query
        if ($startDate) {
            try {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $startDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $startDate = \Carbon\Carbon::parse($startDate)->format('Y-m-d');
            }
        }

        if ($endDate) {
            try {
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $endDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $endDate = \Carbon\Carbon::parse($endDate)->format('Y-m-d');
            }
        }

        if ($request->input('format') === 'excel') {
            return Excel::download(new \App\Exports\KirHistoryExport($startDate, $endDate), 'laporan_histori_kir_' . now()->format('Ymd') . '.xlsx');
        }

        if ($request->input('format') === 'pdf') {
            $query = KirHistory::with(['kendaraan', 'additionalFees.feeType']);

            if ($startDate) {
                $query->whereDate('tanggal_proses', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('tanggal_proses', '<=', $endDate);
            }

            $histories = $query->orderBy('tanggal_proses', 'asc')->get();

            // Generate PDF via TCPDF
            \Elibyy\TCPDF\Facades\TCPDF::reset();
            \Elibyy\TCPDF\Facades\TCPDF::SetCreator('KKUSB-KIR');
            \Elibyy\TCPDF\Facades\TCPDF::SetAuthor('KKUSB');
            \Elibyy\TCPDF\Facades\TCPDF::SetTitle('Laporan Histori KIR');
            
            \Elibyy\TCPDF\Facades\TCPDF::setPrintHeader(false);
            \Elibyy\TCPDF\Facades\TCPDF::setPrintFooter(false);
            
            \Elibyy\TCPDF\Facades\TCPDF::SetMargins(10, 15, 10);
            \Elibyy\TCPDF\Facades\TCPDF::SetAutoPageBreak(true, 10);
            
            // Add page in Landscape orientation
            \Elibyy\TCPDF\Facades\TCPDF::AddPage('L', 'A4');
            
            $html = view('pages.report.print-pdf', compact('histories', 'startDate', 'endDate'))->render();
            
            \Elibyy\TCPDF\Facades\TCPDF::writeHTML($html, true, false, true, false, '');
            
            $pdfContent = \Elibyy\TCPDF\Facades\TCPDF::Output('laporan_histori_kir.pdf', 'S');
            
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="laporan_histori_kir_' . now()->format('Ymd') . '.pdf"');
        }

        return redirect()->back();
    }

    public function rekapBiaya(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // Query database to aggregate cost reports grouped by month for the selected year
        $rekap = KirHistory::selectRaw('
                MONTH(tanggal_proses) as bulan, 
                SUM(biaya) as total_biaya, 
                SUM(jasa) as total_jasa, 
                SUM(total - biaya - jasa) as total_tambahan,
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
