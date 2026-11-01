@extends('layouts.web')
@section('title','Retur Saya')
@section('content')
<a href="/akun" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Akun</a>
<h1 class="font-extrabold text-2xl mt-1">🔄 Retur Saya</h1>
<div class="text-xs text-slate-500 mb-3">{{ $returs->count() }} pengajuan</div>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Retur</th><th class="px-4 py-2">Waktu</th><th class="px-4 py-2">Pcs</th><th class="px-4 py-2">Status</th></tr></thead>
<tbody>
@foreach($returs as $rt)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold"><a href="/retur/{{ $rt->id }}" class="text-blue-700" style="text-decoration:none">Retur #{{ $rt->id }} • {{ $rt->order->kode ?? '' }}</a></td>
<td class="px-4 py-2 text-xs">{{ $rt->created_at->format('d M H:i') }}</td>
<td class="px-4 py-2 text-xs">{{ $rt->items->sum('qty') }} pcs</td>
<td class="px-4 py-2"><span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $rt->status==='selesai'?'bg-emerald-300':($rt->status==='ditolak'?'bg-red-300':'bg-amber-200') }}">{{ strtoupper($rt->status) }}</span></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3">
@foreach($returs as $rt)
<a href="/retur/{{ $rt->id }}" class="block bg-white rounded-3xl shadow-card p-4 mb-2 text-sm" style="text-decoration:none">
<div class="flex justify-between"><b class="text-slate-900">Retur #{{ $rt->id }} • {{ $rt->order->kode ?? '' }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $rt->status==='selesai'?'bg-emerald-300':($rt->status==='ditolak'?'bg-red-300':'bg-amber-200') }}">{{ strtoupper($rt->status) }}</span></div>
<div class="text-xs text-slate-500 mt-0.5">{{ $rt->created_at->format('d M H:i') }} • {{ $rt->items->sum('qty') }} pcs</div>
</a>
@endforeach
</div>
@if($returs->isEmpty())<div class="bg-white rounded-3xl p-8 text-center text-sm shadow-card mt-3"><div class="mt-2">Belum ada pengajuan retur.</div></div>@endif
@endsection
