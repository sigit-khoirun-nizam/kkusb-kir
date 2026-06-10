@extends('layouts.app')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Riwayat pengajuan dan perpanjangan KIR seluruh kendaraan</p>
      </div>
      <div>
        <!-- Filter Form -->
        <form action="{{ route('kir.history') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
          <div x-data="{ isOptionSelected: {{ request('kendaraan_id') ? 'true' : 'false' }} }" class="relative z-20 bg-transparent">
            <select name="kendaraan_id" onchange="this.form.submit()"
              class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
              :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
              <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Semua Kendaraan</option>
              @foreach($kendaraans as $kendaraan)
                <option value="{{ $kendaraan->id }}" {{ request('kendaraan_id') == $kendaraan->id ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                  {{ $kendaraan->nomor_pintu }} - {{ $kendaraan->nopol }}
                </option>
              @endforeach
            </select>
            <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
              <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </span>
          </div>
          @if(request('kendaraan_id'))
            <a href="{{ route('kir.history') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
              Clear
            </a>
          @endif
        </form>
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 text-left dark:border-gray-800">
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">No. Pintu</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">No. Polisi</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Proses</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Exp Lama</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Exp Baru</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Total Biaya</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">No. PR / SPK</th>
            </tr>
          </thead>
          <tbody>
            @forelse($histories as $history)
              <tr class="border-b border-gray-200 last:border-0 dark:border-gray-800 hover:bg-gray-50/30">
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white/90">
                  <a href="{{ route('kendaraan.show', $history->kendaraan) }}" class="text-brand-500 hover:underline">
                    {{ $history->kendaraan->nomor_pintu }}
                  </a>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $history->kendaraan->nopol }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                  {{ $history->tanggal_proses ? $history->tanggal_proses->format('m/d/Y') : '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-400 dark:text-gray-500">
                  {{ $history->exp_kir_lama ? $history->exp_kir_lama->format('m/d/Y') : '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90 font-medium">
                  {{ $history->exp_kir_baru ? $history->exp_kir_baru->format('m/d/Y') : '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                  Rp {{ $history->formatted_total }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                  PR: {{ $history->no_pr ?? '-' }} | SPK: {{ $history->no_spk ?? '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                  Belum ada data histori KIR.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($histories->hasPages())
        <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-800">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $histories->firstItem() }} sampai {{ $histories->lastItem() }} dari {{ $histories->total() }} data
          </p>
          {{ $histories->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
