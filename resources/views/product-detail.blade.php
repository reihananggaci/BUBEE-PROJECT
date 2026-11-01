@extends('layouts.app')
@section('title',$product->nama)
@section('back', '/')
@section('content')
<div class="glass rounded-[2rem] shadow-card overflow-hidden">
<div class="h-52 flex items-center justify-center text-8xl relative overflow-hidden" style="background:linear-gradient(180deg,#0B2A6B,#1D4ED8)">
@if($product->foto)<img src="/storage/{{ $product->foto }}" alt="{{ $product->nama }}" class="absolute inset-0 w-full h-full object-cover">@else<span class="floaty relative">👕</span>@endif
<span class="absolute bottom-3 text-[11px] font-bold bg-black/40 text-white px-3 py-1 rounded-full">📦 Total stok: {{ $product->stok }} • {{ $product->berat_gram }} gr</span>
</div>
<div class="p-4">
<h1 class="font-extrabold">{{ $product->nama }}</h1>
<div class="flex items-center gap-2 mt-1 text-sm">
@if($jml>0)<span class="text-amber-500 font-bold">{{ \App\Models\Review::bintang($rata) }}</span><span class="text-xs text-slate-500">{{ $rata }} ({{ $jml }} ulasan)</span>@else<span class="text-xs text-slate-400">Belum ada ulasan</span>@endif
<span class="text-[11px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-bold">{{ $product->kategori }}</span>
</div>
@php
$shareText = auth()->check()
    ? 'Lihat nih ' . $product->nama . ' cuma Rp ' . number_format($product->hargaUntuk(auth()->user())) . ' di Bubee! ' . url('/produk/' . $product->id)
    : 'Lihat nih ' . $product->nama . ' di Bubee! ' . url('/produk/' . $product->id);
$share = 'https://wa.me/?text=' . urlencode($shareText);
@endphp
<a href="{{ $share }}" target="_blank" class="block text-center mt-2 bg-emerald-50 text-emerald-700 font-bold py-2.5 rounded-2xl text-sm">📤 Bagikan ke WA</a>
<p class="text-sm text-slate-500 mt-2">{{ $product->deskripsi }}</p>
@if(auth()->check())
@php
    $isRslrDetail = auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved';
    $hargaDetail = $product->hargaUntuk(auth()->user());
@endphp
<div class="mt-2 rounded-2xl p-3 text-sm bg-blue-50 border border-blue-100">
    <div>Harga: <b class="text-lg text-blue-900">Rp {{ number_format($hargaDetail) }}</b> <span class="text-[11px] font-bold {{ $isRslrDetail ? 'text-emerald-600' : 'text-slate-400' }}">{{ $isRslrDetail ? 'GROSIR' : 'RETAIL' }}</span></div>
    <div class="text-[11px] text-slate-500">Normal Rp {{ number_format($product->harga_enduser) }} • Reseller mulai Rp {{ number_format(\App\Services\PriceService::resellerTermurah($product)) }} (min {{ \App\Services\PriceService::minReseller($product) }} pcs)</div>
</div>
@else
<div class="mt-2 rounded-2xl p-3 text-sm bg-blue-50 border border-blue-100 flex items-center justify-between gap-3">
    <div>
        <div class="font-extrabold text-blue-900 flex items-center gap-1.5"><svg class="w-4 h-4 text-blue-700 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Harga Terkunci 🔒</div>
        <div class="text-[11px] text-slate-500">Login untuk melihat harga retail & grosir</div>
    </div>
    <a href="/login" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold text-white shrink-0" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Login →</a>
