@extends('layouts.app')
@section('title','Pesanan Saya')
@section('content')
<h1 class="font-extrabold text-lg">Pesanan Saya 📦</h1>
@foreach($orders as $o)
<a href="/pesanan/{{ $o->id }}" class="block glass rounded-3xl shadow-card p-4 mb-2 text-sm card-hover">
<div class="flex justify-between items-center"><b>{{ $o->kode }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $o->status==='selesai'?'bg-emerald-400':($o->status==='batal'?'bg-red-400 text-white':'bg-cyan-300') }}">{{ strtoupper($o->status) }}</span></div>
<div class="text-xs text-slate-500 mt-0.5">{{ $o->created_at->format('d M H:i') }} • {{ $o->kurir }} {{ $o->layanan }} @if($o->resi)• Resi {{ $o->resi }}@endif</div>
@foreach($o->items as $it)<div class="text-[12px] flex items-center gap-1.5">@if($it->product?->foto)<img src="/storage/{{ $it->product->foto }}" alt="{{ $it->product->nama }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif<span>{{ $it->product->nama ?? '' }} <b>({{ $it->variant?->label() }})</b> ×{{ $it->qty }} <span class="text-slate-400">({{ $it->tipe_harga }})</span></span></div>@endforeach
<div class="font-extrabold mt-1">Rp {{ number_format($o->total_bayar) }} <span class="font-normal text-blue-700">→ Detail & Tracking</span></div>
</a>
@endforeach
@if($orders->isEmpty())<div class="glass rounded-[2rem] p-8 text-center text-sm shadow-card"><div class="hexbee mx-auto">🐝</div><div class="mt-2">Belum ada pesanan.<br><a href="/" class="font-bold text-blue-700">Mulai belanja →</a></div></div>@endif
@endsection
