<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
    <tr>
        <td style="width: 8%; vertical-align: middle;" valign="middle">
            <img src="{{ public_path('images/logo-kkusb.png') }}" width="42" height="42" />
        </td>
        <td style="width: 1%;">&nbsp;</td>
        <td style="width: 56%; vertical-align: middle;" valign="middle">
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                <tr><td><span style="font-size: 12px; font-weight: bold; color: #1e3a8a; font-family: helvetica; line-height: 15px;">Koperasi Jasa Karyawan</span></td></tr>
                <tr><td><span style="font-size: 14px; font-weight: bold; color: #1e3a8a; font-family: helvetica; line-height: 17px;">USAHA SEJAHTERA BERSAMA</span></td></tr>
            </table>
        </td>
        <td style="width: 35%; text-align: right; vertical-align: top;">
            <span style="font-size: 15px; font-weight: bold; color: #0f172a; font-family: helvetica;">INVOICE</span><br/>
            <span style="font-size: 9px; color: #6b7280; font-family: helvetica;">No: KIR-{{ str_pad($history->id, 6, '0', STR_PAD_LEFT) }}</span>
        </td>
    </tr>
</table>

<!-- Blue horizontal separator line -->
<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-top: 10px; margin-bottom: 25px;">
    <tr>
        <td style="border-bottom: 3px solid #1e3a8a; line-height: 5px;">&nbsp;</td>
    </tr>
</table>

