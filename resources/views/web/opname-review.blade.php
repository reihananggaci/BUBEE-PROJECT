@extends('layouts.web-admin')
@section('title','Review Sesi #'.$sesi->id)
@section('content')
<a href="/admin/opname/{{ $sesi->id }}" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali hitung</a>
<h1 class="font-extrabold text-2xl text-slate-900 mt-2 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
    </span>
    <span>Review Sesi #{{ $sesi->id }}</span>
</h1>
<div class="text-xs text-slate-500">{{ $beda->count() }} selisih • {{ $kosong }} belum dihitung</div>
@if($kosong>0)
<div class="rounded-2xl p-3 mt-2 text-xs font-bold bg-amber-50 border border-amber-200 text-amber-800 flex items-center gap-2">
    <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <span>Masih ada {{ $kosong }} varian belum dihitung. <a href="/admin/opname/{{ $sesi->id }}?belum=1" class="underline">Hitung dulu →</a></span>
</div>
@endif
<form method="POST" action="/admin/opname/{{ $sesi->id }}/sahkan" enctype="multipart/form-data" class="mt-3" onsubmit="return confirm('Sahkan opname? Stok akan diubah. Berkas tidak bisa dibatalkan.')">@csrf
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto border border-slate-100">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Barang</th><th class="px-4 py-2 text-center">Sistem</th><th class="px-4 py-2 text-center">Fisik</th><th class="px-4 py-2 text-center">Selisih</th><th class="px-4 py-2">Foto bukti</th></tr></thead>
<tbody>
@foreach($beda as $it)
@php $d = $it->selisih(); @endphp
<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold text-xs">{{ $it->variant->product->nama ?? '' }} ({{ $it->variant->label() }})</td>
<td class="px-4 py-2 text-center">{{ $it->snapshot }}</td>
<td class="px-4 py-2 text-center font-extrabold">{{ $it->fisik }}</td>
<td class="px-4 py-2 text-center font-extrabold {{ $d>0?'text-blue-700':'text-red-600' }}">
    <span class="inline-flex items-center gap-1">
        <span>{{ $d>0?'+'.$d:$d }}</span>
        @if($it->besar())
        <span class="w-4 h-4 rounded-full bg-amber-100 text-amber-700 inline-flex items-center justify-center text-[10px] font-black" title="Selisih besar">!</span>
        @endif
    </span>
</td>
<td class="px-4 py-2 text-xs">
@if($it->besar())
    <input type="file" name="foto[{{ $it->variant_id }}]" accept="image/*" class="text-xs">
    @if($it->foto)
    <div class="mt-1 flex items-center gap-1 text-emerald-700 font-bold">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        <a href="/storage/{{ $it->foto }}" target="_blank" class="text-blue-700 font-bold">sudah ada</a>
    </div>
    @endif
@else
    <span class="text-slate-400">tidak wajib</span>
@endif
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3 space-y-2">
@foreach($beda as $it)
@php $d = $it->selisih(); @endphp
<div class="bg-white rounded-3xl shadow-card p-3 text-sm border border-slate-100">
<div class="flex justify-between"><b>{{ $it->variant->product->nama ?? '' }} ({{ $it->variant->label() }})</b><b class="{{ $d>0?'text-blue-700':'text-red-600' }}">{{ $d>0?'+'.$d:$d }}</b></div>
<div class="text-xs text-slate-500">sistem {{ $it->snapshot }} → fisik {{ $it->fisik }}</div>
@if($it->besar())
<div class="mt-1.5 rounded-2xl bg-amber-50 border border-amber-200 p-2 text-xs font-bold text-amber-800">
    <div class="flex items-center gap-1.5">
        <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span>Selisih besar — wajib foto:</span>
    </div>
    @if($it->foto)
    <div class="mt-1 font-normal flex items-center gap-1 text-emerald-700">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        <span>Sudah ada (<a href="/storage/{{ $it->foto }}" target="_blank" class="underline">lihat</a>), boleh ganti:</span>
    </div>
    @endif
    <input type="file" name="foto[{{ $it->variant_id }}]" accept="image/*" class="w-full text-xs mt-1 font-normal">
</div>
@endif
</div>
@endforeach
</div>
@if($beda->isEmpty() && $kosong===0)
<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3 border border-slate-100 flex items-center justify-center gap-2 text-emerald-700 font-bold">
    <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span>Semua cocok dengan sistem!</span>
</div>
@endif
<div class="flex gap-2 mt-3">
@if($kosong===0)
<button class="flex-[2] text-white font-extrabold py-3.5 rounded-3xl text-sm flex items-center justify-center gap-2 shadow-sm hover:opacity-95 transition" style="background:linear-gradient(90deg,#059669,#00E5FF)">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
    <span>Sahkan Opname</span>
</button>
@endif
</div>
</form>
<form method="POST" action="/admin/opname/{{ $sesi->id }}/batal" class="mt-2" onsubmit="return confirm('Batalkan sesi? Tidak ada stok berubah.')">@csrf<button class="w-full bg-red-50 text-red-600 font-bold py-2.5 rounded-2xl text-xs">Batalkan sesi</button></form>
@endsection
