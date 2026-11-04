@extends('layouts.web-admin')
@section('title','Barang Masuk')
@section('content')
<div class="flex justify-between items-center">
<div>
    <h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
        <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><circle cx="7" cy="18" r="2"/><path d="M15 18H9"/><circle cx="17" cy="18" r="2"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"/></svg>
        </span>
        <span>Barang Masuk</span>
    </h1>
    <div class="text-xs text-slate-500 mt-0.5">Tahap 1 catat (stok belum +) → tahap 2 verifikasi QC</div>
</div>
<a href="/admin/penerimaan/baru" class="bg-purple-900 hover:bg-purple-800 text-white font-bold py-2.5 px-5 rounded-2xl text-sm inline-flex items-center gap-1.5 transition" style="text-decoration:none">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
    <span>Catat Kedatangan</span>
</a>
</div>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto mt-3">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Surat Jalan</th><th class="px-4 py-2">Supplier</th><th class="px-4 py-2 text-center">Item</th><th class="px-4 py-2">Pencatat</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Waktu</th></tr></thead>
<tbody>
@foreach($list as $p)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold"><a href="/admin/penerimaan/{{ $p->id }}" class="text-purple-700" style="text-decoration:none">{{ $p->no_surat_jalan }}</a></td>
<td class="px-4 py-2 text-xs">{{ $p->supplier }}</td>
<td class="px-4 py-2 text-center">{{ $p->items_count }}</td>
<td class="px-4 py-2 text-xs">{{ $p->user->name ?? '' }}</td>
<td class="px-4 py-2"><span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $p->status==='diverifikasi'?'bg-emerald-300':'bg-amber-300' }}">{{ strtoupper($p->status) }}</span></td>
<td class="px-4 py-2 text-xs">{{ $p->created_at->format('d M H:i') }}</td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3">
@foreach($list as $p)
<a href="/admin/penerimaan/{{ $p->id }}" class="block bg-white rounded-3xl shadow-card p-4 mb-2 text-sm" style="text-decoration:none">
<div class="flex justify-between items-center"><b class="text-slate-900">{{ $p->no_surat_jalan }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $p->status==='diverifikasi'?'bg-emerald-300':'bg-amber-300' }}">{{ strtoupper($p->status) }}</span></div>
<div class="text-xs text-slate-500">{{ $p->supplier }} • {{ $p->items_count }} item • {{ $p->created_at->format('d M H:i') }}</div>
</a>
@endforeach
</div>
@if($list->isEmpty())<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3">Belum ada catatan kedatangan.</div>@endif

{{-- Navigasi Panah Samping (Pagination) --}}
<div class="mt-4">{{ $list->links() }}</div>
@endsection
