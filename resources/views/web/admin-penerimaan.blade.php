@extends('layouts.web-admin')
@section('title','Barang Masuk')
@section('content')
<div class="flex justify-between items-center">
<div><h1 class="font-extrabold text-2xl">🚚 Barang Masuk</h1><div class="text-xs text-slate-500">Tahap 1 catat (stok belum +) → tahap 2 verifikasi QC</div></div>
<a href="/admin/penerimaan/baru" class="bg-purple-900 text-white font-bold py-2.5 px-5 rounded-2xl text-sm" style="text-decoration:none">➕ Catat Kedatangan</a>
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
