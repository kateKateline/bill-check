@extends('layout.app')

@section('content')

<section class="bg-linear-to-b from-indigo-50/60 to-slate-50 pt-20 pb-16 px-6">
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

  @if (isset($bill) && $bill->status === 'pending')
<form action="{{ route('bill.analyze', $bill) }}" method="POST" class="text-center mt-8">
    @csrf
    <button
        type="submit"
        class="px-6 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition"
    >
        Analyze Bill
    </button>
</form>
@endif


  <!-- Loading State -->
<div id="loadingState" class="hidden text-center py-24">
    <div class="animate-spin w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full mx-auto mb-4"></div>
    <p class="text-slate-600 font-medium">Menganalisis tagihan...</p>
    <p class="text-xs text-slate-400 mt-1">Ini mungkin membutuhkan beberapa detik</p>
</div>


</section>


@if (isset($results))
<section id="resultState" class="max-w-4xl mx-auto px-6 py-12">

  <div class="flex items-center gap-2 mb-8 border-b border-slate-200 pb-4">
    <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Hasil Analisa AI</h3>
  </div>

@foreach ($results as $row)
    @include('components.bill-row', $row)
@endforeach

</section>
@endif


@endsection