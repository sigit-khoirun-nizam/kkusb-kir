<?php

namespace App\Exports;

use App\Models\Kendaraan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class KendaraanExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return Kendaraan::all();
    }

    public function headings(): array
    {
        return [
            'Nomor Pintu',
            'Nopol',
            'Jenis',
            'Deskripsi',
            'Exp KIR',
            'Biaya',
            'Jasa',
            'Total',
            'No PR',
            'No SPK',
            'Status',
        ];
    }

    public function map($kendaraan): array
    {
        return [
            $kendaraan->nomor_pintu,
            $kendaraan->nopol,
            $kendaraan->jenis,
            $kendaraan->deskripsi,
            $kendaraan->exp_kir ? $kendaraan->exp_kir->format('Y-m-d') : '',
            $kendaraan->biaya,
            $kendaraan->jasa,
            $kendaraan->total,
            $kendaraan->no_pr,
            $kendaraan->no_spk,
            $kendaraan->status,
        ];
    }

    public function title(): string
    {
        return 'Laporan KIR Kendaraan';
    }
}
