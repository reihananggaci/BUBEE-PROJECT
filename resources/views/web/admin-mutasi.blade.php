@extends('layouts.web-admin')
@section('title','Mutasi Stok')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>
    </span>
    <span>Riwayat Mutasi Stok</span>
</h1>
<div class="text-xs text-slate-500 mb-2 mt-0.5">Khusus admin: semua keluar/masuk stok tercatat di sini (audit trail).</div>
<form method="GET" action="/admin/mutasi" class="flex gap-2 mb-3 max-w-md">
<div class="relative flex-1">
    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </span>
    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari keterangan…" class="w-full border rounded-2xl pl-9 pr-3 py-2 text-sm">
</div>
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
<a href="/admin/stok-keluar" class="bg-red-50 text-red-600 font-bold px-4 rounded-2xl text-sm py-2 inline-flex items-center gap-1.5 hover:bg-red-100 transition" style="text-decoration:none">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    <span>Catat Keluar</span>
</a>
</form>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Waktu</th><th class="px-4 py-2">Barang</th><th class="px-4 py-2">Qty</th><th class="px-4 py-2">Stok</th><th class="px-4 py-2">Keterangan</th></tr></thead>
<tbody>
@foreach($mutasi as $m)<tr class="border-t border-slate-100">
<td class="px-4 py-2 text-xs whitespace-nowrap">{{ $m->created_at->format('d M H:i') }}</td>
<td class="px-4 py-2"><b>{{ $m->product->nama ?? '(terhapus)' }}</b> @if($m->variant)<span class="font-bold text-purple-700">({{ $m->variant->label() }})</span>@endif</td>
<td class="px-4 py-2"><span class="font-extrabold px-2 py-1 rounded-xl {{ $m->tipe==='masuk'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-600' }}">{{ $m->tipe==='masuk'?'+':'−' }}{{ $m->qty }}</span></td>
<td class="px-4 py-2 text-xs text-slate-400 whitespace-nowrap">{{ $m->stok_sebelum }}→{{ $m->stok_sesudah }}</td>
<td class="px-4 py-2 text-xs text-slate-500">{{ $m->keterangan }}</td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">
@foreach($mutasi as $m)
<div class="bg-white rounded-3xl shadow p-3 mb-1.5 text-xs flex gap-2 items-center">
<span class="font-extrabold px-2 py-1 rounded-xl {{ $m->tipe==='masuk'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-600' }}">{{ $m->tipe==='masuk'?'+':'−' }}{{ $m->qty }}</span>
<div class="flex-1"><b>{{ $m->product->nama ?? '(terhapus)' }}</b> @if($m->variant)<span class="font-bold text-purple-700">({{ $m->variant->label() }})</span>@endif <span class="text-slate-400">{{ $m->stok_sebelum }}→{{ $m->stok_sesudah }}</span><br><span class="text-slate-500">{{ $m->keterangan }} • {{ $m->created_at->format('d M H:i') }}</span></div>
</div>
@endforeach
</div>
<div class="mt-2">{{ $mutasi->links() }}</div>
@endsection
