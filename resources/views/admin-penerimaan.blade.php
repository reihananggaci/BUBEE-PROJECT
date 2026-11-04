@extends('layouts.admin')
@section('title','Barang Masuk')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    </span>
    <span>Barang Masuk</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Tahap 1 catat kedatangan (stok belum +), tahap 2 verifikasi QC di gudang.</div>
<a href="/admin/penerimaan/baru" class="flex items-center justify-center gap-2 bg-purple-900 text-white font-bold py-3 rounded-2xl text-sm mb-2 shadow-sm hover:bg-purple-800 transition" style="text-decoration:none">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
    <span>Catat Kedatangan Baru</span>
</a>
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
