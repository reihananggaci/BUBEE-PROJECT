@extends('layouts.admin')
@section('title','Mutasi Stok')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">📜 Riwayat Mutasi Stok</h1>
<div class="text-xs text-slate-500 mb-2">Khusus admin: semua keluar/masuk stok tercatat di sini (audit trail).</div>
<form method="GET" action="/admin/mutasi" class="flex gap-2 mb-2">
<input name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari keterangan…" class="flex-1 border rounded-2xl px-4 py-2 text-sm">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
</form>
<a href="/admin/stok-keluar" class="block text-center bg-red-50 text-red-600 font-bold py-2.5 rounded-2xl text-xs mb-2">📤 Barang Keluar Manual</a>
@foreach($mutasi as $m)
<div class="glass rounded-3xl shadow p-3 mb-1.5 text-xs flex gap-2 items-center">
<span class="font-extrabold px-2 py-1 rounded-xl {{ $m->tipe==='masuk'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-600' }}">{{ $m->tipe==='masuk'?'+':'−' }}{{ $m->qty }}</span>
<div class="flex-1"><b>{{ $m->product->nama ?? '(terhapus)' }}</b> @if($m->variant)<span class="font-bold text-purple-700">({{ $m->variant->label() }})</span>@endif <span class="text-slate-400">{{ $m->stok_sebelum }}→{{ $m->stok_sesudah }}</span><br><span class="text-slate-500">{{ $m->keterangan }} • {{ $m->created_at->format('d M H:i') }}</span></div>
</div>
@endforeach
<div class="mt-2">{{ $mutasi->links() }}</div>
@endsection
