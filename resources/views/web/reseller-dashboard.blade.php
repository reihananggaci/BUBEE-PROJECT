@extends('layouts.web')
@section('title','Reseller Center')
@section('content')
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2 rounded-3xl p-6 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(135deg,#083344,#0e7490 60%,#22d3ee)">
<div class="flex items-center gap-3 relative">
<div class="w-12 h-12 rounded-2xl bg-cyan-400/20 border border-cyan-300/30 flex items-center justify-center text-cyan-200 shrink-0">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
</div>
<div class="flex-1 min-w-0">
<div class="flex items-center gap-1.5 text-[11px] tracking-widest text-cyan-200 font-extrabold uppercase">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/></svg>
    <span>RESELLER CENTER</span>
</div>
<div class="font-extrabold text-2xl">Halo {{ explode(' ',$user->name)[0] }}!</div>
<div class="text-xs opacity-80">Tier <b>{{ $tier }}</b> • join {{ $user->created_at->format('M Y') }}</div>
</div>
</div>
<div class="grid grid-cols-3 gap-2 mt-4 text-center relative">
<div class="rounded-2xl p-3 bg-black/25"><div class="text-[10px] opacity-70">TOTAL HEMAT</div><div class="font-extrabold">Rp {{ number_format($totalHemat) }}</div></div>
<div class="rounded-2xl p-3 bg-black/25"><div class="text-[10px] opacity-70">PCS</div><div class="font-extrabold text-lg">{{ $totalPcs }}</div></div>
<div class="rounded-2xl p-3 bg-black/25"><div class="text-[10px] opacity-70">ORDER</div><div class="font-extrabold text-lg">{{ $totalOrder }}</div></div>
</div>
<div class="grid grid-cols-3 gap-2 mt-3 text-center text-xs font-bold relative">
<a href="/reseller/etalase" class="flex items-center justify-center gap-1.5 bg-white text-cyan-900 rounded-2xl py-2.5 shadow-sm transition hover:opacity-95" style="text-decoration:none">
    <svg class="w-4 h-4 text-cyan-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
    <span>Etalase Modal</span>
</a>
<a href="/reseller/hemat" class="flex items-center justify-center gap-1.5 bg-white/20 hover:bg-white/25 rounded-2xl py-2.5 text-white transition" style="text-decoration:none">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="8" r="6"/><path d="M18.09 10.37A6 6 0 1 1 10.34 18"/><path d="m7 6 4 4"/></svg>
    <span>Riwayat Hemat</span>
</a>
<a href="/pesanan" class="flex items-center justify-center gap-1.5 bg-white/20 hover:bg-white/25 rounded-2xl py-2.5 text-white transition" style="text-decoration:none">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg>
    <span>Pesanan @if($menunggu>0)({{ $menunggu }})@endif</span>
</a>
</div>
</div>
<div class="space-y-2 self-start lg:sticky lg:top-20">
@if($menunggu>0)
<a href="/pesanan" class="flex items-center gap-2 bg-white rounded-3xl p-4 text-sm shadow-card text-slate-800" style="text-decoration:none">
    <svg class="w-5 h-5 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <span><b>{{ $menunggu }} order</b> menunggu bayar. <span class="text-blue-700 font-bold">Bayar →</span></span>
</a>
@endif
@if($vouchers->count())
<div class="bg-white rounded-3xl shadow-card p-4 text-sm">
<b class="flex items-center gap-1.5 text-slate-900 mb-2">
    <svg class="w-4 h-4 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/></svg>
    <span>Voucher khusus kamu</span>
</b>
@foreach($vouchers as $v)<div class="flex justify-between text-xs py-1 border-b border-blue-50 last:border-0"><span><b>{{ $v->kode }}</b> {{ $v->label() }}</span><span class="text-slate-400">{{ $v->terpakai }}/{{ $v->kuota }}</span></div>@endforeach
</div>
@endif
@if($menipis->count())
<div class="rounded-3xl p-4 text-sm text-white shadow-card" style="background:linear-gradient(90deg,#b45309,#ef4444)">
<div class="flex items-center gap-1.5 font-bold mb-1">
    <svg class="w-4 h-4 text-amber-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <span>Stok menipis, amankan sebelum kehabisan:</span>
</div>
@foreach($menipis as $p)<div>• {{ $p->nama }} — sisa {{ $p->stok }}</div>@endforeach
</div>
@endif
<div class="bg-white rounded-3xl p-4 text-[12px] shadow-card text-slate-700">
<div class="flex items-center gap-1.5 font-bold text-slate-900 mb-1">
    <svg class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/></svg>
    <span>Cara cuan maksimal:</span>
</div>
cek Etalase → pilih margin terbesar → share ke WA → order di atas min. pcs biar harga modal aktif + tumpuk voucher reseller.
</div>
</div>
</div>
@endsection
