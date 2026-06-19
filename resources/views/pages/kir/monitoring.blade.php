@extends('layouts.app')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pantau masa berlaku KIR kendaraan berdasarkan status keamanan</p>
      </div>
      <div class="flex flex-col gap-3 md:flex-row">
        <!-- Status Filter -->
        <form action="{{ route('kir.monitoring') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
          <div x-data="{ isOptionSelected: {{ request('status') ? 'true' : 'false' }} }" class="relative z-20 bg-transparent">
            <select name="status" onchange="this.form.submit()"
              class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
              :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
              <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Semua Status</option>
              <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">🟢 Aman (> 60 Hari)</option>
              <option value="warning" {{ request('status') == 'warning' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">🟡 Warning (<= 60 Hari)</option>
              <option value="urgent" {{ request('status') == 'urgent' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">🔴 Urgent (<= 30 Hari / Expired)</option>
            </select>
            <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
              <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </span>
          </div>

          <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nopol/no pintu..."
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 md:w-64">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          @if(request('search') || request('status'))
            <a href="{{ route('kir.monitoring') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
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
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">No</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">No. Pintu</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">No. Polisi</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Jenis</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Exp. KIR</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Hari</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kendaraans as $kendaraan)
              @php
                $daysRemaining = $kendaraan->exp_kir ? now()->startOfDay()->diffInDays($kendaraan->exp_kir, false) : null;
              @endphp
              <tr class="border-b border-gray-200 last:border-0 dark:border-gray-800">
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white/90">
                  {{ ($kendaraans->currentPage() - 1) * $kendaraans->perPage() + $loop->iteration }}
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $kendaraan->nomor_pintu }}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $kendaraan->nopol }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">{{ $kendaraan->jenis }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                  {{ $kendaraan->exp_kir ? $kendaraan->exp_kir->format('m/d/Y') : '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                  @if($daysRemaining === null)
                    -
                  @elseif($daysRemaining < 0)
                    <span class="text-red-500 font-semibold">Lewat {{ abs($daysRemaining) }} hari</span>
                  @elseif($daysRemaining == 0)
                    <span class="text-red-500 font-semibold">Hari ini</span>
                  @else
                    {{ $daysRemaining }} hari lagi
                  @endif
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                    @if($kendaraan->status_color == 'green') bg-green-500/10 text-green-500 dark:bg-green-500/20 dark:text-green-400
                    @elseif($kendaraan->status_color == 'yellow') bg-yellow-500/10 text-yellow-500 dark:bg-yellow-500/20 dark:text-yellow-400
                    @elseif($kendaraan->status_color == 'red') bg-red-500/10 text-red-500 dark:bg-red-500/20 dark:text-red-400
                    @else bg-gray-500/10 text-gray-500 dark:bg-gray-500/20 dark:text-gray-400
                    @endif">
                    @if($kendaraan->status_color == 'green') 🟢 Aman
                    @elseif($kendaraan->status_color == 'yellow') 🟡 Warning
                    @elseif($kendaraan->status_color == 'red') 🔴 Urgent
                    @else Abu-abu
                    @endif
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-1.5">
                    <a href="{{ route('kendaraan.show', $kendaraan) }}" 
                      class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-100 transition-colors dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20" 
                      title="Lihat Detail Kendaraan">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      Detail
                    </a>
                    
                    @if($kendaraan->documents->isNotEmpty())
                      @php $latestDoc = $kendaraan->documents->sortByDesc('created_at')->first(); @endphp
                      <a href="{{ Storage::url($latestDoc->path) }}" target="_blank" 
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-100 transition-colors dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20" 
                        title="Buka Scan Dokumen PDF">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        PDF
                      </a>
                    @endif
                    
                    <a href="{{ route('kir.proses-form', $kendaraan) }}" 
                      class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-600 hover:bg-green-100 transition-colors dark:bg-green-500/10 dark:text-green-400 dark:hover:bg-green-500/20" 
                      title="Proses Perpanjangan KIR">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m-3-3v12"></path>
                      </svg>
                      Proses KIR
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                  Tidak ada data kendaraan ditemukan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($kendaraans->hasPages())
        <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-800">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $kendaraans->firstItem() }} sampai {{ $kendaraans->lastItem() }} dari {{ $kendaraans->total() }} data
          </p>
          {{ $kendaraans->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
