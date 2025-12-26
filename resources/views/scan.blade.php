@extends('layout.app')

@section('content')

<section class="bg-gradient-to-b from-indigo-50/60 to-slate-50 pt-20 pb-16 px-6">
  <div class="max-w-4xl mx-auto text-center">
    
    <h2 class="text-3xl md:text-4xl font-semibold text-slate-900 mb-4 tracking-tight">
      Cek Transparansi Tagihan Rumah Sakit
    </h2>
    
    <p class="text-slate-600 text-sm md:text-base max-w-xl mx-auto mb-12 leading-relaxed">
      Unggah foto kuitansi atau rincian biaya rumah sakit Anda.  
      Sistem AI akan membantu mengidentifikasi potensi biaya tersembunyi
      atau harga yang tidak wajar.
    </p>

  </div>

  <div class="max-w-xl mx-auto">
    @include('components.upload-box')
  </div>

  <!-- Loading State -->
  <div id="loadingState" class="hidden mt-12">
    <div class="flex items-center justify-center gap-3">
      
      <div class="flex gap-1">
        <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce"></div>
        <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce [animation-delay:-.25s]"></div>
        <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce [animation-delay:-.45s]"></div>
      </div>

      <span class="text-xs font-medium text-indigo-600 uppercase tracking-wider">
        AI sedang memproses
      </span>
    </div>
  </div>

</section>



<section id="resultState" class="hidden max-w-4xl mx-auto px-6 py-12">
    <div class="flex items-center gap-2 mb-8 border-b border-slate-200 pb-4">
        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Hasil Analisa AI</h3>
    </div>

    <div class="mb-8">
        <div class="flex items-center gap-2 mb-3 text-rose-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <h4 class="text-xs font-bold uppercase tracking-widest">Perlu Tindakan Segera</h4>
        </div>
        <div class="grid gap-3">
            @include('components.bill-row', [
                'id' => 'danger1', 'itemName' => 'Minor Surgical Procedure', 'category' => 'Medical Procedure',
                'price' => '1.200.000', 'status' => 'danger', 'label' => 'Potensi Phantom Billing',
                'description' => 'AI mendeteksi prosedur ini tidak tercatat dalam riwayat diagnosis awal Anda. Segera konfirmasi ke bagian administrasi.'
            ])
        </div>
    </div>

    <div class="mb-8">
        <div class="flex items-center gap-2 mb-3 text-amber-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <h4 class="text-xs font-bold uppercase tracking-widest">Tinjau Kembali</h4>
        </div>
        <div class="grid gap-3">
            @include('components.bill-row', [
                'id' => 'review1', 'itemName' => 'Injeksi Vitamin C', 'category' => 'Farmasi',
                'price' => '185.000', 'status' => 'review', 'label' => 'Harga di Atas Rata-rata',
                'description' => 'Harga item ini 45% lebih tinggi dibanding rata-rata pasar (HET).'
            ])
        </div>
    </div>

    <div>
        <div class="flex items-center gap-2 mb-3 text-emerald-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <h4 class="text-xs font-bold uppercase tracking-widest">Normal & Wajar</h4>
        </div>
        <div class="grid gap-3">
            @include('components.bill-row', [
                'id' => 'safe1', 'itemName' => 'Cek Darah Lengkap', 'category' => 'Laboratorium',
                'price' => '450.000', 'status' => 'safe', 'label' => 'Sesuai Standar',
                'description' => 'Biaya sesuai dengan tarif referensi layanan kesehatan wilayah Anda.'
            ])
        </div>
    </div>
</section>

@endsection