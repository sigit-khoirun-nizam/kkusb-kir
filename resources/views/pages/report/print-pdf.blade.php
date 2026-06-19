<h1 style="text-align: center; color: #1e3a8a; font-family: helvetica; font-size: 16px;">LAPORAN HISTORI PEMBARUAN KIR</h1>
<p style="text-align: center; font-family: helvetica; font-size: 10px; color: #4b5563;">
    Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d F Y') : 'Awal' }} s/d {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d F Y') : 'Akhir' }}
</p>

<br/>

<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-color: #cbd5e1; font-size: 8px; font-family: helvetica;">
    <thead>
        <tr style="background-color: #f1f5f9; font-weight: bold; color: #334155; text-align: center;">
            <th style="width: 4%;">No</th>
            <th style="width: 8%;">No. Pintu</th>
            <th style="width: 10%;">No. Polisi</th>
            <th style="width: 10%;">Tgl. Proses</th>
            <th style="width: 10%;">Exp Baru</th>
            <th style="width: 11%;">Biaya Resmi</th>
            <th style="width: 11%;">Jasa</th>
            <th style="width: 18%;">Biaya Tambahan</th>
            <th style="width: 12%;">Total</th>
            <th style="width: 6%;">No. PR</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grandTotal = 0;
            $totalBiaya = 0;
            $totalJasa = 0;
        @endphp
        @forelse($histories as $idx => $history)
            @php
                $grandTotal += $history->total;
                $totalBiaya += $history->biaya;
                $totalJasa += $history->jasa;
            @endphp
            <tr>
                <td style="width: 4%; text-align: center;">{{ $idx + 1 }}</td>
                <td style="width: 8%; text-align: center; font-weight: bold; color: #1f2937;">{{ $history->kendaraan->nomor_pintu ?? '-' }}</td>
                <td style="width: 10%; text-align: center;">{{ $history->kendaraan->nopol ?? '-' }}</td>
                <td style="width: 10%; text-align: center;">{{ $history->tanggal_proses ? $history->tanggal_proses->format('d/m/Y') : '-' }}</td>
                <td style="width: 10%; text-align: center;">{{ $history->exp_kir_baru ? $history->exp_kir_baru->format('d/m/Y') : '-' }}</td>
                <td style="width: 11%; text-align: right;">Rp {{ $history->formatted_biaya }}</td>
                <td style="width: 11%; text-align: right;">Rp {{ $history->formatted_jasa }}</td>
                <td style="width: 18%;">
                    @foreach($history->additionalFees as $addFee)
                        <div style="line-height: 10px;">• {{ $addFee->feeType->name }}: Rp {{ $addFee->formatted_amount }}</div>
                    @endforeach
                </td>
                <td style="width: 12%; text-align: right; font-weight: bold; color: #0f172a;">Rp {{ $history->formatted_total }}</td>
                <td style="width: 6%; text-align: center;">{{ $history->no_pr ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="width: 100%; text-align: center; color: #64748b; font-style: italic;">Tidak ada data histori KIR dalam periode ini.</td>
            </tr>
        @endforelse
        @if($histories->isNotEmpty())
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="5" style="width: 42%; text-align: right; color: #1e3a8a; font-size: 9px;">GRAND TOTAL:</td>
                <td style="width: 11%; text-align: right; color: #1e3a8a; font-size: 9px;">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</td>
                <td style="width: 11%; text-align: right; color: #1e3a8a; font-size: 9px;">Rp {{ number_format($totalJasa, 0, ',', '.') }}</td>
                <td style="width: 18%;">&nbsp;</td>
                <td style="width: 12%; text-align: right; color: #1e3a8a; font-size: 9px;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td style="width: 6%;">&nbsp;</td>
            </tr>
        @endif
    </tbody>
</table>

