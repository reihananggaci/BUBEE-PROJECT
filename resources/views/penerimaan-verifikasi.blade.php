@extends('layouts.admin')
@section('title','Verifikasi '.$penerimaan->no_surat_jalan)
@section('back', '/admin/penerimaan')
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Verifikasi Gudang</h1>
</div>
<div class="text-xs text-slate-500">{{ $penerimaan->no_surat_jalan }} • {{ $penerimaan->supplier }} • Status <b>{{ strtoupper($penerimaan->status) }}</b></div>
@if($penerimaan->status==='diverifikasi')
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm">
@foreach($penerimaan->items as $it)<div class="py-1 border-b border-blue-50 text-xs">{{ $it->product->nama ?? '' }} ({{ $it->variant->label() ?? '-' }}) — klaim {{ $it->qty_klaim }}, lolos <b class="text-emerald-600">{{ $it->qty_lolos }}</b>, rusak <b class="text-red-500">{{ $it->qty_rusak }}</b>@if($it->catatan)<br><span class="text-slate-400">{{ $it->catatan }}</span>@endif</div>@endforeach
</div>
@else
<form method="POST" action="/admin/penerimaan/{{ $penerimaan->id }}/verifikasi" class="glass rounded-3xl shadow-card p-4 mt-2 space-y-2 text-sm" onsubmit="return confirm('Sahkan hasil QC? Stok bertambah sejumlah lolos.')">@csrf
@foreach($penerimaan->items as $it)
<div class="border rounded-2xl p-2.5">
<div class="font-bold text-xs">{{ $it->product->nama ?? '' }} ({{ $it->variant->label() ?? '-' }})</div>
<div class="text-[11px] text-slate-500">Klaim: <b>{{ $it->qty_klaim }}</b> • Lolos + Rusak harus = klaim</div>
<div class="flex gap-1.5 mt-1">
<label class="flex-1 text-[11px]">Lolos<input name="hasil[{{ $it->id }}][lolos]" type="number" min="0" value="{{ $it->qty_klaim }}" class="w-full border rounded-xl px-2 py-1.5 text-center font-bold lolos-in" required></label>
<label class="flex-1 text-[11px]">Rusak<input name="hasil[{{ $it->id }}][rusak]" type="number" min="0" value="0" class="w-full border rounded-xl px-2 py-1.5 text-center font-bold rusak-in" required></label>
</div>
<input name="hasil[{{ $it->id }}][catatan]" placeholder="Catatan QC (opsional)" maxlength="200" class="w-full border rounded-xl px-2 py-1.5 text-xs mt-1">
</div>
@endforeach
<button class="w-full text-white font-extrabold py-3 rounded-2xl flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#059669,#00E5FF)"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg><span>SAHKAN HASIL QC</span></button>
</form>
<form method="POST" action="/admin/penerimaan/{{ $penerimaan->id }}" class="mt-2" onsubmit="return confirm('Batalkan catatan ini?')">@csrf @method('DELETE')<button class="w-full bg-red-50 text-red-600 font-bold py-2.5 rounded-2xl text-xs">Batalkan catatan</button></form>
@endif
@endsection
