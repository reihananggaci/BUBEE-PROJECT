@extends('layouts.app')
@section('title','Etalase Modal')
@section('back', '/reseller')
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-cyan-100 text-cyan-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Etalase Harga Modal</h1>
</div>
<div class="text-xs text-slate-500 mb-2">Harga yang kamu bayar vs harga jual saran. Margin = cuanmu.</div>
<form method="GET" action="/reseller/etalase" class="flex gap-2 mb-2">
<select name="sort" onchange="this.form.submit()" class="flex-1 border rounded-2xl px-3 py-2 text-sm font-bold">
<option value="margin" {{ $sort==='margin'?'selected':'' }}>Urutkan: Margin Terbesar</option>
<option value="stok" {{ $sort==='stok'?'selected':'' }}>Urutkan: Stok Terbanyak</option>
</select>
</form>
@foreach($products as $p)
<div class="glass rounded-[1.5rem] shadow-card overflow-hidden mb-2">
<div class="flex gap-3 p-3">
@if($p->foto)<img src="/storage/{{ $p->foto }}" class="w-16 h-16 rounded-2xl object-cover flex-none"> @else <div class="w-16 h-16 rounded-2xl flex-none flex items-center justify-center text-3xl" style="background:linear-gradient(180deg,#D8E6FF,#fff)">👕</div>@endif
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
<a href="/produk/{{ $p->id }}" class="flex-1 text-center text-white text-xs font-extrabold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#0e7490,#22d3ee)">ORDER MODAL →</a>
@php $share='https://wa.me/?text='.urlencode($p->nama.' cuma Rp '.number_format($p->harga_enduser).' di Bubee! '.url('/produk/'.$p->id)); @endphp
<a href="{{ $share }}" target="_blank" class="text-xs font-bold px-3 py-2.5 rounded-2xl bg-emerald-50 text-emerald-700 inline-flex items-center gap-1.5 hover:bg-emerald-100 transition">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
    <span>Share</span>
</a>
</div>
</div>
@endforeach
@if($products->isEmpty())<div class="glass rounded-3xl p-8 text-center text-sm">Belum ada barang.</div>@endif
@endsection
