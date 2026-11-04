@extends('layouts.admin')
@section('title','Mutasi Stok')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h13"/><path d="M14 3.5 17.5 7 14 10.5"/><path d="M20 17H7"/><path d="M10 13.5 6.5 17 10 20.5"/></svg>
    </span>
    <span>Riwayat Mutasi Stok</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Khusus admin: semua keluar/masuk stok tercatat di sini (audit trail).</div>
<form method="GET" action="/admin/mutasi" class="flex gap-2 mb-2">
<input name="q" value="{{ $q ?? '' }}" placeholder="Cari keterangan…" class="flex-1 border rounded-2xl px-4 py-2 text-sm bg-white">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm shadow-sm">Cari</button>
</form>
<a href="/admin/stok-keluar" class="flex items-center justify-center gap-1.5 bg-red-50 text-red-600 font-bold py-2.5 rounded-2xl text-xs mb-2 border border-red-100 hover:bg-red-100 transition" style="text-decoration:none">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/></svg>
    <span>Catat Barang Keluar Manual</span>
</a>
@foreach($mutasi as $m)
<div class="glass rounded-3xl shadow p-3 mb-1.5 text-xs flex gap-2 items-center">
<span class="font-extrabold px-2 py-1 rounded-xl {{ $m->tipe==='masuk'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-600' }}">{{ $m->tipe==='masuk'?'+':'−' }}{{ $m->qty }}</span>
<div class="flex-1"><b>{{ $m->product->nama ?? '(terhapus)' }}</b> @if($m->variant)<span class="font-bold text-purple-700">({{ $m->variant->label() }})</span>@endif <span class="text-slate-400">{{ $m->stok_sebelum }}→{{ $m->stok_sesudah }}</span><br><span class="text-slate-500">{{ $m->keterangan }} • {{ $m->created_at->format('d M H:i') }}</span></div>
</div>
@endforeach
<div class="mt-2">{{ $mutasi->links() }}</div>
@endsection
