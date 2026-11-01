@extends('layouts.app')
@section('title','Riwayat Hemat')
@section('back', '/reseller')
@section('content')
<h1 class="font-extrabold text-lg">💰 Riwayat Keuntungan</h1>
<div class="rounded-3xl p-4 mt-2 text-white shadow-card" style="background:linear-gradient(135deg,#083344,#0e7490)">
<div class="text-xs opacity-70">Total hemat dari harga modal</div>
<div class="font-extrabold text-2xl">Rp {{ number_format($totalHemat) }}</div>
</div>
@foreach($orders as $o)
<a href="/pesanan/{{ $o->id }}" class="block glass rounded-3xl shadow-card p-3 mt-2 text-sm">
<div class="flex justify-between"><b>{{ $o->kode }}</b><b class="text-emerald-600">hemat Rp {{ number_format($o->hemat) }}</b></div>
<div class="text-xs text-slate-500">{{ $o->created_at->format('d M Y') }} • {{ $o->status }}@if($o->voucher) • 🎟️ {{ $o->voucher->kode }} (-Rp {{ number_format($o->diskon) }})@endif</div>
</a>
@endforeach
@if($orders->isEmpty())<div class="glass rounded-3xl p-8 text-center text-sm mt-2">Belum ada order. Mulai dari <a href="/reseller/etalase" class="font-bold text-cyan-700">Etalase →</a></div>@endif
@endsection
