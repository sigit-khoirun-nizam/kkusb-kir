<?php

namespace App\Imports;

use App\Models\Kendaraan;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class KendaraanImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $expKir = null;
            if (!empty($row['exp_kir'])) {
                $val = trim($row['exp_kir']);
                if (is_numeric($val)) {
                    try {
                        $expKir = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val))->startOfDay();
                    } catch (\Exception $ex) {
                        $expKir = null;
                    }
                } else {
                    try {
                        // Try parsing with explicit format m/d/Y first (e.g. 4/29/2029 or 04/29/2029)
                        $expKir = Carbon::createFromFormat('m/d/Y', $val)->startOfDay();
                    } catch (\Exception $e) {
                        try {
                            // Fallback to standard parse
                            $expKir = Carbon::parse($val)->startOfDay();
                        } catch (\Exception $e2) {
                            $expKir = null;
                        }
                    }
                }
            }

            $biaya = floatval($row['biaya'] ?? 0);
            $jasa = floatval($row['jasa'] ?? 0);
            $total = $biaya + $jasa;

            Kendaraan::updateOrCreate(
                ['nopol' => $row['nopol']],
                [
                    'nomor_pintu' => $row['nomor_pintu'] ?? null,
                    'jenis' => $row['jenis'] ?? null,
                    'deskripsi' => $row['deskripsi'] ?? null,
                    'exp_kir' => $expKir,
                    'biaya' => $biaya,
                    'jasa' => $jasa,
                    'total' => $total,
                    'no_pr' => $row['no_pr'] ?? null,
                    'no_spk' => $row['no_spk'] ?? null,
                    'status' => 'aktif',
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'nomor_pintu' => 'nullable|max:50',
            'nopol' => 'required|max:50',
            'jenis' => 'required|max:50',
            'deskripsi' => 'nullable|max:150',
            'exp_kir' => 'nullable',
            'biaya' => 'nullable|numeric|min:0',
            'jasa' => 'nullable|numeric|min:0',
            'no_pr' => 'nullable|max:100',
            'no_spk' => 'nullable|max:100',
        ];
    }
}
