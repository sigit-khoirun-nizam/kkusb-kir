<?php

namespace App\Imports;

use App\Models\KirVehicle;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;

class VehiclesImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            KirVehicle::create([
                'plate_number' => $row['plate_number'],
                'type' => $row['type'],
                'brand' => $row['brand'],
                'model' => $row['model'],
                'year' => $row['year'],
                'department_id' => $row['department_id'] ?? null,
                'status' => $row['status'] ?? 'aktif',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'plate_number' => 'required|string|max:20',
            'type' => 'required|in:mobil,motor,truk,alat_berat',
            'brand' => 'required|string|max:80',
            'model' => 'required|string|max:80',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'department_id' => 'nullable|integer',
            'status' => 'nullable|in:aktif,rusak,dalam perbaikan',
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
