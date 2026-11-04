@extends('layouts.app')
@section('title','Riwayat Hemat')
@section('back', '/reseller')
@section('content')
<div class="flex items-center gap-2 mb-2">
    <div class="w-8 h-8 rounded-xl bg-cyan-100 text-cyan-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><path d="M12 6v12M15 9.5a3.5 3.5 0 0 0-5 0c0 2 3 2.5 3 4.5 0 1.2-1 2-2.5 2"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Riwayat Keuntungan</h1>
</div>
<div class="rounded-3xl p-4 mt-2 text-white shadow-card" style="background:linear-gradient(135deg,#083344,#0e7490)">
<div class="text-xs opacity-70">Total hemat dari harga modal</div>
<div class="font-extrabold text-2xl">Rp {{ number_format($totalHemat) }}</div>
</div>
@foreach($orders as $o)
<a href="/pesanan/{{ $o->id }}" class="block glass rounded-3xl shadow-card p-3 mt-2 text-sm">
<div class="flex justify-between"><b>{{ $o->kode }}</b><b class="text-emerald-600">hemat Rp {{ number_format($o->hemat) }}</b></div>
<div class="text-xs text-slate-500 flex items-center gap-1.5 flex-wrap mt-0.5"><span>{{ $o->created_at->format('d M Y') }} • {{ $o->status }}</span>@if($o->voucher)<span class="inline-flex items-center gap-1 text-cyan-700 font-bold"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/></svg>{{ $o->voucher->kode }} (-Rp {{ number_format($o->diskon) }})</span>@endif</div>
</a>
@endforeach
@if($orders->isEmpty())<div class="glass rounded-3xl p-8 text-center text-sm mt-2">Belum ada order. Mulai dari <a href="/reseller/etalase" class="font-bold text-cyan-700">Etalase →</a></div>@endif
@endsection
