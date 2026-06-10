<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class KendaraanTemplateExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            [
                '101',
                'B 1234 CD',
                'Medium Bus',
                'Kendaraan Operasional Divisi A',
                '2026-12-31',
                '150000',
                '50000',
                'PR-2026-001',
                'SPK-2026-001'
            ],
            [
                '102',
                'D 5678 EF',
                'Big Bus',
                'Kendaraan Operasional Divisi B',
                '2026-10-15',
                '180000',
                '60000',
                'PR-2026-002',
                'SPK-2026-002'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'nomor_pintu',
            'nopol',
            'jenis',
            'deskripsi',
            'exp_kir',
            'biaya',
            'jasa',
            'no_pr',
            'no_spk'
        ];
    }

    public function title(): string
    {
        return 'Template Import Kendaraan';
    }
}
