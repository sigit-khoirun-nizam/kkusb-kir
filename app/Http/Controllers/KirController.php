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

        $kendaraans = $query->latest()->paginate(10);

        return view('pages.kir.monitoring', [
            'title' => 'Monitoring KIR',
            'kendaraans' => $kendaraans,
        ]);
    }

    public function proses()
    {
        $kendaraans = Kendaraan::orderBy('nomor_pintu', 'asc')->paginate(9);
        return view('pages.kir.proses', [
            'title' => 'Proses Pembaruan KIR',
            'kendaraans' => $kendaraans,
        ]);
    }

    public function showProsesForm(Kendaraan $kendaraan)
    {
        return view('pages.kir.proses-form', [
            'title' => 'Form Proses KIR - ' . $kendaraan->nopol,
            'kendaraan' => $kendaraan,
        ]);
    }

    public function prosesStore(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'tanggal_proses' => 'required|date',
            'exp_kir_baru' => 'required|date|after:tanggal_proses',
            'biaya' => 'required|numeric|min:0',
            'jasa' => 'required|numeric|min:0',
            'no_pr' => 'nullable|string|max:100',
            'no_spk' => 'nullable|string|max:100',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        $total = $validated['biaya'] + $validated['jasa'];

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

        // Handle file upload if exists
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $path = $file->store('kir_documents', 'public');
            
            KirDocument::create([
                'kendaraan_id' => $kendaraan->id,
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
        $query = KirHistory::with('kendaraan');

        if ($request->has('kendaraan_id') && $request->kendaraan_id) {
            $query->where('kendaraan_id', $request->kendaraan_id);
        }

        $histories = $query->latest()->paginate(15);
        $kendaraans = Kendaraan::orderBy('nomor_pintu', 'asc')->get();

        return view('pages.kir.history', [
            'title' => 'Histori Pembaruan KIR',
            'histories' => $histories,
            'kendaraans' => $kendaraans,
        ]);
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
