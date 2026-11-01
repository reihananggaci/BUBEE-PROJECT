@extends('layouts.admin')
@section('title','Moderasi Ulasan')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">⭐ Moderasi Ulasan</h1>
<div class="text-xs text-slate-500 mb-2">Hapus ulasan yang tidak pantas / spam.</div>
@foreach($reviews as $rv)
<div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>{{ $rv->product->nama ?? '' }}</b><span class="text-amber-500 text-xs">{{ str_repeat('★',$rv->rating) }}{{ str_repeat('☆',5-$rv->rating) }}</span></div>
<div class="text-xs text-slate-500">{{ $rv->user->name ?? '' }} • {{ $rv->created_at->format('d M H:i') }}</div>
@if($rv->ulasan)<div class="text-xs mt-1">{{ $rv->ulasan }}</div>@endif
@if($rv->foto)<a href="/storage/{{ $rv->foto }}" target="_blank"><img src="/storage/{{ $rv->foto }}" alt="Foto ulasan {{ $rv->product->nama ?? '' }}" class="w-16 h-16 rounded-xl object-cover mt-1"></a>@endif
<form method="POST" action="/admin/ulasan/{{ $rv->id }}" onsubmit="return confirm('Hapus ulasan ini?')">@csrf @method('DELETE')<button class="text-[11px] text-red-500 font-bold mt-1">🗑️ Hapus</button></form>
</div>
@endforeach
@if($reviews->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Belum ada ulasan.</div>@endif
<div class="mt-2">{{ $reviews->links() }}</div>
@endsection
