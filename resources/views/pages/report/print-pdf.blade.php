<h1 style="text-align: center; color: #1e3a8a; font-family: helvetica; font-size: 16px; font-weight: bold; margin-bottom: 5px;">LAPORAN HISTORI PEMBARUAN KIR</h1>
<p style="text-align: center; font-family: helvetica; font-size: 10px; color: #4b5563;">
    Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d F Y') : 'Awal' }} s/d {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d F Y') : 'Akhir' }}
</p>

<br/>

<table border="1" cellpadding="6" cellspacing="0" style="width: 100%; border-color: #cbd5e1; font-size: 9px; font-family: helvetica;">
    <thead>
        <tr style="background-color: #1e3a8a; color: #ffffff; font-weight: bold; text-align: center;">
            <td style="background-color: #1e3a8a; color: #ffffff; width: 4%; font-weight: bold; vertical-align: middle;" valign="middle">No</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 8%; font-weight: bold; vertical-align: middle;" valign="middle">No. Pintu</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 10%; font-weight: bold; vertical-align: middle;" valign="middle">No. Polisi</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 10%; font-weight: bold; vertical-align: middle;" valign="middle">Tgl. Proses</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 10%; font-weight: bold; vertical-align: middle;" valign="middle">Exp Baru</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 11%; font-weight: bold; vertical-align: middle;" valign="middle">Biaya Resmi</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 11%; font-weight: bold; vertical-align: middle;" valign="middle">Jasa</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 18%; font-weight: bold; vertical-align: middle;" valign="middle">Biaya Tambahan</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 12%; font-weight: bold; vertical-align: middle;" valign="middle">Total</td>
            <td style="background-color: #1e3a8a; color: #ffffff; width: 6%; font-weight: bold; vertical-align: middle;" valign="middle">No. PR</td>
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
                $rowBg = ($idx % 2 === 0) ? '#ffffff' : '#f8fafc';
            @endphp
            <tr style="background-color: {{ $rowBg }};">
                <td style="background-color: {{ $rowBg }}; width: 4%; text-align: center; color: #334155; vertical-align: middle;" valign="middle">{{ $idx + 1 }}</td>
                <td style="background-color: {{ $rowBg }}; width: 8%; text-align: center; font-weight: bold; color: #1e3a8a; vertical-align: middle;" valign="middle">{{ $history->kendaraan->nomor_pintu ?? '-' }}</td>
                <td style="background-color: {{ $rowBg }}; width: 10%; text-align: center; color: #334155; vertical-align: middle;" valign="middle">{{ $history->kendaraan->nopol ?? '-' }}</td>
                <td style="background-color: {{ $rowBg }}; width: 10%; text-align: center; color: #334155; vertical-align: middle;" valign="middle">{{ $history->tanggal_proses ? $history->tanggal_proses->format('d/m/Y') : '-' }}</td>
                <td style="background-color: {{ $rowBg }}; width: 10%; text-align: center; color: #334155; vertical-align: middle;" valign="middle">{{ $history->exp_kir_baru ? $history->exp_kir_baru->format('d/m/Y') : '-' }}</td>
                <td style="background-color: {{ $rowBg }}; width: 11%; text-align: right; color: #334155; vertical-align: middle;" valign="middle">Rp {{ $history->formatted_biaya }}</td>
                <td style="background-color: {{ $rowBg }}; width: 11%; text-align: right; color: #334155; vertical-align: middle;" valign="middle">Rp {{ $history->formatted_jasa }}</td>
                <td style="background-color: {{ $rowBg }}; width: 18%; color: #475569; font-size: 8px; vertical-align: middle;" valign="middle">
                    @if($history->additionalFees->isEmpty())
                        <span style="color: #94a3b8;">-</span>
                    @else
                        @foreach($history->additionalFees as $addFee)
                            <div style="line-height: 10px;">• {{ $addFee->feeType->name }}: Rp {{ $addFee->formatted_amount }}</div>
                        @endforeach
                    @endif
                </td>
                <td style="background-color: {{ $rowBg }}; width: 12%; text-align: right; font-weight: bold; color: #0f172a; vertical-align: middle;" valign="middle">Rp {{ $history->formatted_total }}</td>
                <td style="background-color: {{ $rowBg }}; width: 6%; text-align: center; color: #475569; vertical-align: middle;" valign="middle">{{ $history->no_pr ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="width: 100%; text-align: center; color: #64748b; font-style: italic; background-color: #ffffff;">Tidak ada data histori KIR dalam periode ini.</td>
            </tr>
        @endforelse
        @if($histories->isNotEmpty())
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="5" style="background-color: #f1f5f9; width: 42%; text-align: right; color: #1e3a8a; font-size: 9px; vertical-align: middle;" valign="middle">GRAND TOTAL:</td>
                <td style="background-color: #f1f5f9; width: 11%; text-align: right; color: #1e3a8a; font-size: 9px; vertical-align: middle;" valign="middle">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</td>
                <td style="background-color: #f1f5f9; width: 11%; text-align: right; color: #1e3a8a; font-size: 9px; vertical-align: middle;" valign="middle">Rp {{ number_format($totalJasa, 0, ',', '.') }}</td>
                <td style="background-color: #f1f5f9; width: 18%; vertical-align: middle;" valign="middle">&nbsp;</td>
                <td style="background-color: #f1f5f9; width: 12%; text-align: right; color: #1e3a8a; font-size: 9px; vertical-align: middle;" valign="middle">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td style="background-color: #f1f5f9; width: 6%; vertical-align: middle;" valign="middle">&nbsp;</td>
            </tr>
        @endif
    </tbody>
</table>

