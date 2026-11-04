@extends('layouts.app')
@section('title','Retur #'.$retur->id)
@section('back', auth()->check() && auth()->user()->role==='admin' ? '/admin/retur' : '/retur')
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Retur #{{ $retur->id }}</h1>
</div>
<div class="text-xs text-slate-500">Order {{ $retur->order->kode ?? '' }} • Status <b>{{ strtoupper($retur->status) }}</b></div>
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm space-y-1">
@foreach($retur->items as $ri)<div>- {{ $ri->orderItem->product->nama ?? '' }} ({{ $ri->orderItem->variant?->label() }}) ×{{ $ri->qty }}</div>@endforeach
<div><b>Alasan:</b> {{ $retur->alasan }}</div>
@if($retur->foto)<div class="inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg><a href="/storage/{{ $retur->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto bukti</a></div>@endif
@if($retur->catatan_admin)<div class="bg-purple-50 rounded-2xl p-2.5 text-xs"><b>Catatan admin:</b> {{ $retur->catatan_admin }}</div>@endif
</div>
<a href="/retur" class="block text-center mt-2 text-sm font-bold text-blue-700">← Semua retur saya</a>
@endsection
