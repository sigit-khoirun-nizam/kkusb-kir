@extends('layouts.app')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }} - {{ $kendaraan->nomor_pintu }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Detail spesifikasi kendaraan dan riwayat KIR</p>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('kir.proses-form', $kendaraan) }}" class="inline-flex items-center justify-center rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600">
          Proses KIR
        </a>
        <a href="{{ route('kendaraan.edit', $kendaraan) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
          Edit
        </a>
        <a href="{{ route('kendaraan.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
          Kembali
        </a>
      </div>
    </div>

    <!-- Main Specs Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
      <!-- Vehicle Info Card -->
      <div class="col-span-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Kendaraan</h3>
        <div class="space-y-4">
          <div>
            <span class="text-xs text-gray-500 uppercase">Nomor Pintu</span>
            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $kendaraan->nomor_pintu }}</p>
          </div>
          <div>
            <span class="text-xs text-gray-500 uppercase">Nomor Polisi</span>
            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $kendaraan->nopol }}</p>
          </div>
          <div>
            <span class="text-xs text-gray-500 uppercase">Jenis Kendaraan</span>
            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $kendaraan->jenis }}</p>
          </div>
          <div>
            <span class="text-xs text-gray-500 uppercase">Keterangan / Deskripsi</span>
            <p class="text-sm text-gray-800 dark:text-white/90">{{ $kendaraan->deskripsi ?? '-' }}</p>
          </div>
        </div>
      </div>

      <!-- Current KIR Stats Card -->
      <div class="col-span-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">KIR Aktif Saat Ini</h3>
        <div class="space-y-4">
          <div>
            <span class="text-xs text-gray-500 uppercase">Masa Berlaku (Expiry Date)</span>
            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
              {{ $kendaraan->exp_kir ? $kendaraan->exp_kir->format('d F Y') : 'Belum Pernah' }}
            </p>
          </div>
          <div>
            <span class="text-xs text-gray-500 uppercase">Total Biaya KIR Terakhir</span>
            <p class="text-sm font-medium text-gray-800 dark:text-white/90">Rp {{ $kendaraan->formatted_total }}</p>
            <span class="text-xs text-gray-400">(Biaya Resmi: Rp {{ $kendaraan->formatted_biaya }} | Jasa: Rp {{ $kendaraan->formatted_jasa }})</span>
          </div>
          <div>
            <span class="text-xs text-gray-500 uppercase">Nomor PR / SPK</span>
            <p class="text-sm font-medium text-gray-800 dark:text-white/90">PR: {{ $kendaraan->no_pr ?? '-' }} | SPK: {{ $kendaraan->no_spk ?? '-' }}</p>
          </div>
          <div>
            <span class="text-xs text-gray-500 uppercase">Status</span>
            <div>
              <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                @if($kendaraan->status_color == 'green') bg-green-500/10 text-green-500 dark:bg-green-500/20 dark:text-green-400
                @elseif($kendaraan->status_color == 'yellow') bg-yellow-500/10 text-yellow-500 dark:bg-yellow-500/20 dark:text-yellow-400
                @elseif($kendaraan->status_color == 'red') bg-red-500/10 text-red-500 dark:bg-red-500/20 dark:text-red-400
                @else bg-gray-500/10 text-gray-500 dark:bg-gray-500/20 dark:text-gray-400
                @endif">
                {{ $kendaraan->status_label }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Scanned Documents Card -->
      <div class="col-span-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Dokumen Pendukung</h3>
        <div class="space-y-3">
          @forelse($kendaraan->documents as $doc)
            <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.01]">
              <div class="overflow-hidden mr-2">
                <p class="text-xs font-medium text-gray-800 dark:text-white/90 truncate">{{ $doc->nama_file }}</p>
                <span class="text-[10px] text-gray-400">{{ $doc->created_at->format('m/d/Y') }}</span>
              </div>
              <a href="{{ Storage::url($doc->path) }}" target="_blank" class="inline-flex items-center text-xs font-medium text-brand-500 hover:text-brand-600">
                Buka
              </a>
            </div>
          @empty
            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada scan dokumen kendaraan.</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- KIR History Card -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Histori Pembaruan KIR</h3>
      </div>
      <div class="p-6">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200 text-left dark:border-gray-800">
                <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Proses</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Exp Lama</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Exp Baru</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Biaya</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jasa</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Total</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">No. PR</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">No. SPK</th>
              </tr>
            </thead>
            <tbody>
              @forelse($kendaraan->histories as $history)
                <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800/50">
                  <td class="px-4 py-3.5 text-sm text-gray-800 dark:text-white/90">{{ $history->tanggal_proses ? $history->tanggal_proses->format('m/d/Y') : '-' }}</td>
                  <td class="px-4 py-3.5 text-sm text-gray-500 dark:text-gray-400">{{ $history->exp_kir_lama ? $history->exp_kir_lama->format('m/d/Y') : '-' }}</td>
                  <td class="px-4 py-3.5 text-sm text-gray-800 dark:text-white/90 font-medium">{{ $history->exp_kir_baru ? $history->exp_kir_baru->format('m/d/Y') : '-' }}</td>
                  <td class="px-4 py-3.5 text-sm text-gray-800 dark:text-white/90">Rp {{ $history->formatted_biaya }}</td>
                  <td class="px-4 py-3.5 text-sm text-gray-800 dark:text-white/90">Rp {{ $history->formatted_jasa }}</td>
                  <td class="px-4 py-3.5 text-sm text-gray-800 dark:text-white/90 font-semibold">Rp {{ $history->formatted_total }}</td>
                  <td class="px-4 py-3.5 text-sm text-gray-800 dark:text-white/90">{{ $history->no_pr ?? '-' }}</td>
                  <td class="px-4 py-3.5 text-sm text-gray-800 dark:text-white/90">{{ $history->no_spk ?? '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada histori pembaruan KIR untuk kendaraan ini.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
