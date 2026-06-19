@extends('layouts.app')

@section('content')
  <div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih kendaraan di bawah ini untuk memproses perpanjangan/pengajuan KIR baru</p>
      </div>
      <div>
        <!-- Search Form -->
        <form action="{{ route('kir.proses') }}" method="GET" class="flex items-center">
          <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kendaraan..."
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 md:w-64">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          @if(request('search'))
            <a href="{{ route('kir.proses') }}" class="ml-2 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
              Clear
            </a>
          @endif
        </form>
      </div>
    </div>

    <!-- Vehicles List Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
      @forelse($kendaraans as $kendaraan)
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] flex flex-col justify-between h-48 hover:border-brand-500 dark:hover:border-brand-500 transition-colors">
          <div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-semibold text-gray-400 uppercase">#{{ $kendaraan->nomor_pintu }}</span>
              <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                @if($kendaraan->status_color == 'green') bg-green-500/10 text-green-500 dark:bg-green-500/20 dark:text-green-400
                @elseif($kendaraan->status_color == 'yellow') bg-yellow-500/10 text-yellow-500 dark:bg-yellow-500/20 dark:text-yellow-400
                @elseif($kendaraan->status_color == 'red') bg-red-500/10 text-red-500 dark:bg-red-500/20 dark:text-red-400
                @else bg-gray-500/10 text-gray-500 dark:bg-gray-500/20 dark:text-gray-400
                @endif">
                {{ $kendaraan->status_label }}
              </span>
            </div>
            <h3 class="mt-2 text-lg font-bold text-gray-800 dark:text-white">{{ $kendaraan->nopol }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $kendaraan->jenis }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
              Exp KIR: {{ $kendaraan->exp_kir ? $kendaraan->exp_kir->format('m/d/Y') : 'Belum Pernah' }}
            </p>
          </div>
          <div class="mt-4">
            <a href="{{ route('kir.proses-form', $kendaraan) }}" class="w-full inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
              Proses KIR Baru
            </a>
          </div>
        </div>
      @empty
        <div class="col-span-full rounded-xl border border-dashed border-gray-300 p-8 text-center dark:border-gray-700">
          <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada data kendaraan untuk diproses.</p>
        </div>
      @endforelse
    </div>
    @if($kendaraans->hasPages())
      <div class="flex items-center justify-between border-t border-gray-200 mt-6 pt-4 dark:border-gray-800">
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Menampilkan {{ $kendaraans->firstItem() }} sampai {{ $kendaraans->lastItem() }} dari {{ $kendaraans->total() }} data
        </p>
        {{ $kendaraans->links() }}
      </div>
    @endif
  </div>
@endsection
