@extends('layouts.web-admin')
@section('title','Kelola Retur')
@section('content')
<h1 class="font-extrabold text-2xl">🔄 Kelola Retur & Komplain</h1>
<div class="text-xs text-slate-500 mb-3">Setujui → barang dikirim balik. <b>Selesai</b> → stok varian bertambah otomatis.</div>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Retur</th><th class="px-4 py-2">User</th><th class="px-4 py-2">Item</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Update</th></tr></thead>
<tbody>
@foreach($returs as $rt)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold whitespace-nowrap">Retur #{{ $rt->id }} • {{ $rt->order->kode ?? '' }} <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ strtoupper($rt->kategori ?? 'retur') }}</span><br><span class="font-normal text-[11px] text-slate-400">{{ $rt->created_at->format('d M H:i') }} • {{ $rt->alasan }}</span>@if($rt->foto)<br><a href="/storage/{{ $rt->foto }}" target="_blank" class="text-blue-700 font-bold text-xs">📎 Lihat foto</a>@endif</td>
<td class="px-4 py-2">{{ $rt->user->name ?? '' }}</td>
<td class="px-4 py-2 text-xs">@foreach($rt->items as $ri)<div>- {{ $ri->orderItem->product->nama ?? '' }} ×{{ $ri->qty }}</div>@endforeach</td>
<td class="px-4 py-2"><span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $rt->status==='selesai'?'bg-emerald-300':($rt->status==='ditolak'?'bg-red-300':'bg-amber-200') }}">{{ strtoupper($rt->status) }}</span></td>
<td class="px-4 py-2"><form method="POST" action="/admin/retur/{{ $rt->id }}" class="flex gap-1">@csrf @method('PUT')
<select name="status" class="border rounded-xl px-2 py-1 text-xs font-bold">@foreach(['diajukan','disetujui','ditolak','selesai'] as $s)<option value="{{ $s }}" {{ $rt->status===$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
<input name="catatan_admin" value="{{ $rt->catatan_admin }}" placeholder="Catatan…" class="border rounded-xl px-2 py-1 text-xs w-28">
<button class="bg-purple-900 text-white text-xs font-bold px-3 rounded-xl">OK</button>
</form></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3">
@foreach($returs as $rt)
<div class="bg-white rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>Retur #{{ $rt->id }} • {{ $rt->order->kode ?? '' }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $rt->status==='selesai'?'bg-emerald-300':($rt->status==='ditolak'?'bg-red-300':'bg-amber-200') }}">{{ strtoupper($rt->status) }}</span></div>
<div class="text-xs text-slate-500">{{ $rt->user->name ?? '' }} • {{ $rt->created_at->format('d M H:i') }} • {{ $rt->alasan }}</div>
@foreach($rt->items as $ri)<div class="text-xs">- {{ $ri->orderItem->product->nama ?? '' }} ×{{ $ri->qty }}</div>@endforeach
@if($rt->foto)<div class="text-xs">📎 <a href="/storage/{{ $rt->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto barang</a></div>@endif
<form method="POST" action="/admin/retur/{{ $rt->id }}" class="flex gap-1 mt-2">@csrf @method('PUT')
<select name="status" class="border rounded-xl px-2 py-1.5 text-xs font-bold flex-1">@foreach(['diajukan','disetujui','ditolak','selesai'] as $s)<option value="{{ $s }}" {{ $rt->status===$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
<input name="catatan_admin" value="{{ $rt->catatan_admin }}" placeholder="Catatan…" class="flex-[2] border rounded-xl px-2 py-1.5 text-xs">
<button class="bg-purple-900 text-white text-xs font-bold px-3 rounded-xl">OK</button>
</form>
</div>
@endforeach
</div>
@if($returs->isEmpty())<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3">Belum ada pengajuan retur. 🎉</div>@endif

{{-- Navigasi Panah Samping (Pagination) --}}
<div class="mt-4">{{ $returs->links() }}</div>
@endsection
