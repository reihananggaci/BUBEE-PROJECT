@extends('layouts.admin')
@section('title','Barang Masuk')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">🚚 Barang Masuk</h1>
<div class="text-xs text-slate-500 mb-2">Tahap 1 catat kedatangan (stok belum +), tahap 2 verifikasi QC di gudang.</div>
<a href="/admin/penerimaan/baru" class="block text-center bg-purple-900 text-white font-bold py-3 rounded-2xl text-sm mb-2">➕ Catat Kedatangan</a>
@foreach($list as $p)
<a href="/admin/penerimaan/{{ $p->id }}" class="block glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between items-center"><b>{{ $p->no_surat_jalan }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $p->status==='diverifikasi'?'bg-emerald-300':'bg-amber-300' }}">{{ strtoupper($p->status) }}</span></div>
<div class="text-xs text-slate-500">{{ $p->supplier }} • {{ $p->items_count }} item • {{ $p->user->name ?? '' }} • {{ $p->created_at->format('d M H:i') }}</div>
</a>
@endforeach
@if($list->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Belum ada catatan kedatangan.</div>@endif

{{-- Navigasi Panah Samping (Pagination) --}}
<div class="mt-3">{{ $list->links() }}</div>
@endsection
