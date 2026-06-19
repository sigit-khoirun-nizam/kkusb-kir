@extends('layouts.app')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola master data biaya tambahan opsional untuk proses KIR</p>
      </div>
      <div class="flex flex-col gap-3 md:flex-row">
        <!-- Search Form -->
        <form action="{{ route('biaya-tambahan.index') }}" method="GET" class="flex items-center">
          <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari biaya tambahan..."
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 md:w-64">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          @if(request('search'))
            <a href="{{ route('biaya-tambahan.index') }}" class="ml-2 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
              Clear
            </a>
          @endif
        </form>

        <a href="{{ route('biaya-tambahan.create') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
          <svg class="mr-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 4.16667V15.8333M4.16667 10H15.8333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Tambah Biaya Tambahan
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
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Nama Biaya</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
              <th class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($feeTypes as $feeType)
              <tr class="border-b border-gray-200 last:border-0 dark:border-gray-800">
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white/90">
                  {{ ($feeTypes->currentPage() - 1) * $feeTypes->perPage() + $loop->iteration }}
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white/90">
                  {{ $feeType->name }}
                </td>
                <td class="px-6 py-4">
                  <form action="{{ route('biaya-tambahan.toggle', $feeType) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" title="Klik untuk mengubah status"
                      class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold transition-all cursor-pointer
                      @if($feeType->status === 'aktif') bg-green-500/10 text-green-500 hover:bg-green-500/20 dark:bg-green-500/20 dark:text-green-400
                      @else bg-gray-500/10 text-gray-500 hover:bg-gray-500/20 dark:bg-gray-500/20 dark:text-gray-400
                      @endif">
                      {{ ucfirst($feeType->status) }}
                    </button>
                  </form>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <a href="{{ route('biaya-tambahan.edit', $feeType) }}" class="inline-flex items-center justify-center rounded-lg bg-yellow-500/10 p-2 text-yellow-500 hover:bg-yellow-500/20 dark:bg-yellow-500/20 dark:text-yellow-400" title="Edit">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 17.25V21H6.75L17.81 9.94L14.06 6.19L3 17.25ZM20.71 7.04C21.1 6.65 21.1 6.02 20.71 5.63L18.37 3.29C17.98 2.9 17.35 2.9 16.96 3.29L15.13 5.12L18.88 8.87L20.71 7.04Z" fill="currentColor"/>
                      </svg>
                    </a>
                    <button type="button" onclick="deleteFeeType('{{ route('biaya-tambahan.destroy', $feeType) }}')" class="inline-flex items-center justify-center rounded-lg bg-red-500/10 p-2 text-red-500 hover:bg-red-500/20 dark:bg-red-500/20 dark:text-red-400">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V7H6V19ZM19 4H15.5L14.5 3H9.5L8.5 4H5V6H19V4Z" fill="currentColor"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                  Belum ada data biaya tambahan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($feeTypes->hasPages())
        <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-800">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $feeTypes->firstItem() }} sampai {{ $feeTypes->lastItem() }} dari {{ $feeTypes->total() }} data
          </p>
          {{ $feeTypes->links() }}
        </div>
      @endif
    </div>
  </div>

  <script>
    function deleteFeeType(url) {
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data biaya tambahan yang dihapus tidak dapat dikembalikan!',
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
@endsection
