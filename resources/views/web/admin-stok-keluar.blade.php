@extends('layouts.web-admin')
@section('title','Barang Keluar')
@section('content')
<a href="/admin/mutasi" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Mutasi Stok</a>
<h1 class="font-extrabold text-2xl mt-1">📤 Barang Keluar Manual</h1>
<div class="text-xs text-slate-500 mb-3">Rusak, hilang, atau sample — stok berkurang + tercatat di mutasi.</div>
<form method="POST" action="/admin/stok-keluar" class="bg-white rounded-3xl shadow-card p-5 space-y-2 text-sm max-w-xl" onsubmit="return confirm('Keluarkan stok? Tidak bisa dibatalkan.')">@csrf
<select name="variant_id" class="w-full border rounded-2xl px-3 py-2.5" required>
<option value="">— Pilih barang + varian —</option>
@foreach($products as $p)@foreach($p->variants as $v)<option value="{{ $v->id }}">{{ $p->nama }} • {{ $v->label() }} (sisa {{ $v->stok }})</option>@endforeach@endforeach
</select>
<div class="grid grid-cols-2 gap-2">
<input name="qty" type="number" min="1" placeholder="Qty *" class="border rounded-2xl px-3 py-2.5" required>
<select name="alasan" class="border rounded-2xl px-3 py-2.5 font-bold" required>
<option value="rusak">Rusak</option><option value="hilang">Hilang</option><option value="sample">Sample</option><option value="lainnya">Lainnya</option>
</select>
</div>
<input name="catatan" placeholder="Catatan (opsional)" maxlength="200" class="w-full border rounded-2xl px-3 py-2.5">
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#b45309,#ef4444)">KELUARKAN STOK</button>
</form>
@endsection
