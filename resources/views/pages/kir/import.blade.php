@extends('layouts.app')

@section('content')
  <div class="space-y-6 max-w-xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Import data kendaraan masal menggunakan file Excel</p>
      </div>
      <a href="{{ route('kir.monitoring') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
        Batal
      </a>
    </div>

    <!-- Alert Status -->
    @if(session('success'))
      <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/20 dark:text-green-400">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-400">
        {{ session('error') }}
      </div>
    @endif

    <!-- Import Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <form action="{{ route('kir.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
          <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Pilih File Excel <span class="text-red-500">*</span>
          </label>
          <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400">
          <p class="mt-1 text-xs text-gray-400">Format: .xlsx, .xls, .csv (Maksimal: 10MB)</p>
        </div>

        <div class="mb-6 rounded-lg bg-blue-50/50 p-4 dark:bg-blue-900/10">
          <h4 class="text-xs font-semibold text-blue-900 dark:text-blue-400 mb-2">Panduan Kolom Excel:</h4>
          <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
            Pastikan header kolom pertama file Excel Anda memiliki susunan berikut:<br>
            <strong>nomor_pintu, nopol, jenis, deskripsi, exp_kir, biaya, jasa, no_pr, no_spk</strong>
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            * Data kendaraan lama yang memiliki Nopol sama akan diperbarui datanya secara otomatis.
          </p>
        </div>

        <div class="flex items-center justify-end">
          <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white hover:bg-brand-600 transition-colors w-full sm:w-auto">
            Mulai Import Data
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
