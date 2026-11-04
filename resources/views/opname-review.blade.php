@extends('layouts.admin')
@section('title','Review Sesi #'.$sesi->id)
@section('back', '/admin/opname/'.$sesi->id)
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Review Sesi #{{ $sesi->id }}</h1>
</div>
<div class="text-xs text-slate-500">{{ $beda->count() }} selisih • {{ $kosong }} belum dihitung</div>
@if($kosong>0)
<div class="rounded-2xl p-3 mt-2 text-xs font-bold bg-amber-50 border border-amber-200 text-amber-800 flex items-center gap-2">
    <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <span>Masih ada {{ $kosong }} varian belum dihitung. <a href="/admin/opname/{{ $sesi->id }}?belum=1" class="underline">Hitung dulu →</a></span>
</div>
@endif
@if($beda->isEmpty())
<div class="glass rounded-3xl p-6 text-center text-sm mt-2 flex flex-col items-center justify-center gap-2 text-emerald-800">
    <svg class="w-8 h-8 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <b>Semua data fisik cocok dengan sistem!</b>
</div>
@endif
<form method="POST" action="/admin/opname/{{ $sesi->id }}/sahkan" enctype="multipart/form-data" class="mt-2 space-y-2" onsubmit="return confirm('Sahkan opname? Stok akan diubah. Berkas tidak bisa dibatalkan.')">@csrf
@foreach($beda as $it)
@php $d = $it->selisih(); @endphp
<div class="glass rounded-3xl shadow-card p-3 text-sm">
<div class="flex justify-between"><b>{{ $it->variant->product->nama ?? '' }} ({{ $it->variant->label() }})</b><b class="{{ $d>0?'text-blue-700':'text-red-600' }}">{{ $d>0?'+'.$d:$d }}</b></div>
<div class="text-xs text-slate-500">sistem {{ $it->snapshot }} → fisik {{ $it->fisik }}</div>
@if($it->besar())
<div class="mt-1.5 rounded-2xl bg-amber-50 border border-amber-200 p-2 text-xs font-bold text-amber-800">
<div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Selisih besar — wajib foto bukti</div>
@if($it->foto)<div class="mt-1 flex items-center gap-1 text-emerald-700"><svg class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Foto sudah ada (<a href="/storage/{{ $it->foto }}" target="_blank" class="underline">lihat</a>), boleh ganti:</div>@endif
<input type="file" name="foto[{{ $it->variant_id }}]" accept="image/*" class="w-full text-xs mt-1 font-normal">
</div>
@elseif($it->foto)<div class="text-xs mt-1 inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg><a href="/storage/{{ $it->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto bukti</a></div>@endif
</div>
@endforeach
@if($kosong===0)<button class="w-full text-white font-extrabold py-3.5 rounded-3xl text-sm flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#059669,#00E5FF)"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span>SAHKAN OPNAME</span></button>@endif
</form>
<form method="POST" action="/admin/opname/{{ $sesi->id }}/batal" class="mt-2" onsubmit="return confirm('Batalkan sesi? Tidak ada stok berubah.')">@csrf<button class="w-full bg-red-50 text-red-600 font-bold py-2.5 rounded-2xl text-xs">Batalkan sesi</button></form>
@endsection
