@extends('layouts.app')
@section('title','Keranjang')
@section('back', '/')
@section('content')
<h1 class="font-extrabold text-lg">Keranjang <span class="text-xs font-normal text-slate-500">{{ $carts->count() }} item</span></h1>
@if($carts->isEmpty())<div class="glass rounded-[2rem] p-8 text-center text-sm mt-2 shadow-card"><div class="hexbee mx-auto">🐝</div><div class="mt-2 font-bold">Sarang masih kosong.</div>Yuk isi dengan barang favoritmu!<br><a href="/" class="font-bold text-blue-700">Jelajahi katalog →</a></div>@else
@php $total=0; $berat=0; @endphp
@foreach($carts as $c)
@php $h=$c->variant ? $c->variant->hargaUntuk(auth()->user()) : $c->product->hargaUntuk(auth()->user()); $total+=$h*$c->qty; $berat+=($c->variant ? $c->variant->beratGram() : $c->product->berat_gram)*$c->qty; @endphp
<div class="glass rounded-3xl shadow-card p-3 mb-2 flex gap-3 items-center">
<div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl overflow-hidden" style="background:linear-gradient(180deg,#D8E6FF,#fff)">@if($c->product->foto)<img src="/storage/{{ $c->product->foto }}" alt="{{ $c->product->nama }}" class="w-full h-full object-cover">@else👕@endif</div>
<div class="flex-1 text-sm"><div class="font-bold">{{ $c->product->nama }}</div>
<div class="text-[11px] text-blue-700 font-bold">{{ $c->variant?->label() ?? '' }} • Stok {{ $c->variant->stok ?? $c->product->stok }}</div>
<div class="text-[12px]">Rp {{ number_format($h) }} × {{ $c->qty }} = <b>Rp {{ number_format($h*$c->qty) }}</b></div>
<div class="flex gap-1 mt-1">
<form method="POST" action="/keranjang-item/{{ $c->id }}" class="flex gap-1">@csrf @method('PUT')
<input type="number" name="qty" value="{{ $c->qty }}" min="1" class="w-16 border rounded-xl px-2 py-1 text-sm"><button class="text-xs bg-blue-50 px-2 rounded-xl font-bold">OK</button></form>
<form method="POST" action="/keranjang-item/{{ $c->id }}">@csrf @method('DELETE')<button class="text-xs text-red-500 font-bold">Hapus</button></form>
</div></div>
</div>
@endforeach
<div class="glass rounded-3xl p-4 text-sm shadow-card">Subtotal: <b>Rp {{ number_format($total) }}</b> • Berat: <b>{{ number_format($berat) }} gr</b><div class="text-[11px] text-slate-500">Ongkir dihitung di checkout.</div></div>
<a href="/checkout" class="block text-center text-white font-extrabold py-3.5 rounded-[1.5rem] mt-2 shadow-card" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">Checkout →</a>
@endif
@endsection
