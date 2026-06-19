<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\KirHistory;
use App\Models\KirDocument;
use App\Imports\KendaraanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class KirController extends Controller
{
    public function monitoring(Request $request)
    {
        $query = Kendaraan::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nopol', 'like', "%{$search}%")
                  ->orWhere('nomor_pintu', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status) {
            $status = $request->status;
            if ($status == 'aman') {
                $query->where('exp_kir', '>', now()->addDays(60));
            } elseif ($status == 'warning') {
                $query->whereBetween('exp_kir', [now()->addDays(31)->startOfDay(), now()->addDays(60)->endOfDay()]);
            } elseif ($status == 'urgent') {
                $query->where('exp_kir', '<=', now()->addDays(30));
            }
        }

        $kendaraans = $query->with('documents')->latest()->paginate(10)->withQueryString();

        return view('pages.kir.monitoring', [
            'title' => 'Monitoring KIR',
            'kendaraans' => $kendaraans,
        ]);
    }

    public function proses(Request $request)
    {
        $query = Kendaraan::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nopol', 'like', "%{$search}%")
                  ->orWhere('nomor_pintu', 'like', "%{$search}%");
            });
        }

        $kendaraans = $query->orderBy('nomor_pintu', 'asc')->paginate(9)->withQueryString();

        return view('pages.kir.proses', [
            'title' => 'Proses Pembaruan KIR',
            'kendaraans' => $kendaraans,
        ]);
    }

    public function showProsesForm(Kendaraan $kendaraan)
    {
        $additionalFeeTypes = \App\Models\AdditionalFeeType::active()->orderBy('name', 'asc')->get();

        return view('pages.kir.proses-form', [
            'title' => 'Form Proses KIR - ' . $kendaraan->nopol,
            'kendaraan' => $kendaraan,
            'additionalFeeTypes' => $additionalFeeTypes,
        ]);
    }

    public function prosesStore(Request $request, Kendaraan $kendaraan)
    {
        if ($request->has('biaya')) {
            $request->merge([
                'biaya' => str_replace('.', '', $request->biaya)
            ]);
        }
        if ($request->has('jasa')) {
            $request->merge([
                'jasa' => str_replace('.', '', $request->jasa)
            ]);
        }
        if ($request->has('additional_fees') && is_array($request->additional_fees)) {
            $cleanedFees = [];
            foreach ($request->additional_fees as $key => $item) {
                $typeId = $item['type_id'] ?? null;
                $amount = $item['amount'] ?? null;
                if ($typeId) {
                    $cleanedFees[] = [
                        'type_id' => $typeId,
                        'amount' => ($amount !== null && $amount !== '') ? str_replace('.', '', $amount) : '0',
                    ];
                }
            }
            $request->merge([
                'additional_fees' => $cleanedFees
            ]);
        }

        $validated = $request->validate([
            'tanggal_proses' => 'required|date',
            'exp_kir_baru' => 'required|date|after:tanggal_proses',
            'biaya' => 'required|numeric|min:0',
            'jasa' => 'required|numeric|min:0',
            'no_pr' => 'nullable|string|max:100',
            'no_spk' => 'nullable|string|max:100',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
            'additional_fees' => 'nullable|array',
            'additional_fees.*.type_id' => 'required|exists:additional_fee_types,id',
            'additional_fees.*.amount' => 'required|numeric|min:0',
        ]);

        $additionalFeesTotal = 0;
        $additionalFeesData = [];
        if (!empty($validated['additional_fees'])) {
            foreach ($validated['additional_fees'] as $item) {
                $id = $item['type_id'];
                $amount = (float) $item['amount'];
                if ($amount > 0) {
                    $additionalFeesTotal += $amount;
                    $additionalFeesData[$id] = ($additionalFeesData[$id] ?? 0) + $amount;
                }
            }
        }

        $total = $validated['biaya'] + $validated['jasa'] + $additionalFeesTotal;

        // Save History Record
        $history = KirHistory::create([
            'kendaraan_id' => $kendaraan->id,
            'exp_kir_lama' => $kendaraan->exp_kir,
            'exp_kir_baru' => $validated['exp_kir_baru'],
            'biaya' => $validated['biaya'],
            'jasa' => $validated['jasa'],
            'total' => $total,
            'no_pr' => $validated['no_pr'],
            'no_spk' => $validated['no_spk'],
            'tanggal_proses' => $validated['tanggal_proses'],
        ]);

        // Save Additional Fees
        foreach ($additionalFeesData as $id => $amount) {
            \App\Models\KirHistoryAdditionalFee::create([
                'kir_history_id' => $history->id,
                'additional_fee_type_id' => $id,
                'amount' => $amount,
            ]);
        }

        // Handle file upload if exists
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $path = $file->store('kir_documents', 'public');
            
            KirDocument::create([
                'kendaraan_id' => $kendaraan->id,
                'kir_history_id' => $history->id,
                'nama_file' => $file->getClientOriginalName(),
                'path' => $path,
            ]);
        }

        // Update Kendaraan Table
        $kendaraan->update([
            'exp_kir' => $validated['exp_kir_baru'],
            'biaya' => $validated['biaya'],
            'jasa' => $validated['jasa'],
            'total' => $total,
            'no_pr' => $validated['no_pr'],
            'no_spk' => $validated['no_spk'],
            'status' => 'aktif', // reset to active since renewed
        ]);

        return redirect()->route('kir.monitoring')->with('success', 'KIR kendaraan ' . $kendaraan->nopol . ' berhasil diperbarui!');
    }

    public function history(Request $request)
    {
        $query = KirHistory::with(['kendaraan.documents', 'document', 'additionalFees.feeType']);

        if ($request->has('kendaraan_id') && $request->kendaraan_id) {
            $query->where('kendaraan_id', $request->kendaraan_id);
        }

        $histories = $query->latest()->paginate(10)->withQueryString();
        $kendaraans = Kendaraan::orderBy('nomor_pintu', 'asc')->get();

        return view('pages.kir.history', [
            'title' => 'Histori Pembaruan KIR',
            'histories' => $histories,
            'kendaraans' => $kendaraans,
        ]);
    }

    public function printHistory(KirHistory $history)
    {
        $history->load(['kendaraan', 'additionalFees.feeType']);
        
        // Reset TCPDF instance to ensure a clean slate
        \Elibyy\TCPDF\Facades\TCPDF::reset();
        
        // Set Document Information
        \Elibyy\TCPDF\Facades\TCPDF::SetCreator('KKUSB-KIR');
        \Elibyy\TCPDF\Facades\TCPDF::SetAuthor('KKUSB');
        \Elibyy\TCPDF\Facades\TCPDF::SetTitle('Bukti Pembaruan KIR');
        \Elibyy\TCPDF\Facades\TCPDF::SetSubject('Bukti Pembaruan KIR');
        
        // Remove Default Header/Footer
        \Elibyy\TCPDF\Facades\TCPDF::setPrintHeader(false);
        \Elibyy\TCPDF\Facades\TCPDF::setPrintFooter(false);
        
        // Set margins (15mm top/bottom, 15mm left/right)
        \Elibyy\TCPDF\Facades\TCPDF::SetMargins(15, 15, 15);
        
        // Enable Auto Page Break (with 10mm margin from bottom)
        \Elibyy\TCPDF\Facades\TCPDF::SetAutoPageBreak(true, 10);
        
        // Add page
        \Elibyy\TCPDF\Facades\TCPDF::AddPage('P', 'A4');
        
        // Render HTML view
        $html = view('pages.kir.print-history-pdf', compact('history'))->render();
        
        // Output HTML to PDF
        \Elibyy\TCPDF\Facades\TCPDF::writeHTML($html, true, false, true, false, '');
        
        // Output PDF to browser directly
        $pdfContent = \Elibyy\TCPDF\Facades\TCPDF::Output('kir_receipt_' . $history->id . '.pdf', 'S');
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="kir_receipt_' . $history->id . '.pdf"');
    }

    public function importForm()
    {
        return view('pages.kir.import', [
            'title' => 'Import Data Excel KIR',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new KendaraanImport, $request->file('excel_file'));
            return redirect()->route('kir.monitoring')->with('success', 'Data KIR berhasil diimport dari Excel!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('error', 'Validasi gagal: ' . implode('; ', $errorMessages));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
