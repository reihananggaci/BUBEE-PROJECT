@extends('layouts.admin')
@section('title','Kelola Retur')
@section('back', '/admin')
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Kelola Retur & Komplain</h1>
</div>
<div class="text-xs text-slate-500 mb-2">Setujui → barang dikirim balik. <b>Selesai</b> → stok varian bertambah otomatis.</div>
@foreach($returs as $rt)
<div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>Retur #{{ $rt->id }} • {{ $rt->order->kode ?? '' }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $rt->status==='selesai'?'bg-emerald-300':($rt->status==='ditolak'?'bg-red-300':'bg-amber-200') }}">{{ strtoupper($rt->status) }}</span></div>
<div class="text-xs"><span class="font-extrabold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ strtoupper($rt->kategori ?? 'retur') }}</span> <span class="text-slate-500">{{ $rt->user->name ?? '' }} • {{ $rt->created_at->format('d M H:i') }} • {{ $rt->alasan }}</span></div>
@foreach($rt->items as $ri)<div class="text-xs">- {{ $ri->orderItem->product->nama ?? '' }} ×{{ $ri->qty }}</div>@endforeach
@if($rt->foto)<div class="text-xs mt-1 inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg><a href="/storage/{{ $rt->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto barang</a></div>@endif
<form method="POST" action="/admin/retur/{{ $rt->id }}" class="flex gap-1 mt-2">@csrf @method('PUT')
<select name="status" class="border rounded-xl px-2 py-1.5 text-xs font-bold flex-1">@foreach(['diajukan','disetujui','ditolak','selesai'] as $s)<option value="{{ $s }}" {{ $rt->status===$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
<input name="catatan_admin" value="{{ $rt->catatan_admin }}" placeholder="Catatan…" class="flex-[2] border rounded-xl px-2 py-1.5 text-xs">
<button class="bg-purple-900 text-white text-xs font-bold px-3 rounded-xl">OK</button>
</form>
</div>
@endforeach
@if($returs->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Belum ada pengajuan retur.</div>@endif

{{-- Navigasi Panah Samping (Pagination) --}}
<div class="mt-3">{{ $returs->links() }}</div>
@endsection
