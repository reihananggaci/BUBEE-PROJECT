@extends('layouts.admin')
@section('title','Kelola Retur')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">🔄 Kelola Retur & Komplain</h1>
<div class="text-xs text-slate-500 mb-2">Setujui → barang dikirim balik. <b>Selesai</b> → stok varian bertambah otomatis.</div>
@foreach($returs as $rt)
<div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>Retur #{{ $rt->id }} • {{ $rt->order->kode ?? '' }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $rt->status==='selesai'?'bg-emerald-300':($rt->status==='ditolak'?'bg-red-300':'bg-amber-200') }}">{{ strtoupper($rt->status) }}</span></div>
<div class="text-xs"><span class="font-extrabold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ strtoupper($rt->kategori ?? 'retur') }}</span> <span class="text-slate-500">{{ $rt->user->name ?? '' }} • {{ $rt->created_at->format('d M H:i') }} • {{ $rt->alasan }}</span></div>
@foreach($rt->items as $ri)<div class="text-xs">- {{ $ri->orderItem->product->nama ?? '' }} ×{{ $ri->qty }}</div>@endforeach
@if($rt->foto)<div class="text-xs">📎 <a href="/storage/{{ $rt->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto barang</a></div>@endif
<form method="POST" action="/admin/retur/{{ $rt->id }}" class="flex gap-1 mt-2">@csrf @method('PUT')
<select name="status" class="border rounded-xl px-2 py-1.5 text-xs font-bold flex-1">@foreach(['diajukan','disetujui','ditolak','selesai'] as $s)<option value="{{ $s }}" {{ $rt->status===$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
<input name="catatan_admin" value="{{ $rt->catatan_admin }}" placeholder="Catatan…" class="flex-[2] border rounded-xl px-2 py-1.5 text-xs">
<button class="bg-purple-900 text-white text-xs font-bold px-3 rounded-xl">OK</button>
</form>
</div>
@endforeach
@if($returs->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Belum ada pengajuan retur. 🎉</div>@endif
@endsection
