@extends('layouts.web-admin')
@section('title','Verifikasi Bayar')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
    </span>
    <span>Verifikasi Pembayaran</span>
</h1>
<div class="text-xs text-slate-500 mb-3 mt-0.5">Khusus admin: setujui bukti transfer / tolak. Belum bayar ({{ $menunggu }}) ada di Kelola Order.</div>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Order</th><th class="px-4 py-2">Pembeli</th><th class="px-4 py-2">Item</th><th class="px-4 py-2">Bukti</th><th class="px-4 py-2 text-right">Total</th><th class="px-4 py-2">Aksi</th></tr></thead>
<tbody>
@foreach($orders as $o)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold whitespace-nowrap">{{ $o->kode }}<br><span class="font-normal text-[11px] text-slate-400">{{ $o->created_at->format('d M H:i') }}</span></td>
<td class="px-4 py-2">{{ $o->user->name ?? '' }}</td>
<td class="px-4 py-2 text-xs">@foreach($o->items as $it)<div>- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }}</div>@endforeach</td>
<td class="px-4 py-2">@if($o->bukti_bayar)<a href="/storage/{{ $o->bukti_bayar }}" target="_blank"><img src="/storage/{{ $o->bukti_bayar }}" class="w-20 h-20 rounded-xl object-cover bg-slate-100" alt="bukti"></a>@else<span class="text-xs text-slate-400">-</span>@endif</td>
<td class="px-4 py-2 text-right font-bold whitespace-nowrap">Rp {{ number_format($o->total_bayar) }}</td>
<td class="px-4 py-2"><div class="flex gap-1">
<form method="POST" action="/admin/verifikasi/{{ $o->id }}/setuju" class="flex items-center gap-1">@csrf<input name="resi" required maxlength="50" placeholder="No resi" class="w-24 rounded-xl border border-slate-200 px-2 py-1 text-xs"><button class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-1.5 px-3 rounded-2xl text-xs transition">Setuju</button></form>
<form method="POST" action="/admin/verifikasi/{{ $o->id }}/tolak" onsubmit="return confirm('Tolak bukti? Stok kembali.')">@csrf<button class="bg-red-500 hover:bg-red-600 text-white font-bold py-1.5 px-3 rounded-2xl text-xs transition">Tolak</button></form>
</div></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3">
@foreach($orders as $o)
<div class="bg-white rounded-3xl shadow p-4 mb-2 text-sm">
<div class="flex justify-between"><b>{{ $o->kode }}</b><b class="text-fuchsia-700">Rp {{ number_format($o->total_bayar) }}</b></div>
<div class="text-xs text-slate-500">{{ $o->user->name ?? '' }} • {{ $o->created_at->format('d M H:i') }}</div>
@foreach($o->items as $it)<div class="text-xs">- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }}</div>@endforeach
@if($o->bukti_bayar)<a href="/storage/{{ $o->bukti_bayar }}" target="_blank" class="block mt-1 rounded-2xl overflow-hidden border"><img src="/storage/{{ $o->bukti_bayar }}" class="w-full max-h-56 object-contain bg-slate-100" alt="bukti"></a>@endif
<div class="flex gap-1.5 mt-2">
<form method="POST" action="/admin/verifikasi/{{ $o->id }}/setuju" class="flex-1">@csrf<input name="resi" required maxlength="50" placeholder="No resi" class="w-full rounded-xl border border-slate-200 px-2 py-1.5 text-xs mb-1"><button class="w-full bg-emerald-500 text-white font-bold py-2.5 rounded-2xl text-xs">Setuju → Kirim</button></form>
<form method="POST" action="/admin/verifikasi/{{ $o->id }}/tolak" class="flex-1" onsubmit="return confirm('Tolak bukti? Stok kembali.')">@csrf<button class="w-full bg-red-500 text-white font-bold py-2.5 rounded-2xl text-xs">Tolak + Batal</button></form>
</div>
</div>
@endforeach
</div>
@if($orders->isEmpty())<div class="bg-white rounded-3xl p-8 text-center text-sm mt-3 text-slate-400">Tidak ada bukti menunggu verifikasi.</div>@endif

{{-- Navigasi Panah Samping (Pagination) --}}
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
