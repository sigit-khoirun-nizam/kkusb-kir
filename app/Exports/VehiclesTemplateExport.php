<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class VehiclesTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return collect([
            [
                'plate_number' => 'B 1234 ABC',
                'type' => 'mobil',
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'year' => 2020,
                'department_id' => '',
                'status' => 'aktif',
            ],
            [
                'plate_number' => 'B 5678 XYZ',
                'type' => 'motor',
                'brand' => 'Honda',
                'model' => 'Vario',
                'year' => 2021,
                'department_id' => '',
                'status' => 'aktif',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'plate_number',
            'type',
            'brand',
            'model',
            'year',
            'department_id',
            'status',
        ];
    }

    public function title(): string
    {
        return 'Template Kendaraan';
    }
}