</div>
@endif
@if(auth()->check())
@php($varianTerpilih = $product->variants->first(fn($v) => $v->stok > 0)?->id)
<form id="form-keranjang" method="POST" action="/keranjang/{{ $product->id }}" class="mt-3 space-y-2">@csrf
<label class="text-sm font-bold">Pilih varian</label>
<div class="grid grid-cols-2 gap-1.5">
@foreach($product->variants as $v)
<label data-stok="{{ $v->stok }}" data-label="{{ $v->label() }}" class="border-2 rounded-2xl px-3 py-2 text-center text-sm cursor-pointer transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 {{ $v->stok<=0?'opacity-40':'' }}">
<input type="radio" name="variant_id" value="{{ $v->id }}" class="sr-only" {{ $varianTerpilih === $v->id ? 'checked' : '' }} {{ $v->stok<=0?'disabled':'' }}>
<div class="font-bold">{{ $v->label() }}</div>
<div class="text-[11px] {{ $v->stok<=0?'text-red-500':'text-slate-500' }}">{{ $v->stok<=0?'Habis':'Sisa '.$v->stok }}</div>
</label>
@endforeach
</div>
<div id="varian-info" class="text-[11px] text-slate-500"></div>
<div class="flex gap-2">
<input type="number" name="qty" value="1" min="1" max="{{ max(1,$product->stok) }}" class="w-20 border rounded-2xl px-3 py-2.5 text-center font-bold">
<button id="tombol-keranjang" class="flex-1 text-white font-extrabold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)" {{ $product->stok<=0?'disabled':'' }}>{{ $product->stok<=0?'STOK HABIS':'+ KERANJANG' }}</button>
</div>
</form>
<script>
(function(){
    var form = document.getElementById('form-keranjang');
    if (!form) return;
    var info = document.getElementById('varian-info');
    var qty = form.querySelector('input[name="qty"]');
    var tombol = document.getElementById('tombol-keranjang');
    function sync(){
        var pilih = form.querySelector('input[name="variant_id"]:checked');
        if (!pilih) {
            tombol.disabled = true;
            tombol.textContent = 'PILIH VARIAN';
            info.textContent = 'Silakan pilih varian yang tersedia.';
            return;
        }
        var kartu = pilih.closest('label');
        var stok = parseInt(kartu.dataset.stok, 10) || 0;
        qty.max = Math.max(1, stok);
        if (parseInt(qty.value, 10) > stok) qty.value = Math.max(1, stok);
        info.textContent = 'Dipilih: ' + kartu.dataset.label + ' • sisa ' + stok;
        tombol.disabled = stok <= 0;
        tombol.textContent = stok <= 0 ? 'STOK HABIS' : '+ KERANJANG';
    }
    form.querySelectorAll('input[name="variant_id"]').forEach(function(r){ r.addEventListener('change', sync); });
    qty.addEventListener('change', sync);
    form.addEventListener('submit', function(e){
        var pilih = form.querySelector('input[name="variant_id"]:checked');
        if (!pilih || (parseInt(pilih.closest('label')?.dataset?.stok, 10) || 0) <= 0) {
            e.preventDefault();
            alert('Pilih varian yang masih tersedia.');
        }
    });
    sync();
})();
</script>
@else<a href="/login" class="block text-center mt-3 text-white font-bold py-3 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Login untuk Lihat Harga & Order →</a>@endif
</div>
</div>
{{-- ULASAN --}}
<div id="ulasan" class="glass rounded-[2rem] shadow-card p-4 mt-3">
<div class="font-extrabold text-sm">⭐ Ulasan Pembeli ({{ $jml }})</div>
@if($bolehUlas)
<details class="mt-2 text-sm bg-amber-50 border border-amber-100 rounded-2xl p-3">
<summary class="font-bold cursor-pointer">✍️ Tulis ulasanmu</summary>
<form method="POST" action="/produk/{{ $product->id }}/ulasan" enctype="multipart/form-data" class="mt-2 space-y-1.5">@csrf
<select name="rating" class="w-full border rounded-2xl px-3 py-2"><option value="5">⭐⭐⭐⭐⭐ (5)</option><option value="4">⭐⭐⭐⭐ (4)</option><option value="3">⭐⭐⭐ (3)</option><option value="2">⭐⭐ (2)</option><option value="1">⭐ (1)</option></select>
<textarea name="ulasan" placeholder="Ceritakan pengalamanmu…" class="w-full border rounded-2xl px-3 py-2"></textarea>
<input type="file" name="foto" accept="image/*" class="w-full text-xs">
<button class="w-full bg-amber-400 font-extrabold py-2.5 rounded-2xl text-sm">KIRIM</button>
</form>
</details>
@endif
@foreach($ulasan as $u)
<div class="py-2 border-b border-blue-50 last:border-0 text-sm">
<div class="flex justify-between"><b>{{ $u->user->name ?? 'Anonim' }}</b><span class="text-amber-500 text-xs">{{ str_repeat('★',$u->rating) }}{{ str_repeat('☆',5-$u->rating) }}</span></div>
@if($u->ulasan)<div class="text-xs text-slate-600">{{ $u->ulasan }}</div>@endif
@if($u->foto)<a href="/storage/{{ $u->foto }}" target="_blank"><img src="/storage/{{ $u->foto }}" class="w-20 h-20 rounded-xl object-cover mt-1"></a>@endif
<div class="text-[10px] text-slate-400">{{ $u->created_at->format('d M Y') }}</div>
</div>
@endforeach
@if($jml===0)<div class="text-xs text-slate-400 mt-1">Jadilah yang pertama mengulas setelah order selesai! 🐝</div>@endif
</div>
<div>
<div class="font-extrabold text-sm mt-4 mb-2">✨ Mungkin kamu suka</div>
<div class="grid grid-cols-2 gap-2">
@foreach($lainnya as $l)<a href="/produk/{{ $l->id }}" class="glass rounded-3xl p-3 shadow-card card-hover flex flex-col h-full"><div class="text-3xl text-center">@if($l->foto)<img src="/storage/{{ $l->foto }}" alt="{{ $l->nama }}" class="w-full h-20 rounded-2xl object-cover">@else👕@endif</div><div class="text-[12px] font-bold mt-1">{{ $l->nama }}</div>@if(auth()->check())<div class="text-[12px] text-blue-800 font-extrabold" style="margin-top:auto">Rp {{ number_format($l->hargaUntuk(auth()->user())) }}</div>@else<div style="margin-top:auto"><span class="price-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4.5" y="10.5" width="15" height="9.5" rx="2.5"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/></svg>Login untuk lihat harga</span></div>@endif</a>@endforeach
</div>
</div>
@endsection
