@extends('layouts.app')

@section('content')
  <div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambahkan kendaraan baru ke sistem</p>
      </div>
      <a href="{{ route('kendaraan.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
        Kembali
      </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <form action="{{ route('kendaraan.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <!-- Nomor Pintu -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nomor Pintu <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nomor_pintu" value="{{ old('nomor_pintu') }}" required placeholder="BL268"
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('nomor_pintu') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Nopol -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nomor Polisi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nopol" value="{{ old('nopol') }}" required placeholder="B 1234 ABC"
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('nopol') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Jenis Kendaraan -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Jenis Kendaraan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="jenis" value="{{ old('jenis') }}" required placeholder="TRUK / MIXER / MOBIL"
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('jenis') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Status -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Status <span class="text-red-500">*</span>
            </label>
            <div x-data="{ isOptionSelected: true }" class="relative z-20 bg-transparent">
              <select name="status" required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Aktif</option>
                <option value="jatuh_tempo" {{ old('status') == 'jatuh_tempo' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Jatuh Tempo</option>
                <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Terlambat</option>
                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Selesai</option>
              </select>
              <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
            </div>
            @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Deskripsi -->
          <div class="sm:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Deskripsi Kendaraan
            </label>
            <textarea name="deskripsi" placeholder="Tulis keterangan kendaraan..." rows="3"
              class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <div class="sm:col-span-2 border-t border-gray-200 pt-4 dark:border-gray-800">
            <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-4">Informasi KIR Aktif (Opsional)</h3>
          </div>

          <!-- Exp KIR -->
          <div>
            <x-form.date-picker 
              name="exp_kir" 
              id="exp_kir" 
              label="Tanggal Exp KIR" 
              placeholder="Pilih Tanggal"
              default-date="{{ old('exp_kir') }}"
            />
            @error('exp_kir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Biaya -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Biaya Resmi
            </label>
            <input type="text" name="biaya" 
              x-data="{ 
                val: '{{ old('biaya', 0) }}',
                format(value) {
                  if (!value) return '';
                  let clean = value.toString().replace(/\D/g, '');
                  if (clean === '') return '';
                  clean = parseInt(clean, 10).toString();
                  return clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
              }"
              x-init="val = format(val)"
              x-model="val"
              @input="val = format($event.target.value)"
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('biaya') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Jasa -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Jasa Pengurusan
            </label>
            <input type="text" name="jasa" 
              x-data="{ 
                val: '{{ old('jasa', 0) }}',
                format(value) {
                  if (!value) return '';
                  let clean = value.toString().replace(/\D/g, '');
                  if (clean === '') return '';
                  clean = parseInt(clean, 10).toString();
                  return clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
              }"
              x-init="val = format(val)"
              x-model="val"
              @input="val = format($event.target.value)"
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('jasa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- No PR -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nomor PR
            </label>
            <input type="text" name="no_pr" value="{{ old('no_pr') }}" placeholder="PR-XXXXX"
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('no_pr') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- No SPK -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nomor SPK
            </label>
            <input type="text" name="no_spk" value="{{ old('no_spk') }}" placeholder="SPK-XXXXX"
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('no_spk') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex items-center justify-end gap-3">
          <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white hover:bg-brand-600">
            Simpan Kendaraan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
