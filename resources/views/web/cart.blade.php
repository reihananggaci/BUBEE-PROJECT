@extends('layouts.web')
@section('title','Keranjang')
@section('content')
<a href="/" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Lanjut Belanja</a>
<h1 class="font-extrabold text-2xl mt-1">Keranjang <span class="text-sm font-normal text-slate-500">{{ $carts->count() }} item</span></h1>
@if($carts->isEmpty())
<div class="bg-white rounded-3xl p-8 text-center text-sm mt-3 shadow-card"><div class="mt-2 font-bold">Sarang masih kosong.</div>Yuk isi dengan barang favoritmu!<br><a href="/" class="font-bold text-blue-700">Jelajahi katalog →</a></div>
@else
@php $total=0; $berat=0; @endphp
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2 space-y-2">
@foreach($carts as $c)
@php $h=$c->product->hargaUntuk(auth()->user()); $total+=$h*$c->qty; $berat+=$c->product->berat_gram*$c->qty; @endphp
<div class="bg-white rounded-3xl shadow-card p-3 flex gap-3 items-center">
<div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-none overflow-hidden" style="background:linear-gradient(180deg,#D8E6FF,#fff)">@if($c->product->foto)<img src="/storage/{{ $c->product->foto }}" alt="{{ $c->product->nama }}" class="w-full h-full object-cover">@else👕@endif</div>
<div class="flex-1 text-sm min-w-0"><div class="font-bold">{{ $c->product->nama }}</div>
<div class="text-[11px] text-blue-700 font-bold">{{ $c->variant?->label() ?? '' }} • Stok {{ $c->variant->stok ?? $c->product->stok }}</div>
<div class="text-[12px]">Rp {{ number_format($h) }} × {{ $c->qty }} = <b>Rp {{ number_format($h*$c->qty) }}</b></div>
<div class="flex gap-1 mt-1">
<form method="POST" action="/keranjang-item/{{ $c->id }}" class="flex gap-1">@csrf @method('PUT')
<input type="number" name="qty" value="{{ $c->qty }}" min="1" class="w-16 border rounded-xl px-2 py-1 text-sm"><button class="text-xs bg-blue-50 px-2 rounded-xl font-bold">OK</button></form>
<form method="POST" action="/keranjang-item/{{ $c->id }}">@csrf @method('DELETE')<button class="text-xs text-red-500 font-bold">Hapus</button></form>
</div></div>
</div>
@endforeach
</div>
<div class="lg:sticky lg:top-20 self-start bg-white rounded-3xl p-5 text-sm shadow-card">
<b>🧾 Ringkasan</b>
<div class="mt-1">Subtotal: <b>Rp {{ number_format($total) }}</b></div>
<div>Berat: <b>{{ number_format($berat) }} gr</b></div>
<div class="text-[11px] text-slate-500">Ongkir dihitung di checkout.</div>
<a href="/checkout" class="block text-center text-white font-extrabold py-3.5 rounded-3xl mt-3" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4);text-decoration:none">Checkout →</a>
</div>
</div>
@endif
@endsection
