@extends('layout.app')

@section('content')
<section class="bg-slate-50 py-16 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-800">History Scan Tagihan RS</h1>
            <p class="text-slate-500 mt-2">
                Riwayat upload dan analisis tagihan rumah sakit
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-slate-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">File</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bills as $bill)
                        <tr class="border-t hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-500">
                                {{ $bill->created_at->format('d M Y H:i') }}
                            </td>

                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $bill->original_filename ?? 'Hospital Bill' }}
                            </td>

                            <td class="px-6 py-4">
                                @if ($bill->status === 'completed')
                                    <span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-700">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right font-semibold text-slate-800">
                                {{ $bill->total_amount ? 'Rp ' . number_format($bill->total_amount) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                Belum ada riwayat scan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $bills->links() }}
        </div>
    </div>
</section>
@endsection
