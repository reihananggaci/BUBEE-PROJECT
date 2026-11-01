@extends('layouts.app')
@section('title','Retur #'.$retur->id)
@section('back', auth()->check() && auth()->user()->role==='admin' ? '/admin/retur' : '/retur')
@section('content')
<h1 class="font-extrabold">🔄 Retur #{{ $retur->id }}</h1>
<div class="text-xs text-slate-500">Order {{ $retur->order->kode ?? '' }} • Status <b>{{ strtoupper($retur->status) }}</b></div>
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm space-y-1">
@foreach($retur->items as $ri)<div>- {{ $ri->orderItem->product->nama ?? '' }} ({{ $ri->orderItem->variant?->label() }}) ×{{ $ri->qty }}</div>@endforeach
<div><b>Alasan:</b> {{ $retur->alasan }}</div>
@if($retur->foto)<div>📎 <a href="/storage/{{ $retur->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto</a></div>@endif
@if($retur->catatan_admin)<div class="bg-purple-50 rounded-2xl p-2.5 text-xs"><b>Catatan admin:</b> {{ $retur->catatan_admin }}</div>@endif
</div>
<a href="/retur" class="block text-center mt-2 text-sm font-bold text-blue-700">← Semua retur saya</a>
@endsection
