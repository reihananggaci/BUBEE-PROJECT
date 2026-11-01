@extends('layouts.app')
@section('title','Ajukan Retur')
@section('back', '/pesanan/'.$order->id)
@section('content')
<h1 class="font-extrabold text-lg">🔄 Ajukan Retur / Komplain</h1>
<div class="text-xs text-slate-500">{{ $order->kode }} • Pilih kategori + barang + jumlah</div>
<form method="POST" action="/pesanan/{{ $order->id }}/retur" enctype="multipart/form-data" class="glass rounded-3xl shadow-card p-4 mt-2 space-y-2 text-sm">@csrf
@foreach($order->items as $it)
<label class="flex items-center gap-2 border rounded-2xl px-3 py-2">
<div class="flex-1"><b>{{ $it->product->nama ?? '' }}</b> ({{ $it->variant?->label() }})<div class="text-[11px] text-slate-500">dibeli ×{{ $it->qty }}</div></div>
<input type="number" name="items[{{ $it->id }}]" value="0" min="0" max="{{ $it->qty }}" class="w-16 border rounded-xl px-2 py-1.5 text-center font-bold">
</label>
@endforeach
<textarea name="alasan" placeholder="Ceritakan komplain * (cth: size kebesaran / cacat / salah kirim)" class="w-full border rounded-2xl px-3 py-2.5" required></textarea>
<select name="kategori" class="w-full border rounded-2xl px-3 py-2.5">
<option value="retur">🔄 Retur (kembalikan barang)</option>
<option value="rusak">💔 Barang rusak/cacat</option>
<option value="salah_kirim">📦 Salah kirim</option>
<option value="telat">⏰ Paket telat</option>
<option value="komplain_lain">💬 Komplain lain</option>
</select>
<div><label class="text-xs">📷 Foto barang (opsional)</label><input type="file" name="foto" accept="image/*" class="w-full text-xs mt-1"></div>
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#b45309,#f59e0b)">KIRIM PENGAJUAN 🔄</button>
</form>
<div class="text-[11px] text-slate-500 mt-1">Isi 0 untuk barang yang tidak diretur. Stok kembali setelah admin menyelesaikan retur.</div>
@endsection
