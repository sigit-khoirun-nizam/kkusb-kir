@extends('layouts.app')

@section('content')
  <div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ekspor data histori perpanjangan KIR dalam format Excel atau PDF berdasarkan periode tanggal proses</p>
    </div>

    <!-- Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <form action="{{ route('report.export') }}" method="GET" target="_blank" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <!-- Start Date -->
          <div>
            <x-form.date-picker 
              name="start_date" 
              id="start_date" 
              label="Tanggal Mulai" 
              placeholder="Pilih Tanggal Mulai"
              default-date="{{ request('start_date', date('Y-m-01')) }}"
            />
          </div>

          <!-- End Date -->
          <div>
            <x-form.date-picker 
              name="end_date" 
              id="end_date" 
              label="Tanggal Selesai" 
              placeholder="Pilih Tanggal Selesai"
              default-date="{{ request('end_date', date('Y-m-d')) }}"
            />
          </div>
        </div>

        <!-- Export Actions -->
        <div class="pt-4 border-t border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-end gap-3">
          <button type="submit" name="format" value="excel"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export to Excel
          </button>
          
          <button type="submit" name="format" value="pdf"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Export to PDF
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
