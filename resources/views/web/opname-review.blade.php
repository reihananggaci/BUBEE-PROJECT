@extends('layouts.web-admin')
@section('title','Review Sesi #'.$sesi->id)
@section('content')
<a href="/admin/opname/{{ $sesi->id }}" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali hitung</a>
<h1 class="font-extrabold text-2xl mt-1">🧾 Review Sesi #{{ $sesi->id }}</h1>
<div class="text-xs text-slate-500">{{ $beda->count() }} selisih • {{ $kosong }} belum dihitung</div>
@if($kosong>0)<div class="rounded-2xl p-3 mt-2 text-xs font-bold bg-amber-50 border border-amber-200 text-amber-800">⏳ Masih ada {{ $kosong }} varian belum dihitung. <a href="/admin/opname/{{ $sesi->id }}?belum=1" class="underline">Hitung dulu →</a></div>@endif
<form method="POST" action="/admin/opname/{{ $sesi->id }}/sahkan" enctype="multipart/form-data" class="mt-3" onsubmit="return confirm('Sahkan opname? Stok akan diubah. Berkas tidak bisa dibatalkan.')">@csrf
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Barang</th><th class="px-4 py-2 text-center">Sistem</th><th class="px-4 py-2 text-center">Fisik</th><th class="px-4 py-2 text-center">Selisih</th><th class="px-4 py-2">Foto bukti</th></tr></thead>
<tbody>
@foreach($beda as $it)
@php $d = $it->selisih(); @endphp
<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold text-xs">{{ $it->variant->product->nama ?? '' }} ({{ $it->variant->label() }})</td>
<td class="px-4 py-2 text-center">{{ $it->snapshot }}</td>
<td class="px-4 py-2 text-center font-extrabold">{{ $it->fisik }}</td>
<td class="px-4 py-2 text-center font-extrabold {{ $d>0?'text-blue-700':'text-red-600' }}">{{ $d>0?'+'.$d:$d }}{{ $it->besar()?' ⚠️':'' }}</td>
<td class="px-4 py-2 text-xs">@if($it->besar())<input type="file" name="foto[{{ $it->variant_id }}]" accept="image/*" class="text-xs">@if($it->foto)<div class="mt-1">✅ <a href="/storage/{{ $it->foto }}" target="_blank" class="text-blue-700 font-bold">sudah ada</a></div>@endif @else<span class="text-slate-400">tidak wajib</span>@endif</td>
</tr>
@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3 space-y-2">
@foreach($beda as $it)
@php $d = $it->selisih(); @endphp
<div class="bg-white rounded-3xl shadow-card p-3 text-sm">
<div class="flex justify-between"><b>{{ $it->variant->product->nama ?? '' }} ({{ $it->variant->label() }})</b><b class="{{ $d>0?'text-blue-700':'text-red-600' }}">{{ $d>0?'+'.$d:$d }}</b></div>
<div class="text-xs text-slate-500">sistem {{ $it->snapshot }} → fisik {{ $it->fisik }}</div>
@if($it->besar())
<div class="mt-1.5 rounded-2xl bg-amber-50 border border-amber-200 p-2 text-xs font-bold text-amber-800">⚠️ Selisih besar — wajib foto
@if($it->foto)<div class="mt-1 font-normal">✅ Sudah ada (<a href="/storage/{{ $it->foto }}" target="_blank" class="underline">lihat</a>), boleh ganti:</div>@endif
<input type="file" name="foto[{{ $it->variant_id }}]" accept="image/*" class="w-full text-xs mt-1 font-normal">
</div>
@endif
</div>
@endforeach
</div>
@if($beda->isEmpty() && $kosong===0)<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3">🎉 Semua cocok dengan sistem!</div>@endif
<div class="flex gap-2 mt-3">
@if($kosong===0)<button class="flex-[2] text-white font-extrabold py-3.5 rounded-3xl text-sm" style="background:linear-gradient(90deg,#059669,#00E5FF)">✅ SAHKAN OPNAME</button>@endif
</div>
</form>
<form method="POST" action="/admin/opname/{{ $sesi->id }}/batal" class="mt-2" onsubmit="return confirm('Batalkan sesi? Tidak ada stok berubah.')">@csrf<button class="w-full bg-red-50 text-red-600 font-bold py-2.5 rounded-2xl text-xs">Batalkan sesi</button></form>
@endsection
