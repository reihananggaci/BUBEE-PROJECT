@extends('layouts.app')
@section('title','Retur Saya')
@section('back', '/akun')
@section('content')
<div class="flex items-center gap-2 mb-2">
    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Retur Saya</h1>
</div>
@foreach($returs as $rt)
<a href="/retur/{{ $rt->id }}" class="block glass rounded-3xl shadow-card p-4 mb-2 text-sm card-hover">
<div class="flex justify-between"><b>Retur #{{ $rt->id }} • {{ $rt->order->kode ?? '' }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $rt->status==='selesai'?'bg-emerald-300':($rt->status==='ditolak'?'bg-red-300':'bg-amber-200') }}">{{ strtoupper($rt->status) }}</span></div>
<div class="text-xs text-slate-500 mt-0.5">{{ $rt->created_at->format('d M H:i') }} • {{ $rt->items->sum('qty') }} pcs</div>
</a>
@endforeach
@if($returs->isEmpty())<div class="glass rounded-[2rem] p-8 text-center text-sm shadow-card"><div class="hexbee mx-auto"><svg class="w-7 h-7 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8 2 5 5 5 9c0 2.2 1 4.2 2.5 5.5L7 20l5-2 5 2-.5-5.5C18 13.2 19 11.2 19 9c0-4-3-7-7-7z"/><path d="M9 10h6M8 14h8"/></svg></div><div class="mt-2">Belum ada pengajuan retur.</div></div>@endif
@endsection
