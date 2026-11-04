@extends('layouts.web')
@section('title','Ajukan Retur')
@section('content')
<a href="/pesanan/{{ $order->id }}" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali ke Order {{ $order->kode }}</a>
<h1 class="font-extrabold text-2xl text-slate-900 mt-2 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V5.5A1.5 1.5 0 0 1 5.5 4H9"/><path d="M4 5.5A9 9 0 1 1 3 13"/><path d="M3 13h5"/></svg>
    </span>
    <span>Ajukan Retur / Komplain</span>
</h1>
<div class="text-xs text-slate-500">{{ $order->kode }} • Pilih kategori + barang + jumlah yang dikembalikan</div>
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<form method="POST" action="/pesanan/{{ $order->id }}/retur" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-card p-5 space-y-2 text-sm lg:col-span-2">@csrf
@foreach($order->items as $it)
<label class="flex items-center gap-2 border rounded-2xl px-3 py-2">
<div class="flex-1"><b>{{ $it->product->nama ?? '' }}</b> ({{ $it->variant?->label() }})<div class="text-[11px] text-slate-500">dibeli ×{{ $it->qty }}</div></div>
<input type="number" name="items[{{ $it->id }}]" value="0" min="0" max="{{ $it->qty }}" class="w-16 border rounded-xl px-2 py-1.5 text-center font-bold">
</label>
@endforeach
<textarea name="alasan" placeholder="Ceritakan komplain * (cth: size kebesaran / barang cacat / salah kirim)" class="w-full border rounded-2xl px-3 py-2.5" required></textarea>
<select name="kategori" class="w-full border rounded-2xl px-3 py-2.5">
<option value="retur">Retur (kembalikan barang)</option>
<option value="rusak">Barang rusak/cacat</option>
<option value="salah_kirim">Salah kirim (varian/warna)</option>
<option value="telat">Paket telat</option>
<option value="komplain_lain">Komplain lain</option>
</select>
<div>
    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 mb-1">
        <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
        <span>Foto barang (opsional)</span>
    </label>
    <input type="file" name="foto" accept="image/*" class="w-full text-xs">
</div>
<button class="w-full text-white font-extrabold py-3 rounded-2xl shadow-sm hover:opacity-95 transition" style="background:linear-gradient(90deg,#b45309,#f59e0b)">Kirim Pengajuan</button>
</form>
<div class="lg:sticky lg:top-20 self-start bg-amber-50 border border-amber-200 rounded-3xl p-5 text-xs text-amber-800">
<b class="flex items-center gap-1.5 text-amber-900 mb-1">
    <svg class="w-4 h-4 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    <span>Ketentuan retur</span>
</b>
Isi 0 untuk barang yang tidak diretur. Stok kembali setelah admin menyelesaikan retur.
</div>
</div>
@endsection
