@extends('layouts.admin')
@section('title','Moderasi Ulasan')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    </span>
    <span>Moderasi Ulasan</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Hapus ulasan yang tidak pantas / spam.</div>
@foreach($reviews as $rv)
<div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>{{ $rv->product->nama ?? '' }}</b><span class="text-amber-500 text-xs">{{ str_repeat('★',$rv->rating) }}{{ str_repeat('☆',5-$rv->rating) }}</span></div>
<div class="text-xs text-slate-500">{{ $rv->user->name ?? '' }} • {{ $rv->created_at->format('d M H:i') }}</div>
@if($rv->ulasan)<div class="text-xs mt-1">{{ $rv->ulasan }}</div>@endif
@if($rv->foto)<a href="/storage/{{ $rv->foto }}" target="_blank"><img src="/storage/{{ $rv->foto }}" alt="Foto ulasan {{ $rv->product->nama ?? '' }}" class="w-16 h-16 rounded-xl object-cover mt-1"></a>@endif
<form method="POST" action="/admin/ulasan/{{ $rv->id }}" onsubmit="return confirm('Hapus ulasan ini?')">@csrf @method('DELETE')<button class="inline-flex items-center gap-1 text-[11px] text-red-500 hover:text-red-700 font-bold mt-1"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg><span>Hapus</span></button></form>
</div>
@endforeach
@if($reviews->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Belum ada ulasan.</div>@endif
<div class="mt-2">{{ $reviews->links() }}</div>
@endsection
