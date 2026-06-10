<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class KirsTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return collect([
            [
                'vehicle_id' => 1,
                'nomor_pintu' => 'P001',
                'nopol' => 'B 1234 ABC',
                'deskripsi' => 'KIR Rutin',
                'plate_nomor_kategori' => 'B',
                'tanggal_proses' => now()->format('Y-m-d'),
                'exp_kir' => now()->addYear()->format('Y-m-d'),
                'next_alert_date' => now()->addMonths(11)->format('Y-m-d'),
                'biaya' => 500000,
                'jasa' => 100000,
                'no_pr' => 'PR-001',
                'no_spk' => 'SPK-001',
                'dokumen_kir' => 'dokumen_kir_001.pdf',
                'status' => 'aktif',
                'catatan' => 'KIR tahunan',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'vehicle_id',
            'nomor_pintu',
            'nopol',
            'deskripsi',
            'plate_nomor_kategori',
            'tanggal_proses',
            'exp_kir',
            'next_alert_date',
            'biaya',
            'jasa',
            'no_pr',
            'no_spk',
            'dokumen_kir',
            'status',
            'catatan',
        ];
    }

    public function title(): string
    {
        return 'Template KIR';
    }
}
