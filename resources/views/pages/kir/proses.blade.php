@extends('layouts.app')

@section('content')
  <div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih kendaraan di bawah ini untuk memproses perpanjangan/pengajuan KIR baru</p>
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
