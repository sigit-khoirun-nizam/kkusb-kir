@extends('layouts.app')

@section('content')
  <div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tinjau rekap pengeluaran dan jumlah pengurusan KIR bulanan</p>
      </div>
      <div>
        <!-- Year selector -->
        <form action="{{ route('report.rekap-biaya') }}" method="GET" class="flex gap-2">
          <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
            <select name="year" onchange="this.form.submit()"
              class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
              :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
              @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Tahun {{ $y }}</option>
              @endfor
            </select>
            <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
              <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </span>
          </div>
        </form>
      </div>
    </div>

    <!-- Summary Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="flex items-center justify-between">
        <div>
          <span class="text-xs text-gray-400 uppercase">Total Pengeluaran KIR (Tahun {{ $year }})</span>
          <h2 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">Rp {{ number_format($yearlyTotal, 0, ',', '.') }}</h2>
        </div>
      </div>
    </div>

    <!-- Cost Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 text-left dark:border-gray-800">
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Bulan</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Kendaraan</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Biaya Resmi</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Jasa Pengurusan</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</th>
            </tr>
          </thead>
          <tbody>
            @php
              $monthsList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
              ];
            @endphp
            @forelse($rekap as $row)
              <tr class="border-b border-gray-200 last:border-0 dark:border-gray-800">
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white/90">
                  {{ $monthsList[$row->bulan] ?? '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                  {{ $row->total_kendaraan }} Unit
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                  Rp {{ number_format($row->total_biaya, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                  Rp {{ number_format($row->total_jasa, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90 font-semibold">
                  Rp {{ number_format($row->total_pengeluaran, 0, ',', '.') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                  Tidak ada transaksi biaya KIR untuk tahun {{ $year }}.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
