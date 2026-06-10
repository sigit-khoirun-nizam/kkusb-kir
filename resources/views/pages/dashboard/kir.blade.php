@extends('layouts.app')

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Statistics Cards -->
    <div class="col-span-12 space-y-6">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <!-- Total Vehicles -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kendaraan</p>
          <h4 class="mt-1 text-title-sm font-semibold text-gray-800 dark:text-white/90">{{ $totalVehicles }}</h4>
        </div>

        <!-- KIR Aktif -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">KIR Aktif</p>
          <h4 class="mt-1 text-title-sm font-semibold text-green-600 dark:text-green-400">{{ $activeKIR }}</h4>
        </div>

        <!-- KIR Expired -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">KIR Expired</p>
          <h4 class="mt-1 text-title-sm font-semibold text-red-600 dark:text-red-400">{{ $expiredKIR }}</h4>
        </div>

        <!-- KIR Bulan Ini -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">KIR Bulan Ini</p>
          <h4 class="mt-1 text-title-sm font-semibold text-amber-500 dark:text-amber-400">{{ $currentMonthKIR }}</h4>
        </div>

        <!-- KIR 3 Bulan Depan -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">KIR 3 Bulan Depan</p>
          <h4 class="mt-1 text-title-sm font-semibold text-blue-500 dark:text-blue-400">{{ $threeMonthsKIR }}</h4>
        </div>

        <!-- Total Biaya -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Biaya KIR</p>
          <h4 class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">Rp {{ $totalCost }}</h4>
        </div>
      </div>
    </div>

    <!-- Alert KIR Records -->
    <div class="col-span-12">
      <div class="rounded-xl border border-red-200 bg-white shadow-sm dark:border-red-900/30 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between border-b border-red-100 px-6 py-4 dark:border-red-900/20">
          <div class="flex items-center gap-2">
            <span class="flex h-3 w-3 rounded-full bg-red-500 animate-pulse"></span>
            <h3 class="text-lg font-semibold text-red-800 dark:text-red-400">Peringatan Jatuh Tempo KIR</h3>
          </div>
          <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800 dark:bg-red-900/30 dark:text-red-400">
            {{ $activeAlerts->total() }} Kendaraan Perlu Tindakan
          </span>
        </div>
        <div class="p-6">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200 text-left dark:border-gray-800">
                  <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">No. Pintu</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">No. Polisi</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jenis</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Exp. KIR</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Hari</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Pesan Alert</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($activeAlerts as $alert)
                  @php
                    $daysRemaining = now()->startOfDay()->diffInDays($alert->exp_kir, false);
                  @endphp
                  <tr class="border-b border-gray-200 last:border-0 dark:border-gray-800 hover:bg-gray-50/50 dark:hover:bg-white/[0.01]">
                    <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $alert->nomor_pintu ?? '-' }}</td>
                    <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-white/90">{{ $alert->nopol ?? '-' }}</td>
                    <td class="px-4 py-4 text-sm text-gray-800 dark:text-white/90">{{ $alert->jenis ?? '-' }}</td>
                    <td class="px-4 py-4 text-sm text-gray-800 dark:text-white/90">
                      {{ $alert->exp_kir ? $alert->exp_kir->format('m/d/Y') : '-' }}
                    </td>
                    <td class="px-4 py-4 text-sm">
                      @if($daysRemaining < 0)
                        <span class="text-red-500 font-semibold">Lewat {{ abs($daysRemaining) }} hari</span>
                      @elseif($daysRemaining == 0)
                        <span class="text-red-500 font-semibold">Hari ini</span>
                      @else
                        <span class="text-gray-700 dark:text-gray-300">{{ $daysRemaining }} hari lagi</span>
                      @endif
                    </td>
                    <td class="px-4 py-4 text-sm">
                      @if($daysRemaining < 0)
                        <span class="inline-flex rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">KIR Kedaluwarsa!</span>
                      @elseif($daysRemaining <= 30)
                        <span class="inline-flex rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">Segera proses KIR (H-30)</span>
                      @elseif($daysRemaining <= 60)
                        <span class="inline-flex rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">Kendaraan mulai masuk periode KIR (H-60)</span>
                      @endif
                    </td>
                    <td class="px-4 py-4 text-sm">
                      <div class="flex items-center gap-2">
                        <a href="{{ route('kendaraan.show', $alert) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500/10 p-2 text-blue-500 hover:bg-blue-500/20 dark:bg-blue-500/20 dark:text-blue-400">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z" fill="currentColor"/>
                          </svg>
                        </a>
                        <a href="{{ route('kir.proses-form', $alert) }}" class="inline-flex items-center justify-center rounded-lg bg-green-500/10 p-2 text-green-500 hover:bg-green-500/20 dark:bg-green-500/20 dark:text-green-400">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 13H13V19H11V13H5V11H11V5H13V11H19V13Z" fill="currentColor"/>
                          </svg>
                        </a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                      Semua KIR kendaraan dalam status aman (tidak ada alert).
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($activeAlerts->hasPages())
            <div class="flex items-center justify-between border-t border-gray-200 mt-4 pt-4 dark:border-gray-800">
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Menampilkan {{ $activeAlerts->firstItem() }} sampai {{ $activeAlerts->lastItem() }} dari {{ $activeAlerts->total() }} data
              </p>
              {{ $activeAlerts->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
