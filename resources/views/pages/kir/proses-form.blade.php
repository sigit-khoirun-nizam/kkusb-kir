@extends('layouts.app')

@section('content')
  <div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Proses pembaruan KIR untuk kendaraan {{ $kendaraan->nomor_pintu }} ({{ $kendaraan->nopol }})</p>
      </div>
      <a href="{{ route('kir.proses') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
        Batal
      </a>
    </div>

    <!-- Info Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] grid grid-cols-2 gap-4">
      <div>
        <span class="text-xs text-gray-400 uppercase block">No Polisi / No Pintu</span>
        <span class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $kendaraan->nopol }} / {{ $kendaraan->nomor_pintu }}</span>
      </div>
      <div>
        <span class="text-xs text-gray-400 uppercase block">Exp KIR Lama</span>
        <span class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $kendaraan->exp_kir ? $kendaraan->exp_kir->format('m/d/Y') : 'Belum Pernah' }}</span>
      </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
      <form action="{{ route('kir.proses-store', $kendaraan) }}" method="POST" enctype="multipart/form-data"
        x-data="{
          tanggalProses: '{{ old('tanggal_proses', date('m/d/Y')) }}',
          expKirBaru: '{{ old('exp_kir_baru') }}',
          recommendedDate: '-',
          
          updateRecommendation() {
            if (this.tanggalProses) {
              const date = new Date(this.tanggalProses);
              date.setMonth(date.getMonth() + 6);
              
              const yyyy = date.getFullYear();
              let mm = date.getMonth() + 1;
              let dd = date.getDate();
              
              if (dd < 10) dd = '0' + dd;
              if (mm < 10) mm = '0' + mm;
              
              this.recommendedDate = mm + '/' + dd + '/' + yyyy;
              
              const newDateVal = mm + '/' + dd + '/' + yyyy;
              if (!this.expKirBaru) {
                this.expKirBaru = newDateVal;
                this.$nextTick(() => {
                  const fp = document.getElementById('exp_kir_baru')?._flatpickr;
                  if (fp) {
                    fp.setDate(newDateVal, false);
                  }
                });
              }
            }
          }
        }"
        x-init="updateRecommendation()"
        @date-change="
          if ($event.detail.instance.element.id === 'tanggal_proses') {
            tanggalProses = $event.detail.dateStr;
            updateRecommendation();
          } else if ($event.detail.instance.element.id === 'exp_kir_baru') {
            expKirBaru = $event.detail.dateStr;
          }
        "
      >
        @csrf
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <!-- Tanggal Proses -->
          <div>
            <x-form.date-picker 
              name="tanggal_proses" 
              id="tanggal_proses" 
              label="Tanggal Proses KIR" 
              placeholder="Pilih Tanggal"
              default-date="{{ old('tanggal_proses', date('Y-m-d')) }}"
            />
            @error('tanggal_proses') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Exp KIR Baru -->
          <div>
            <x-form.date-picker 
              name="exp_kir_baru" 
              id="exp_kir_baru" 
              label="Tanggal Exp KIR Baru" 
              placeholder="Pilih Tanggal"
              default-date="{{ old('exp_kir_baru') }}"
            />
            <p class="mt-1 text-[11px] text-gray-400">Rekomendasi (EXP KIR + 6 Bulan): <span x-text="recommendedDate">-</span></p>
            @error('exp_kir_baru') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Biaya Resmi -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Biaya Resmi <span class="text-red-500">*</span>
            </label>
            <input type="number" name="biaya" value="{{ old('biaya', 0) }}" min="0" required
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('biaya') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- Jasa Pengurusan -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Jasa Pengurusan <span class="text-red-500">*</span>
            </label>
            <input type="number" name="jasa" value="{{ old('jasa', 0) }}" min="0" required
              class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            @error('jasa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>

          <!-- No PR -->
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Nomor Purchase Request (PR)
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

          <!-- Dokumen Scan -->
          <div class="sm:col-span-2">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Upload Scan Dokumen KIR / Invoice (PDF/Image)
            </label>
            <input type="file" name="dokumen" accept=".pdf,.jpg,.jpeg,.png"
              class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400">
            @error('dokumen') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex items-center justify-end gap-3">
          <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-5 py-3 text-sm font-medium text-white hover:bg-brand-600">
            Simpan & Perbarui KIR
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
