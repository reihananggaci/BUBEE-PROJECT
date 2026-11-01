@extends('layouts.app')
@section('title','Retur Saya')
@section('back', '/akun')
@section('content')
<h1 class="font-extrabold text-lg">🔄 Retur Saya</h1>
@foreach($returs as $rt)
<a href="/retur/{{ $rt->id }}" class="block glass rounded-3xl shadow-card p-4 mb-2 text-sm card-hover">
<div class="flex justify-between"><b>Retur #{{ $rt->id }} • {{ $rt->order->kode ?? '' }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $rt->status==='selesai'?'bg-emerald-300':($rt->status==='ditolak'?'bg-red-300':'bg-amber-200') }}">{{ strtoupper($rt->status) }}</span></div>
<div class="text-xs text-slate-500 mt-0.5">{{ $rt->created_at->format('d M H:i') }} • {{ $rt->items->sum('qty') }} pcs</div>
</a>
@endforeach
@if($returs->isEmpty())<div class="glass rounded-[2rem] p-8 text-center text-sm shadow-card"><div class="hexbee mx-auto">🐝</div><div class="mt-2">Belum ada pengajuan retur.</div></div>@endif
@endsection
