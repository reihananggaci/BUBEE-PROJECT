@extends('layouts.app')
@section('title','Reseller Center')
@section('content')
<div class="rounded-[2rem] p-5 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(135deg,#083344,#0e7490 60%,#22d3ee)">
<div class="flex items-center gap-3 relative">
<div class="w-12 h-12 rounded-2xl bg-cyan-400/20 border border-cyan-300/30 text-cyan-200 flex items-center justify-center">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
</div>
<div class="flex-1 min-w-0">
<div class="text-[11px] tracking-widest text-cyan-200 font-bold flex items-center gap-1.5">
    <svg class="w-3.5 h-3.5 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    <span>RESELLER CENTER</span>
</div>
<div class="font-extrabold text-xl">Halo {{ explode(' ',$user->name)[0] }}!</div>
<div class="text-xs opacity-80">Tier <b>{{ $tier }}</b> • join {{ $user->created_at->format('M Y') }}</div>
</div>
</div>
<div class="grid grid-cols-3 gap-2 mt-3 text-center relative">
<div class="rounded-2xl p-2.5 bg-black/25"><div class="text-[10px] opacity-70">TOTAL HEMAT</div><div class="font-extrabold">Rp {{ number_format($totalHemat) }}</div></div>
<div class="rounded-2xl p-2.5 bg-black/25"><div class="text-[10px] opacity-70">PCS</div><div class="font-extrabold text-lg">{{ $totalPcs }}</div></div>
<div class="rounded-2xl p-2.5 bg-black/25"><div class="text-[10px] opacity-70">ORDER</div><div class="font-extrabold text-lg">{{ $totalOrder }}</div></div>
</div>
<div class="grid grid-cols-3 gap-2 mt-2 text-center text-[11px] font-bold relative">
<a href="/reseller/etalase" class="bg-white text-cyan-900 rounded-2xl py-2.5 flex items-center justify-center gap-1.5 shadow-sm">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
    <span>Etalase Modal</span>
</a>
<a href="/reseller/hemat" class="bg-white/20 hover:bg-white/30 text-white rounded-2xl py-2.5 flex items-center justify-center gap-1.5">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><path d="M12 6v12M15 9.5a3.5 3.5 0 0 0-5 0c0 2 3 2.5 3 4.5 0 1.2-1 2-2.5 2"/></svg>
    <span>Riwayat Hemat</span>
</a>
<a href="/pesanan" class="bg-white/20 hover:bg-white/30 text-white rounded-2xl py-2.5 flex items-center justify-center gap-1.5">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
    <span>Pesanan @if($menunggu>0)({{ $menunggu }})@endif</span>
</a>
</div>
</div>

@if($menunggu>0)
<a href="/pesanan" class="flex items-center gap-2 glass rounded-3xl p-3 mt-2 text-sm shadow-card">
    <svg class="w-4 h-4 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <span><b>{{ $menunggu }} order</b> menunggu bayar. <span class="text-blue-700 font-bold">Bayar →</span></span>
</a>
@endif

@if($vouchers->count())
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm">
<div class="flex items-center gap-1.5 font-bold mb-2 text-slate-800">
    <svg class="w-4 h-4 text-cyan-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
    <span>Voucher khusus kamu</span>
</div>
@foreach($vouchers as $v)<div class="flex justify-between text-xs py-1 border-b border-blue-50 last:border-0"><span><b>{{ $v->kode }}</b> {{ $v->label() }}</span><span class="text-slate-400">{{ $v->terpakai }}/{{ $v->kuota }}</span></div>@endforeach
</div>
@endif

@if($menipis->count())
<div class="rounded-3xl p-4 mt-2 text-sm text-white shadow-card" style="background:linear-gradient(90deg,#b45309,#ef4444)">
<div class="flex items-center gap-1.5 font-bold mb-1">
    <svg class="w-4 h-4 text-amber-200 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <span>Stok menipis, amankan sebelum kehabisan:</span>
</div>
@foreach($menipis as $p)<div>• {{ $p->nama }} — sisa {{ $p->stok }}</div>@endforeach
</div>
@endif

<div class="glass rounded-3xl p-4 mt-2 text-[12px] shadow-card flex items-start gap-2">
    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
    <div><b>Cara cuan maksimal:</b> cek Etalase → pilih margin terbesar → share ke WA → order di atas min. pcs biar harga modal aktif + tumpuk voucher reseller.</div>
</div>
@endsection