<!-- Double Column Grid for Info -->
<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
    <tr>
        <!-- Left Info Block -->
        <td style="width: 48%;">
            <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-color: #e5e7eb; font-size: 10px; font-family: helvetica;">
                <tr style="background-color: #f8fafc;">
                    <td colspan="2" style="width: 100%; font-weight: bold; color: #1e3a8a; font-size: 11px; border-bottom: 1px solid #e5e7eb;">DETIL KENDARAAN</td>
                </tr>
                <tr>
                    <td style="width: 35%; color: #4b5563; border-bottom: 1px solid #f3f4f6;">No. Pintu</td>
                    <td style="width: 65%; font-weight: bold; color: #1f2937; border-bottom: 1px solid #f3f4f6;">{{ $history->kendaraan->nomor_pintu }}</td>
                </tr>
                <tr>
                    <td style="width: 35%; color: #4b5563; border-bottom: 1px solid #f3f4f6;">No. Polisi</td>
                    <td style="width: 65%; font-weight: bold; color: #1f2937; border-bottom: 1px solid #f3f4f6;">{{ $history->kendaraan->nopol }}</td>
                </tr>
                <tr>
                    <td style="width: 35%; color: #4b5563;">Jenis Unit</td>
                    <td style="width: 65%; font-weight: bold; color: #1f2937;">{{ $history->kendaraan->jenis }}</td>
                </tr>
            </table>
        </td>
        
        <!-- Space column -->
        <td style="width: 4%;">&nbsp;</td>
        
        <!-- Right Info Block -->
        <td style="width: 48%;">
            <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-color: #e5e7eb; font-size: 10px; font-family: helvetica;">
                <tr style="background-color: #f8fafc;">
                    <td colspan="2" style="width: 100%; font-weight: bold; color: #1e3a8a; font-size: 11px; border-bottom: 1px solid #e5e7eb;">RINCIAN TRANSAKSI</td>
                </tr>
                <tr>
                    <td style="width: 38%; color: #4b5563; border-bottom: 1px solid #f3f4f6;">Tgl. Proses</td>
                    <td style="width: 62%; font-weight: bold; color: #1f2937; border-bottom: 1px solid #f3f4f6;">{{ $history->tanggal_proses ? $history->tanggal_proses->format('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 38%; color: #4b5563; border-bottom: 1px solid #f3f4f6;">Masa Berlaku</td>
                    <td style="width: 62%; font-weight: bold; color: #1f2937; border-bottom: 1px solid #f3f4f6;">{{ $history->exp_kir_baru ? $history->exp_kir_baru->format('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 38%; color: #4b5563;">No. PR / SPK</td>
                    <td style="width: 62%; font-weight: bold; color: #1f2937;">{{ $history->no_pr ?? '-' }} / {{ $history->no_spk ?? '-' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br/><br/>

<!-- Cost Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-color: #cbd5e1; font-size: 11px; font-family: helvetica;">
    <thead>
        <tr style="background-color: #f1f5f9; font-weight: bold; color: #334155;">
            <th style="width: 10%; text-align: center;">No</th>
            <th style="width: 60%;">Deskripsi Pembiayaan</th>
            <th style="width: 30%; text-align: right;">Jumlah Biaya</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 10%; text-align: center; color: #64748b;">1</td>
            <td style="width: 60%; color: #334155;">Biaya Resmi KIR <span style="font-size: 9px; color: #64748b;"><br/>(Retribusi Uji Berkala, Bukti Lulus Uji Elektronik/Smart Card)</span></td>
            <td style="width: 30%; text-align: right; font-weight: bold; color: #0f172a; vertical-align: middle;">Rp {{ $history->formatted_biaya }}</td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: center; color: #64748b;">2</td>
            <td style="width: 60%; color: #334155;">Jasa Pengurusan KIR <span style="font-size: 9px; color: #64748b;"><br/>(Jasa kepengurusan administrasi operasional oleh agen/pihak ketiga)</span></td>
            <td style="width: 30%; text-align: right; font-weight: bold; color: #0f172a; vertical-align: middle;">Rp {{ $history->formatted_jasa }}</td>
        </tr>
        @php
            $rowNo = 3;
        @endphp
        @foreach($history->additionalFees as $addFee)
            <tr>
                <td style="width: 10%; text-align: center; color: #64748b;">{{ $rowNo++ }}</td>
                <td style="width: 60%; color: #334155;">{{ $addFee->feeType->name }} <span style="font-size: 9px; color: #64748b;"><br/>(Biaya tambahan opsional)</span></td>
                <td style="width: 30%; text-align: right; font-weight: bold; color: #0f172a; vertical-align: middle;">Rp {{ $addFee->formatted_amount }}</td>
            </tr>
        @endforeach
        <tr style="background-color: #f8fafc; font-weight: bold;">
            <td colspan="2" style="width: 70%; font-size: 12px; color: #1e3a8a; text-align: left;">TOTAL PENGELUARAN</td>
            <td style="width: 30%; font-size: 12px; color: #1e3a8a; text-align: right;">Rp {{ $history->formatted_total }}</td>
        </tr>
    </tbody>
</table>

<br/><br/><br/>

<!-- Date & Additional notes -->
<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
    <tr>
        <td style="width: 60%; font-size: 9px; color: #64748b; font-family: helvetica; vertical-align: bottom;">
            <strong>Catatan:</strong><br/>
            1. Bukti ini merupakan rincian resmi pengeluaran kas unit KIR kendaraan.<br/>
            2. Masa berlaku KIR lama sebelumnya: {{ $history->exp_kir_lama ? $history->exp_kir_lama->format('d F Y') : 'Belum Pernah' }}.
        </td>
        <td style="width: 40%; text-align: right; font-size: 9px; color: #64748b; font-family: helvetica;">
            Diterbitkan oleh sistem pada:<br/>
            <strong>{{ now()->format('d F Y H:i') }} WIB</strong>
        </td>
    </tr>
</table>

<br/><br/>

<!-- E-Signature Notice Footer -->
<table border="0" cellpadding="8" cellspacing="0" style="width: 100%; border-top: 1px dashed #cbd5e1; text-align: center; font-size: 9px; color: #94a3b8; font-family: helvetica;">
    <tr>
        <td>
            Dokumen ini diterbitkan secara elektronik oleh Sistem Monitoring KIR Koperasi Karyawan USB.<br/>
            Bukti ini sah dan valid sebagai pencatatan operasional internal tanpa memerlukan tanda tangan basah.
        </td>
    </tr>
</table>
