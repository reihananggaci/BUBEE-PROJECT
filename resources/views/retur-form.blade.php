@extends('layouts.app')
@section('title','Ajukan Retur')
@section('back', '/pesanan/'.$order->id)
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Ajukan Retur / Komplain</h1>
</div>
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
<option value="retur">Retur (kembalikan barang)</option>
<option value="rusak">Barang rusak / cacat produksi</option>
<option value="salah_kirim">Salah kirim varian atau produk</option>
<option value="telat">Paket terlambat datang</option>
<option value="komplain_lain">Komplain lainnya</option>
</select>
<div>
    <label class="text-xs inline-flex items-center gap-1 font-bold text-slate-700">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        <span>Foto barang bukti (opsional)</span>
    </label>
    <input type="file" name="foto" accept="image/*" class="w-full text-xs mt-1">
</div>
<button class="w-full text-white font-extrabold py-3 rounded-2xl flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#b45309,#f59e0b)">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    <span>Kirim Pengajuan Retur</span>
</button>
</form>
<div class="text-[11px] text-slate-500 mt-1">Isi 0 untuk barang yang tidak diretur. Stok kembali setelah admin menyelesaikan retur.</div>
@endsection
