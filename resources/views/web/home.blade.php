@extends('layouts.web')
@section('title','Home')
@section('content')
{{-- HERO 2 kolom desktop --}}
<div class="rounded-3xl p-8 text-white shadow-card relative overflow-hidden anim-in" style="--d:0ms;background:linear-gradient(135deg,#050B2E,#0B2A6B 55%,#1D4ED8);border:1px solid rgba(255,255,255,.12)">
<div class="absolute -right-10 -top-10 w-52 h-52 rounded-full bg-cyan-400/25 blur-3xl"></div>
<div class="absolute -left-10 -bottom-14 w-52 h-52 rounded-full bg-indigo-500/25 blur-3xl"></div>
<div class="relative grid gap-6 lg:grid-cols-2 lg:items-center">
<div class="min-w-0">
<div class="text-[11px] tracking-widest text-cyan-300 font-bold">● STOK REAL-TIME</div>
<div class="font-extrabold text-3xl mt-1 flex items-center gap-2">
    <span>Halo {{ auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Kak' }}</span>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-400/20 text-amber-300">
        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M21.5 7.5c-.83 0-1.5.67-1.5 1.5v4.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5v-7c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v7c0 .28-.22.5-.5.5s-.5-.22-.5-.5V3.5c0-.83-.67-1.5-1.5-1.5S12.5 2.67 12.5 3.5v8.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5V5.5c0-.83-.67-1.5-1.5-1.5S8.5 4.67 8.5 5.5v9.18l-1.92-2.02c-.59-.61-1.56-.62-2.16-.03-.61.59-.62 1.56-.03 2.16l4.63 4.86c2.32 2.44 5.54 3.85 8.98 3.85h.5c3.87 0 7-3.13 7-7v-7.5c0-.83-.67-1.5-1.5-1.5z"/></svg>
    </span>
</div>
<div class="text-sm opacity-70 mt-1">Lihat stok, cek harga, pesan sekarang. Semua dalam satu tempat.</div>
<form method="GET" action="/" class="mt-4 flex gap-2 max-w-md">
<input type="hidden" name="filter" value="{{ $filter }}"><input type="hidden" name="kategori" value="{{ $kategori }}"><input type="hidden" name="sort" value="{{ $sort }}">
<div class="relative flex-1">
    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </span>
    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari barang atau tumbler…" class="w-full rounded-2xl pl-10 pr-4 py-2.5 text-sm text-slate-900 outline-none">
</div>
<button class="bg-cyan-300 text-slate-900 font-extrabold px-5 rounded-2xl text-sm">Cari</button>
</form>
</div>
<div class="hidden lg:flex items-center justify-end gap-3 text-center">
<div class="rounded-2xl px-6 py-4" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(6px)"><div class="font-extrabold text-2xl">{{ $products->count() }}</div><div class="text-xs opacity-70">Produk aktif</div></div>
<div class="rounded-2xl px-6 py-4" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);backdrop-filter:blur(6px)"><div class="font-extrabold text-2xl">JNE J&T SPX</div><div class="text-xs opacity-70">Kurir didukung</div></div>
<div class="w-16 h-16 rounded-3xl bg-amber-400 text-slate-900 flex items-center justify-center shadow-lg transform hover:scale-105 transition" title="Bubee">
    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2C8 2 5 5 5 9c0 2.2 1 4.2 2.5 5.5L7 20l5-2 5 2-.5-5.5C18 13.2 19 11.2 19 9c0-4-3-7-7-7z"/>
        <path d="M9 10h6M8 14h8"/>
        <circle cx="10" cy="7" r="1" fill="currentColor"/>
        <circle cx="14" cy="7" r="1" fill="currentColor"/>
    </svg>
</div>
</div>
</div>
</div>

{{-- FILTER --}}
<div class="bg-white border border-slate-100 rounded-3xl shadow-card p-4 mt-4 flex flex-wrap gap-1.5 text-[12px] font-bold items-center anim-in" style="--d:60ms">
@foreach(['semua'=>'Semua','baru'=>'Baru','tersedia'=>'Ready','menipis'=>'Menipis','habis'=>'Habis'] as $k=>$l)
<a href="/?filter={{ $k }}&q={{ $q }}&kategori={{ $kategori }}&sort={{ $sort }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($filter??'semua')===$k?'bg-blue-900 text-white':'bg-slate-100 text-slate-600' }}">{{ $l }}</a>
@endforeach
<span class="mx-1 text-slate-200">|</span>
<a href="/?filter={{ $filter }}&q={{ $q }}&kategori=semua&sort={{ $sort }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($kategori??'semua')==='semua'?'bg-amber-400 text-slate-900':'bg-slate-100 text-slate-600' }}">Semua</a>
@foreach($daftarKategori as $kt)
<a href="/?filter={{ $filter }}&q={{ $q }}&kategori={{ $kt }}&sort={{ $sort }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($kategori??'semua')===$kt?'bg-amber-400 text-slate-900':'bg-slate-100 text-slate-600' }}">{{ $kt }}</a>
@endforeach
<form method="GET" action="/" class="ml-auto">
<input type="hidden" name="filter" value="{{ $filter }}"><input type="hidden" name="q" value="{{ $q }}"><input type="hidden" name="kategori" value="{{ $kategori }}">
<select name="sort" onchange="this.form.submit()" class="rounded-full text-[12px] font-bold px-3 py-1.5 bg-slate-100 text-slate-700 outline-none">
<option value="stok" {{ $sort==='stok'?'selected':'' }}>Stok terbanyak</option>
<option value="murah" {{ $sort==='murah'?'selected':'' }}>Termurah</option>
<option value="mahal" {{ $sort==='mahal'?'selected':'' }}>Termahal</option>
</select>
</form>
</div>

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

{{-- GRID 4-5 kolom desktop --}}
<div class="grid grid-cols-2 gap-3 mt-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
@foreach($products as $p)
@php $st=$p->statusStok(); $harga=$p->hargaUntuk(auth()->user()); $disk=$p->harga_enduser>0?round(($p->harga_enduser-$p->harga_reseller)/$p->harga_enduser*100):0; $isRslr=auth()->check()&&auth()->user()->role==='reseller'&&auth()->user()->status_reseller==='approved'; @endphp
<div class="bg-white border border-slate-100 rounded-3xl shadow-card overflow-hidden flex flex-col h-full anim-in" style="--d:{{ min($loop->index*50,400) }}ms">
<a href="/produk/{{ $p->id }}" class="flex-none flex items-center justify-center text-5xl relative overflow-hidden block" style="background:linear-gradient(180deg,#D8E6FF,#fff);aspect-ratio:4/3">
@if($p->foto)<img src="/storage/{{ $p->foto }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $p->nama }}" loading="lazy">@else<span class="relative">👕</span>@endif
@if($isRslr && $disk>0)<span class="absolute top-2 left-2 text-[10px] font-extrabold bg-pink-500 text-white px-2 py-0.5 rounded-full">-{{ $disk }}% RSLR</span>@endif
@if($st==='habis')<span class="absolute top-2 right-2 text-[10px] font-bold bg-slate-900 text-white px-2 py-0.5 rounded-full">HABIS</span>
@elseif($st==='menipis')<span class="absolute top-2 right-2 text-[10px] font-bold bg-amber-400 px-2 py-0.5 rounded-full chip-live">SISA {{ $p->stok }}</span>
@else<span class="absolute top-2 right-2 text-[10px] font-bold bg-emerald-400 px-2 py-0.5 rounded-full">● {{ $p->stok }}</span>@endif
</a>
<div class="p-3 flex flex-col flex-1">
<a href="/produk/{{ $p->id }}" class="p-title font-bold text-[13px] leading-tight block text-slate-900 hover:text-blue-700" style="text-decoration:none">{{ $p->nama }} @if($p->created_at && $p->created_at->gte(now()->subDays(7)))<span class="text-[9px] font-extrabold bg-cyan-400 text-slate-900 px-1.5 py-0.5 rounded-full align-middle">BARU</span>@endif</a>
<div class="text-[10px] mt-0.5 text-slate-500">@if($p->reviews_count>0)<span class="text-amber-500 font-bold">★ {{ number_format($p->reviews_avg_rating,1) }}</span> • @endif{{ $p->kategori }} • Tanpa min. order</div>
<div style="margin-top:auto">
@if(auth()->check())
<div class="price-slot">
    <div class="font-extrabold text-blue-900">{{ $p->hargaRentang(auth()->user()) }} <span class="text-[10px] font-bold {{ $isRslr?'text-emerald-600':'text-slate-400' }}">{{ $isRslr?'GROSIR':'RETAIL' }}</span></div>
</div>
<a href="/produk/{{ $p->id }}" class="lihat-btn block text-center text-[13px] font-extrabold py-2 rounded-2xl text-white" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8);text-decoration:none">Lihat</a>
@else
<a href="/login" class="price-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Login untuk lihat harga</a>
@endif
</div>
</div>
</div>
@endforeach
</div>
@if($products->isEmpty())<div class="bg-white rounded-3xl p-8 text-center text-sm mt-4">Produk tidak ketemu di sarang…<br><a href="/" class="font-bold text-blue-700">Reset →</a></div>@endif
@endsection
