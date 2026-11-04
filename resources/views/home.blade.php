@extends('layouts.app')
@section('title','Home')
@section('content')
{{-- HERO --}}
<div class="rounded-[1.75rem] p-5 text-white shadow-card relative overflow-hidden anim-in" style="--d:0ms;background:var(--hex),linear-gradient(135deg,#050B2E,#0B2A6B 55%,#1D4ED8)">
<div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-cyan-400/30 blur-2xl"></div>
<div class="relative">
<div class="text-[11px] tracking-widest text-cyan-300 font-bold">● STOK REAL-TIME</div>
<div class="font-extrabold text-xl mt-1">Halo {{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Kak' }}</div>
<div class="text-xs opacity-70 mt-1">Lihat stok, cek harga, pesan sekarang. Semua dalam satu tempat.</div>
</div>
<div class="relative">
<form method="GET" action="/" class="mt-3 flex gap-2">
<input type="hidden" name="filter" value="{{ $filter }}"><input type="hidden" name="kategori" value="{{ $kategori }}"><input type="hidden" name="sort" value="{{ $sort }}">
<div class="relative flex-1">
    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </span>
    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari barang…" class="w-full rounded-2xl pl-9 pr-3 py-2 text-sm text-slate-900 outline-none">
</div>
<button class="bg-cyan-300 text-slate-900 font-extrabold px-4 rounded-2xl text-sm">Cari</button>
</form>
</div>
</div>

{{-- FILTER RAPI --}}
<div class="glass rounded-3xl shadow-card p-3 mt-3 anim-in" style="--d:60ms">
<div class="flex gap-1.5 text-[11px] font-bold overflow-x-auto pb-1">
@foreach(['semua'=>'Semua','baru'=>'Baru','tersedia'=>'Ready','menipis'=>'Menipis','habis'=>'Habis'] as $k=>$l)
<a href="/?filter={{ $k }}&q={{ $q }}&kategori={{ $kategori }}&sort={{ $sort }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($filter??'semua')===$k?'bg-blue-900 text-white':'bg-slate-100 text-slate-600' }}">{{ $l }}</a>
@endforeach
</div>
<div class="flex gap-1.5 text-[11px] font-bold overflow-x-auto items-center">
<a href="/?filter={{ $filter }}&q={{ $q }}&kategori=semua&sort={{ $sort }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($kategori??'semua')==='semua'?'bg-amber-400 text-slate-900':'bg-slate-100 text-slate-600' }}">Semua</a>
@foreach($daftarKategori as $kt)
<a href="/?filter={{ $filter }}&q={{ $q }}&kategori={{ $kt }}&sort={{ $sort }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($kategori??'semua')===$kt?'bg-amber-400 text-slate-900':'bg-slate-100 text-slate-600' }}">{{ $kt }}</a>
@endforeach
<form method="GET" action="/" class="ml-auto flex-none">
<input type="hidden" name="filter" value="{{ $filter }}"><input type="hidden" name="q" value="{{ $q }}"><input type="hidden" name="kategori" value="{{ $kategori }}">
<select name="sort" onchange="this.form.submit()" class="rounded-full text-[11px] font-bold px-2 py-1.5 bg-slate-100 text-slate-700 outline-none">
<option value="stok" {{ $sort==='stok'?'selected':'' }}>Stok terbanyak</option>
<option value="murah" {{ $sort==='murah'?'selected':'' }}>Termurah</option>
<option value="mahal" {{ $sort==='mahal'?'selected':'' }}>Termahal</option>
</select>
</form>
</div>
</div>

{{-- ROLE BANNER --}}
@if(auth()->check() && auth()->user()->role==='reseller' && auth()->user()->status_reseller!=='approved')
<div class="rounded-2xl p-3 mt-3 text-xs bg-amber-50 border border-amber-200 text-amber-800 flex items-center gap-2">
    <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <span>Menunggu approval admin. Cek status di <a href="/akun" class="font-bold underline">Akun</a>.</span>
</div>
@endif
@if(auth()->check() && auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved')
<div class="rounded-2xl p-3 mt-3 text-xs font-bold text-white flex items-center gap-2" style="background:linear-gradient(90deg,#059669,#00E5FF)">
    <svg class="w-4 h-4 text-emerald-100 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
    <span>Harga reseller aktif! Nikmati harga modal khusus mitra mulai 1 pcs (tanpa minimal belanja).</span>
</div>
@endif

{{-- GRID --}}
@php
    $isRslrUser = auth()->check() && auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved';
@endphp
<div class="grid grid-cols-2 gap-3 mt-3">
@foreach($products as $p)
@php $st=$p->statusStok(); $harga=$p->hargaUntuk(auth()->user()); $disk=$p->harga_enduser>0?round(($p->harga_enduser-$p->harga_reseller)/$p->harga_enduser*100):0; @endphp
<div class="glass rounded-[1.5rem] shadow-card overflow-hidden card-hover flex flex-col h-full anim-in" style="--d:{{ min($loop->index*50,400) }}ms">
<a href="/produk/{{ $p->id }}" class="flex-none flex items-center justify-center text-5xl relative overflow-hidden block" style="background:linear-gradient(180deg,#D8E6FF,#fff);aspect-ratio:4/3">
@if($p->foto)<img src="/storage/{{ $p->foto }}" alt="{{ $p->nama }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">@else<span class="floaty relative">👕</span>@endif
@if($isRslrUser && $disk>0)<span class="absolute top-2 left-2 text-[10px] font-extrabold bg-pink-500 text-white px-2 py-0.5 rounded-full">-{{ $disk }}% RSLR</span>@endif
@if($st==='habis')<span class="absolute top-2 right-2 text-[10px] font-bold bg-slate-900 text-white px-2 py-0.5 rounded-full">HABIS</span>
@elseif($st==='menipis')<span class="absolute top-2 right-2 text-[10px] font-bold bg-amber-400 px-2 py-0.5 rounded-full chip-live">SISA {{ $p->stok }}</span>
@else<span class="absolute top-2 right-2 text-[10px] font-bold bg-emerald-400 px-2 py-0.5 rounded-full">● {{ $p->stok }}</span>@endif
</a>
<div class="p-3 flex flex-col flex-1">
<a href="/produk/{{ $p->id }}" class="p-title font-bold text-[13px] leading-tight block text-slate-900 hover:text-blue-700">{{ $p->nama }} @if($p->created_at && $p->created_at->gte(now()->subDays(7)))<span class="text-[9px] font-extrabold bg-cyan-400 text-slate-900 px-1.5 py-0.5 rounded-full align-middle">BARU</span>@endif</a>
<div class="text-[10px] mt-0.5 text-slate-500">@if($p->reviews_count>0)<span class="text-amber-500 font-bold">★ {{ number_format($p->reviews_avg_rating,1) }}</span> • @endif{{ $p->kategori }} • Tanpa min. order</div>
<div style="margin-top:auto">
@if(auth()->check())
<div class="price-slot">
    <div class="font-extrabold text-blue-900">{{ $p->hargaRentang(auth()->user()) }} <span class="text-[10px] font-bold {{ $isRslrUser?'text-emerald-600':'text-slate-400' }}">{{ $isRslrUser?'GROSIR':'RETAIL' }}</span></div>
</div>
<a href="/produk/{{ $p->id }}" class="lihat-btn block text-center text-[13px] font-extrabold py-2 rounded-2xl text-white" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Lihat</a>
@else
<a href="/login" class="price-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Login untuk lihat harga</a>
@endif
</div>
</div>
</div>
@endforeach
</div>
@if($products->isEmpty())<div class="glass rounded-3xl p-8 text-center text-sm mt-3"><div class="hexbee mx-auto"><svg class="w-7 h-7 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8 2 5 5 5 9c0 2.2 1 4.2 2.5 5.5L7 20l5-2 5 2-.5-5.5C18 13.2 19 11.2 19 9c0-4-3-7-7-7z"/><path d="M9 10h6M8 14h8"/></svg></div><div class="mt-2">Produk tidak ketemu di sarang…<br><a href="/" class="font-bold text-blue-700">Reset →</a></div></div>@endif
@endsection
