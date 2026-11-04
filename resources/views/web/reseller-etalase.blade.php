@extends('layouts.web')
@section('title','Etalase Modal')
@section('content')
<a href="/reseller" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Reseller Center</a>
<h1 class="font-extrabold text-2xl text-slate-900 mt-2 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-cyan-100 text-cyan-800 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
    </span>
    <span>Etalase Harga Modal</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Harga yang kamu bayar vs harga jual saran. Margin = cuanmu.</div>
<form method="GET" action="/reseller/etalase" class="flex gap-2 mb-3 max-w-xs">
<select name="sort" onchange="this.form.submit()" class="flex-1 border border-slate-200 rounded-2xl px-3 py-2 text-sm font-bold bg-white text-slate-700">
<option value="margin" {{ $sort==='margin'?'selected':'' }}>Margin terbesar</option>
<option value="stok" {{ $sort==='stok'?'selected':'' }}>Stok terbanyak</option>
</select>
</form>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Barang</th><th class="px-4 py-2">Stok</th><th class="px-4 py-2 text-right">Modal</th><th class="px-4 py-2 text-right">Jual Saran</th><th class="px-4 py-2 text-right">Margin</th><th class="px-4 py-2">Aksi</th></tr></thead>
<tbody>
@foreach($products as $p)<tr class="border-t border-slate-100">
<td class="px-4 py-2"><div class="flex items-center gap-2">@if($p->foto)<img src="/storage/{{ $p->foto }}" class="w-10 h-10 rounded-xl object-cover" alt="">@endif<b>{{ $p->nama }}</b> <span class="text-[11px] text-slate-400">{{ $p->kategori }} • Mulai 1 pcs</span></div></td>
<td class="px-4 py-2 text-xs">{{ $p->stok }}</td>
<td class="px-4 py-2 text-right font-extrabold text-cyan-800">Rp {{ number_format($p->harga_reseller) }}</td>
<td class="px-4 py-2 text-right text-xs text-slate-400 line-through">Rp {{ number_format($p->harga_enduser) }}</td>
<td class="px-4 py-2 text-right"><span class="text-[10px] font-extrabold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">+Rp {{ number_format($p->margin_rp) }} ({{ $p->margin_pct }}%)</span></td>
<td class="px-4 py-2"><div class="flex gap-1"><a href="/produk/{{ $p->id }}" class="text-white text-xs font-extrabold py-1.5 px-3 rounded-2xl" style="background:linear-gradient(90deg,#0e7490,#22d3ee);text-decoration:none">ORDER MODAL →</a>
@php $share='https://wa.me/?text='.urlencode($p->nama.' cuma Rp '.number_format($p->harga_enduser).' di Bubee! '.url('/produk/'.$p->id)); @endphp
<a href="{{ $share }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-2xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition" style="text-decoration:none">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
    <span>Share</span>
</a></div></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3 grid gap-2 md:grid-cols-2">
@foreach($products as $p)
<div class="bg-white rounded-3xl shadow-card overflow-hidden">
<div class="flex gap-3 p-3">
@if($p->foto)<img src="/storage/{{ $p->foto }}" class="w-16 h-16 rounded-2xl object-cover flex-none" alt=""> @else <div class="w-16 h-16 rounded-2xl flex-none flex items-center justify-center text-3xl" style="background:linear-gradient(180deg,#D8E6FF,#fff)">👕</div>@endif
<div class="flex-1 min-w-0 text-sm">
<div class="font-bold leading-tight">{{ $p->nama }}</div>
<div class="text-[11px] text-slate-500">{{ $p->kategori }} • stok {{ $p->stok }} • Mulai 1 pcs</div>
<div class="mt-1 flex items-center gap-2 flex-wrap">
<span class="font-extrabold text-cyan-800 text-base">Rp {{ number_format($p->harga_reseller) }}</span>
<span class="text-[11px] text-slate-400 line-through">Rp {{ number_format($p->harga_enduser) }}</span>
<span class="text-[10px] font-extrabold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">+Rp {{ number_format($p->margin_rp) }} ({{ $p->margin_pct }}%)</span>
</div>
</div>
</div>
<div class="px-3 pb-3 flex gap-2">
<a href="/produk/{{ $p->id }}" class="flex-1 text-center text-white text-xs font-extrabold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#0e7490,#22d3ee);text-decoration:none">ORDER MODAL →</a>
@php $share='https://wa.me/?text='.urlencode($p->nama.' cuma Rp '.number_format($p->harga_enduser).' di Bubee! '.url('/produk/'.$p->id)); @endphp
<a href="{{ $share }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-2.5 rounded-2xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition" style="text-decoration:none">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
    <span>Share</span>
</a>
</div>
</div>
@endforeach
</div>
@if($products->isEmpty())<div class="bg-white rounded-3xl p-8 text-center text-sm mt-3">Belum ada barang.</div>@endif
@endsection
