@extends('layouts.web-admin')
@section('title','Barang Keluar')
@section('content')
<a href="/admin/mutasi" class="text-xs font-bold text-blue-700 inline-flex items-center gap-1" style="text-decoration:none">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    <span>Mutasi Stok</span>
</a>
<h1 class="font-extrabold text-2xl mt-1 text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </span>
    <span>Barang Keluar Manual</span>
</h1>
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
