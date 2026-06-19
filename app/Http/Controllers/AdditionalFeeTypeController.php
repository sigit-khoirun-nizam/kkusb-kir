<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdditionalFeeType;

class AdditionalFeeTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = AdditionalFeeType::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $feeTypes = $query->latest()->paginate(5)->withQueryString();

        return view('pages.biaya-tambahan.index', [
            'title' => 'Master Data Biaya Tambahan',
            'feeTypes' => $feeTypes,
        ]);
    }

    public function create()
    {
        return view('pages.biaya-tambahan.create', [
            'title' => 'Tambah Biaya Tambahan',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        AdditionalFeeType::create($validated);

        return redirect()->route('biaya-tambahan.index')->with('success', 'Biaya tambahan berhasil ditambahkan!');
    }

    public function edit(AdditionalFeeType $biaya_tambahan)
    {
        return view('pages.biaya-tambahan.edit', [
            'title' => 'Edit Biaya Tambahan',
            'feeType' => $biaya_tambahan,
        ]);
    }

    public function update(Request $request, AdditionalFeeType $biaya_tambahan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        $biaya_tambahan->update($validated);

        return redirect()->route('biaya-tambahan.index')->with('success', 'Biaya tambahan berhasil diperbarui!');
    }

    public function destroy(AdditionalFeeType $biaya_tambahan)
    {
        $biaya_tambahan->delete();
        return redirect()->route('biaya-tambahan.index')->with('success', 'Biaya tambahan berhasil dihapus!');
    }

    public function toggleStatus(AdditionalFeeType $additional_fee_type)
    {
        $newStatus = $additional_fee_type->status === 'aktif' ? 'nonaktif' : 'aktif';
        $additional_fee_type->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('biaya-tambahan.index')->with('success', 'Status biaya "' . $additional_fee_type->name . '" berhasil diubah menjadi ' . $newStatus . '!');
    }
}
