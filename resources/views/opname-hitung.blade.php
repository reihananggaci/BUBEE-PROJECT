@extends('layouts.admin')
@section('title','Hitung Sesi #'.$sesi->id)
@section('back', '/admin/opname')
@section('content')
<h1 class="font-extrabold text-lg">🔢 Sesi #{{ $sesi->id }} ({{ $sesi->cakupan }})</h1>
<div class="text-xs text-slate-500">Terisi {{ $sesi->terisi() }}/{{ $sesi->total() }} varian • <a href="/admin/opname/{{ $sesi->id }}?belum=1" class="font-bold text-purple-700">yang belum saja →</a></div>
<div class="flex gap-1.5 mt-2 text-[11px] font-bold overflow-x-auto pb-1">
@foreach($products as $i => $p)
<a href="/admin/opname/{{ $sesi->id }}?p={{ $i }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ $i===$pos?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}">{{ $i+1 }}. {{ \Str::limit($p->nama,14) }}</a>
@endforeach
</div>
<div class="glass rounded-3xl shadow-card p-4 mt-2">
<div class="font-extrabold">{{ $pos+1 }}. {{ $product->nama }}</div>
@if($product->foto)<img src="/storage/{{ $product->foto }}" alt="{{ $product->nama }}" class="w-full h-40 rounded-2xl object-cover mt-2">@endif
<form method="POST" action="/admin/opname/{{ $sesi->id }}/hitung" class="mt-2 space-y-2">@csrf
<input type="hidden" name="next" value="{{ $pos+1 }}">
@foreach($product->variants as $v)
@php $it = $itemMap->get($v->id); @endphp
<label class="flex items-center gap-2 border rounded-2xl px-3 py-2">
<div class="flex-1"><b class="text-sm">{{ $v->label() }}</b><div class="text-[11px] text-slate-400">sistem: {{ $v->stok }}</div></div>
<button type="button" class="w-9 h-9 rounded-xl bg-slate-100 font-extrabold" onclick="this.nextElementSibling.stepDown()">−</button>
<input name="fisik[{{ $v->id }}]" value="{{ $it->fisik ?? '' }}" type="number" min="0" placeholder="0" class="w-16 border rounded-xl px-2 py-2 text-center font-extrabold">
<button type="button" class="w-9 h-9 rounded-xl bg-slate-100 font-extrabold" onclick="this.previousElementSibling.stepUp()">+</button>
</label>
@endforeach
<div class="flex gap-1.5">
@if($pos>0)<a href="/admin/opname/{{ $sesi->id }}?p={{ $pos-1 }}" class="flex-1 text-center bg-slate-100 font-bold py-3 rounded-2xl text-sm">← Sebelumnya</a>@endif
<button class="flex-[2] text-white font-extrabold py-3 rounded-2xl text-sm" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">Simpan & Lanjut →</button>
</div>
</form>
</div>
<a href="/admin/opname/{{ $sesi->id }}/review" class="block text-center mt-2 text-sm font-bold text-purple-700 glass rounded-2xl py-2.5">Review & Sahkan →</a>
@endsection
