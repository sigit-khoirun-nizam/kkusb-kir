@extends('layouts.app')

@section('content')
  <div class="space-y-6" x-data="{ showImportModal: false }">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola master data kendaraan</p>
      </div>
      <div class="flex flex-col gap-3 md:flex-row">
        <!-- Search Form -->
        <form action="{{ route('kendaraan.index') }}" method="GET" class="flex items-center">
          <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kendaraan..."
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 md:w-64">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          @if(request('search'))
            <a href="{{ route('kendaraan.index') }}" class="ml-2 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
              Clear
            </a>
          @endif
        </form>
        
        <!-- Action Buttons -->
        <a href="{{ route('kendaraan.template') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-850 dark:text-gray-300 dark:hover:bg-gray-800">
          <svg class="mr-2 text-gray-500" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 15V3m0 12l-4-4m4 4l4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Contoh Excel
        </a>
        <button @click="showImportModal = true" class="inline-flex items-center justify-center rounded-lg border border-brand-500 bg-brand-500/10 px-4 py-2.5 text-sm font-medium text-brand-600 hover:bg-brand-500 hover:text-white dark:text-brand-400">
          <svg class="mr-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Import Excel
        </button>
        <a href="{{ route('kendaraan.create') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
          <svg class="mr-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 4.16667V15.8333M4.16667 10H15.8333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Tambah Kendaraan
        </a>
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
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Total Biaya</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kendaraans as $kendaraan)
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
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">Rp {{ $kendaraan->formatted_total }}</td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                    @if($kendaraan->status_color == 'green') bg-green-500/10 text-green-500 dark:bg-green-500/20 dark:text-green-400
                    @elseif($kendaraan->status_color == 'yellow') bg-yellow-500/10 text-yellow-500 dark:bg-yellow-500/20 dark:text-yellow-400
                    @elseif($kendaraan->status_color == 'red') bg-red-500/10 text-red-500 dark:bg-red-500/20 dark:text-red-400
                    @else bg-gray-500/10 text-gray-500 dark:bg-gray-500/20 dark:text-gray-400
                    @endif">
                    {{ $kendaraan->status_label }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <a href="{{ route('kendaraan.show', $kendaraan) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-500/10 p-2 text-blue-500 hover:bg-blue-500/20 dark:bg-blue-500/20 dark:text-blue-400">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z" fill="currentColor"/>
                      </svg>
                    </a>
                    <a href="{{ route('kendaraan.edit', $kendaraan) }}" class="inline-flex items-center justify-center rounded-lg bg-yellow-500/10 p-2 text-yellow-500 hover:bg-yellow-500/20 dark:bg-yellow-500/20 dark:text-yellow-400">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 17.25V21H6.75L17.81 9.94L14.06 6.19L3 17.25ZM20.71 7.04C21.1 6.65 21.1 6.02 20.71 5.63L18.37 3.29C17.98 2.9 17.35 2.9 16.96 3.29L15.13 5.12L18.88 8.87L20.71 7.04Z" fill="currentColor"/>
                      </svg>
                    </a>
                    <button type="button" onclick="deleteKendaraan('{{ route('kendaraan.destroy', $kendaraan) }}')" class="inline-flex items-center justify-center rounded-lg bg-red-500/10 p-2 text-red-500 hover:bg-red-500/20 dark:bg-red-500/20 dark:text-red-400">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V7H6V19ZM19 4H15.5L14.5 3H9.5L8.5 4H5V6H19V4Z" fill="currentColor"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                  Belum ada data kendaraan.
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

  <script>
    function deleteKendaraan(url) {
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data kendaraan yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = url;
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          const csrfInput = document.createElement('input');
          csrfInput.type = 'hidden';
          csrfInput.name = '_token';
          csrfInput.value = csrfToken;
          form.appendChild(csrfInput);
          const methodInput = document.createElement('input');
          methodInput.type = 'hidden';
          methodInput.name = '_method';
          methodInput.value = 'DELETE';
          form.appendChild(methodInput);
          document.body.appendChild(form);
          form.submit();
        }
      });
    }
  </script>

  <!-- Modal Import -->
  <div x-show="showImportModal" class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto" style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showImportModal = false"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-md transform overflow-hidden rounded-xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
      <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-800">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Import Data Kendaraan</h3>
        <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

      <form action="{{ route('kendaraan.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">Pilih File Excel</label>
          <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400">
          <p class="mt-1 text-xs text-gray-400">Format: .xlsx, .xls, .csv (Maksimal: 10MB)</p>
        </div>

        <div class="rounded-lg bg-blue-50/50 p-4 dark:bg-blue-900/10">
          <h4 class="text-xs font-semibold text-blue-900 dark:text-blue-400 mb-1">Panduan Kolom Excel:</h4>
          <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
            Pastikan header kolom pertama file Excel Anda memiliki susunan berikut:<br>
            <strong>nomor_pintu, nopol, jenis, deskripsi, exp_kir, biaya, jasa, no_pr, no_spk</strong>
          </p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-200 dark:border-gray-800">
          <button type="button" @click="showImportModal = false" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            Batal
            </button>
          <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
            Upload & Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
