<section class="max-w-4xl mx-auto px-6 py-16 border-t border-slate-200">
    <div class="text-center mb-10">
        <h3 class="text-2xl font-bold text-slate-800">Frequently Asked Questions</h3>
        <p class="text-sm text-slate-500 mt-2">Pahami bagaimana AI kami bekerja untuk Anda</p>
    </div>

    <div class="grid gap-4 max-w-2xl mx-auto">
        <div class="bg-white border border-slate-200 rounded-xl p-4 cursor-pointer hover:border-indigo-200 transition" onclick="toggleDetail('faq1')">
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-slate-700">Bagaimana AI menentukan tagihan yang tidak wajar?</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            <p id="faq1" class="hidden text-xs text-slate-500 mt-3 leading-relaxed">
                AI kami membandingkan data tagihan Anda dengan database rata-rata biaya medis regional, regulasi BPJS/Asuransi, dan standar harga obat eceran tertinggi (HET).
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-4 cursor-pointer hover:border-indigo-200 transition" onclick="toggleDetail('faq2')">
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-slate-700">Apakah data tagihan saya aman?</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
            <p id="faq2" class="hidden text-xs text-slate-500 mt-3 leading-relaxed">
                Tentu. Seluruh data yang diunggah akan diproses secara anonim dan tidak akan disebarluaskan kepada pihak ketiga sesuai kebijakan privasi kami.
            </p>
        </div>
    </div>
</section>