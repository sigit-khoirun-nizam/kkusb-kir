<?php

namespace App\Exports;

use App\Models\KirHistory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class KirHistoryExport implements FromQuery, WithHeadings, WithTitle, WithMapping
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = KirHistory::with(['kendaraan', 'additionalFees.feeType']);

        $startDate = $this->startDate;
        $endDate = $this->endDate;

        if ($startDate) {
            try {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $startDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $startDate = \Carbon\Carbon::parse($startDate)->format('Y-m-d');
            }
            $query->whereDate('tanggal_proses', '>=', $startDate);
        }

        if ($endDate) {
            try {
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $endDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $endDate = \Carbon\Carbon::parse($endDate)->format('Y-m-d');
            }
            $query->whereDate('tanggal_proses', '<=', $endDate);
        }

        return $query->orderBy('tanggal_proses', 'asc');
    }

    public function headings(): array
    {
        return [
            'No. Pintu',
            'No. Polisi',
            'Jenis Unit',
            'Tanggal Proses',
            'Exp KIR Lama',
            'Exp KIR Baru',
            'Biaya Resmi',
            'Jasa Pengurusan',
            'Biaya Tambahan (Detail)',
            'Total Pengeluaran',
            'No. PR',
            'No. SPK',
        ];
    }

    public function map($history): array
    {
        $additionalFeesStr = '';
        if ($history->additionalFees->isNotEmpty()) {
            $feesList = [];
            foreach ($history->additionalFees as $addFee) {
                $feesList[] = $addFee->feeType->name . ': Rp ' . $addFee->formatted_amount;
            }
            $additionalFeesStr = implode("\n", $feesList);
        }

        return [
            $history->kendaraan->nomor_pintu ?? '-',
            $history->kendaraan->nopol ?? '-',
            $history->kendaraan->jenis ?? '-',
            $history->tanggal_proses ? $history->tanggal_proses->format('Y-m-d') : '',
            $history->exp_kir_lama ? $history->exp_kir_lama->format('Y-m-d') : '',
            $history->exp_kir_baru ? $history->exp_kir_baru->format('Y-m-d') : '',
            $history->biaya,
            $history->jasa,
            $additionalFeesStr,
            $history->total,
            $history->no_pr,
            $history->no_spk,
        ];
    }

    public function title(): string
    {
        return 'Laporan Histori KIR';
    }
}
