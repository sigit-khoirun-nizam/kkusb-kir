<?php

namespace App\Imports;

use App\Models\VehicleKir;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;

class KirsImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            VehicleKir::create([
                'vehicle_id' => $row['vehicle_id'],
                'nomor_pintu' => $row['nomor_pintu'] ?? null,
                'nopol' => $row['nopol'] ?? null,
                'deskripsi' => $row['deskripsi'] ?? null,
                'plate_nomor_kategori' => $row['plate_nomor_kategori'] ?? null,
                'tanggal_proses' => $row['tanggal_proses'],
                'exp_kir' => $row['exp_kir'],
                'next_alert_date' => $row['next_alert_date'] ?? null,
                'biaya' => $row['biaya'],
                'jasa' => $row['jasa'],
                'total' => ($row['biaya'] ?? 0) + ($row['jasa'] ?? 0),
                'no_pr' => $row['no_pr'] ?? null,
                'no_spk' => $row['no_spk'] ?? null,
                'dokumen_kir' => $row['dokumen_kir'] ?? null,
                'status' => $row['status'] ?? 'aktif',
                'catatan' => $row['catatan'] ?? null,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:kir_vehicles,id',
            'nomor_pintu' => 'nullable|string|max:255',
            'nopol' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'plate_nomor_kategori' => 'nullable|string|max:5',
            'tanggal_proses' => 'required|date',
            'exp_kir' => 'required|date|after:tanggal_proses',
            'next_alert_date' => 'nullable|date|before:exp_kir',
            'biaya' => 'required|integer|min:0',
            'jasa' => 'required|integer|min:0',
            'no_pr' => 'nullable|string|max:255',
            'no_spk' => 'nullable|string|max:255',
            'dokumen_kir' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,jatuh_tempo,terlambat,selesai',
            'catatan' => 'nullable|string',
        ];
    }

    public function onError(\Throwable $e)
    {
        // Handle error
    }

    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        // Handle validation failure
    }
}
