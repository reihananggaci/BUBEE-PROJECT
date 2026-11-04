@extends('layouts.app')
@section('title','Pesanan Saya')
@section('content')
<div class="flex items-center gap-2 mb-2">
    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Pesanan Saya</h1>
</div>
@foreach($orders as $o)
<a href="/pesanan/{{ $o->id }}" class="block glass rounded-3xl shadow-card p-4 mb-2 text-sm card-hover">
<div class="flex justify-between items-center"><b>{{ $o->kode }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $o->status==='selesai'?'bg-emerald-400':($o->status==='batal'?'bg-red-400 text-white':'bg-cyan-300') }}">{{ strtoupper($o->status) }}</span></div>
<div class="text-xs text-slate-500 mt-0.5">{{ $o->created_at->format('d M H:i') }} • {{ $o->kurir }} {{ $o->layanan }} @if($o->resi)• Resi {{ $o->resi }}@endif</div>
@foreach($o->items as $it)<div class="text-[12px] flex items-center gap-1.5">@if($it->product?->foto)<img src="/storage/{{ $it->product->foto }}" alt="{{ $it->product->nama }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="w-5 h-5 rounded bg-blue-50 text-blue-600 flex items-center justify-center shrink-0"><svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></span>@endif<span>{{ $it->product->nama ?? '' }} <b>({{ $it->variant?->label() }})</b> ×{{ $it->qty }} <span class="text-slate-400">({{ $it->tipe_harga }})</span></span></div>@endforeach
<div class="font-extrabold mt-1">Rp {{ number_format($o->total_bayar) }} <span class="font-normal text-blue-700">→ Detail & Tracking</span></div>
</a>
@endforeach
@if($orders->isEmpty())<div class="glass rounded-[2rem] p-8 text-center text-sm shadow-card"><div class="hexbee mx-auto"><svg class="w-7 h-7 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8 2 5 5 5 9c0 2.2 1 4.2 2.5 5.5L7 20l5-2 5 2-.5-5.5C18 13.2 19 11.2 19 9c0-4-3-7-7-7z"/><path d="M9 10h6M8 14h8"/></svg></div><div class="mt-2">Belum ada pesanan.<br><a href="/" class="font-bold text-blue-700">Mulai belanja →</a></div></div>@endif
@endsection
