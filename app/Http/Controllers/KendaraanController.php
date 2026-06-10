<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Exports\KendaraanTemplateExport;
use App\Imports\KendaraanImport;
use Maatwebsite\Excel\Facades\Excel;

class KendaraanController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new KendaraanTemplateExport, 'template_import_kendaraan.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new KendaraanImport, $request->file('excel_file'));
            return redirect()->route('kendaraan.index')->with('success', 'Data kendaraan berhasil diimport dari Excel!');
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

    public function index(Request $request)
    {
        $query = Kendaraan::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nopol', 'like', "%{$search}%")
                  ->orWhere('nomor_pintu', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%");
        }

        $kendaraans = $query->latest()->paginate(10);
        return view('pages.kendaraan.index', [
            'title' => 'Master Data Kendaraan',
            'kendaraans' => $kendaraans,
        ]);
    }

    public function create()
    {
        return view('pages.kendaraan.create', [
            'title' => 'Tambah Kendaraan',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_pintu' => 'required|string|max:50',
            'nopol' => 'required|string|max:50|unique:kendaraan,nopol',
            'jenis' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:150',
            'exp_kir' => 'nullable|date',
            'biaya' => 'required|numeric|min:0',
            'jasa' => 'required|numeric|min:0',
            'no_pr' => 'nullable|string|max:100',
            'no_spk' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
        ]);

        $validated['total'] = $validated['biaya'] + $validated['jasa'];

        Kendaraan::create($validated);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan!');
    }

    public function show(Kendaraan $kendaraan)
    {
        $kendaraan->load(['histories', 'documents']);
        return view('pages.kendaraan.show', [
            'title' => 'Detail Kendaraan',
            'kendaraan' => $kendaraan,
        ]);
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('pages.kendaraan.edit', [
            'title' => 'Edit Kendaraan',
            'kendaraan' => $kendaraan,
        ]);
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'nomor_pintu' => 'required|string|max:50',
            'nopol' => 'required|string|max:50|unique:kendaraan,nopol,' . $kendaraan->id,
            'jenis' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:150',
            'exp_kir' => 'nullable|date',
            'biaya' => 'required|numeric|min:0',
            'jasa' => 'required|numeric|min:0',
            'no_pr' => 'nullable|string|max:100',
            'no_spk' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
        ]);

        $validated['total'] = $validated['biaya'] + $validated['jasa'];

        $kendaraan->update($validated);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil diperbarui!');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dihapus!');
    }
}
