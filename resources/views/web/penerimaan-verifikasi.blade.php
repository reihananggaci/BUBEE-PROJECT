@extends('layouts.web-admin')
@section('title','Verifikasi '.$penerimaan->no_surat_jalan)
@section('content')
<a href="/admin/penerimaan" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Barang Masuk</a>
<h1 class="font-extrabold text-2xl mt-1">🔍 Verifikasi Gudang</h1>
<div class="text-xs text-slate-500">{{ $penerimaan->no_surat_jalan }} • {{ $penerimaan->supplier }} • Status <b>{{ strtoupper($penerimaan->status) }}</b></div>
@if($penerimaan->status==='diverifikasi')
<div class="bg-white rounded-3xl shadow-card p-5 mt-3 text-sm">
<div class="hidden lg:block overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Barang</th><th class="py-1 text-center">Klaim</th><th class="py-1 text-center">Lolos</th><th class="py-1 text-center">Rusak</th><th class="py-1">Catatan</th></tr></thead>
<tbody>
@foreach($penerimaan->items as $it)<tr class="border-t border-slate-100"><td class="py-1 font-bold text-xs">{{ $it->product->nama ?? '' }} ({{ $it->variant->label() ?? '-' }})</td><td class="py-1 text-center">{{ $it->qty_klaim }}</td><td class="py-1 text-center font-extrabold text-emerald-600">{{ $it->qty_lolos }}</td><td class="py-1 text-center font-extrabold text-red-500">{{ $it->qty_rusak }}</td><td class="py-1 text-xs text-slate-400">{{ $it->catatan }}</td></tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">@foreach($penerimaan->items as $it)<div class="py-1 border-b border-blue-50 text-xs">{{ $it->product->nama ?? '' }} — klaim {{ $it->qty_klaim }}, lolos <b class="text-emerald-600">{{ $it->qty_lolos }}</b>, rusak <b class="text-red-500">{{ $it->qty_rusak }}</b></div>@endforeach</div>
</div>
@else
<form method="POST" action="/admin/penerimaan/{{ $penerimaan->id }}/verifikasi" class="bg-white rounded-3xl shadow-card p-5 mt-3 space-y-2 text-sm max-w-2xl" onsubmit="return confirm('Sahkan hasil QC? Stok bertambah sejumlah lolos.')">@csrf
@foreach($penerimaan->items as $it)
<div class="border rounded-2xl p-3">
<div class="font-bold text-xs">{{ $it->product->nama ?? '' }} ({{ $it->variant->label() ?? '-' }}) — klaim <b>{{ $it->qty_klaim }}</b></div>
<div class="grid grid-cols-2 gap-1.5 mt-1">
<label class="text-[11px]">Lolos<input name="hasil[{{ $it->id }}][lolos]" type="number" min="0" value="{{ $it->qty_klaim }}" class="w-full border rounded-xl px-2 py-1.5 text-center font-bold" required></label>
<label class="text-[11px]">Rusak<input name="hasil[{{ $it->id }}][rusak]" type="number" min="0" value="0" class="w-full border rounded-xl px-2 py-1.5 text-center font-bold" required></label>
</div>
<input name="hasil[{{ $it->id }}][catatan]" placeholder="Catatan QC (opsional)" maxlength="200" class="w-full border rounded-xl px-2 py-1.5 text-xs mt-1">
</div>
@endforeach
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#059669,#00E5FF)">✅ SAHKAN QC</button>
</form>
<form method="POST" action="/admin/penerimaan/{{ $penerimaan->id }}" class="mt-2 max-w-2xl" onsubmit="return confirm('Batalkan catatan ini?')">@csrf @method('DELETE')<button class="w-full bg-red-50 text-red-600 font-bold py-2.5 rounded-2xl text-xs">Batalkan catatan</button></form>
@endif
@endsection
