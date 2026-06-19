@extends('layouts.app')

@section('content')
  <div class="space-y-6 max-w-xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambahkan jenis biaya tambahan baru</p>
      </div>
      <a href="{{ route('biaya-tambahan.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
        Kembali
      </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <form action="{{ route('biaya-tambahan.store') }}" method="POST">
        @csrf
        <div class="space-y-6">
          <!-- Nama Biaya -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nama Biaya <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: STIKER PEMANTUL"
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
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
                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }} class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Nonaktif</option>
              </select>
              <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
            </div>
            @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex items-center justify-end gap-3">
          <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white hover:bg-brand-600">
            Simpan Biaya Tambahan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
