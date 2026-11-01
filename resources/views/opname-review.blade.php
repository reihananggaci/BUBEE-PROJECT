@extends('layouts.admin')
@section('title','Review Sesi #'.$sesi->id)
@section('back', '/admin/opname/'.$sesi->id)
@section('content')
<h1 class="font-extrabold text-lg">🧾 Review Sesi #{{ $sesi->id }}</h1>
<div class="text-xs text-slate-500">{{ $beda->count() }} selisih • {{ $kosong }} belum dihitung</div>
@if($kosong>0)<div class="rounded-2xl p-3 mt-2 text-xs font-bold bg-amber-50 border border-amber-200 text-amber-800">⏳ Masih ada {{ $kosong }} varian belum dihitung. <a href="/admin/opname/{{ $sesi->id }}?belum=1" class="underline">Hitung dulu →</a></div>@endif
@if($beda->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm mt-2">🎉 Semua cocok dengan sistem!</div>@endif
<form method="POST" action="/admin/opname/{{ $sesi->id }}/sahkan" enctype="multipart/form-data" class="mt-2 space-y-2" onsubmit="return confirm('Sahkan opname? Stok akan diubah. Berkas tidak bisa dibatalkan.')">@csrf
@foreach($beda as $it)
@php $d = $it->selisih(); @endphp
<div class="glass rounded-3xl shadow-card p-3 text-sm">
<div class="flex justify-between"><b>{{ $it->variant->product->nama ?? '' }} ({{ $it->variant->label() }})</b><b class="{{ $d>0?'text-blue-700':'text-red-600' }}">{{ $d>0?'+'.$d:$d }}</b></div>
<div class="text-xs text-slate-500">sistem {{ $it->snapshot }} → fisik {{ $it->fisik }}</div>
@if($it->besar())
<div class="mt-1.5 rounded-2xl bg-amber-50 border border-amber-200 p-2 text-xs font-bold text-amber-800">⚠️ Selisih besar — wajib foto bukti
@if($it->foto)<div class="mt-1">✅ Foto sudah ada (<a href="/storage/{{ $it->foto }}" target="_blank" class="underline">lihat</a>), boleh ganti:</div>@endif
<input type="file" name="foto[{{ $it->variant_id }}]" accept="image/*" class="w-full text-xs mt-1 font-normal">
</div>
@elseif($it->foto)<div class="text-xs mt-1">📷 <a href="/storage/{{ $it->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto</a></div>@endif
</div>
@endforeach
@if($kosong===0)<button class="w-full text-white font-extrabold py-3.5 rounded-3xl text-sm" style="background:linear-gradient(90deg,#059669,#00E5FF)">✅ SAHKAN OPNAME</button>@endif
</form>
<form method="POST" action="/admin/opname/{{ $sesi->id }}/batal" class="mt-2" onsubmit="return confirm('Batalkan sesi? Tidak ada stok berubah.')">@csrf<button class="w-full bg-red-50 text-red-600 font-bold py-2.5 rounded-2xl text-xs">Batalkan sesi</button></form>
@endsection
