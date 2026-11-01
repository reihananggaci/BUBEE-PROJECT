@extends('layouts.admin')
@section('title','Barang Keluar')
@section('back', '/admin/mutasi')
@section('content')
<h1 class="font-extrabold text-lg">📤 Barang Keluar Manual</h1>
<div class="text-xs text-slate-500 mb-2">Rusak, hilang, atau sample — stok berkurang + tercatat di mutasi.</div>
<form method="POST" action="/admin/stok-keluar" class="glass rounded-3xl shadow-card p-4 mt-2 space-y-2 text-sm" onsubmit="return confirm('Keluarkan stok? Tidak bisa dibatalkan.')">@csrf
<select name="variant_id" class="w-full border rounded-2xl px-3 py-2.5" required>
<option value="">— Pilih barang + varian —</option>
@foreach($products as $p)
@foreach($p->variants as $v)<option value="{{ $v->id }}">{{ $p->nama }} • {{ $v->label() }} (sisa {{ $v->stok }})</option>@endforeach
@endforeach
</select>
<div class="flex gap-1.5">
<input name="qty" type="number" min="1" placeholder="Qty *" class="flex-1 border rounded-2xl px-3 py-2.5" required>
<select name="alasan" class="flex-1 border rounded-2xl px-3 py-2.5 font-bold" required>
<option value="rusak">Rusak</option><option value="hilang">Hilang</option><option value="sample">Sample</option><option value="lainnya">Lainnya</option>
</select>
</div>
<input name="catatan" placeholder="Catatan (opsional)" maxlength="200" class="w-full border rounded-2xl px-3 py-2.5">
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#b45309,#ef4444)">KELUARKAN STOK</button>
</form>
@endsection
