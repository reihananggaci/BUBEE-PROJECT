@extends('layouts.web-admin')
@section('title','Moderasi Ulasan')
@section('content')
<h1 class="font-extrabold text-2xl">⭐ Moderasi Ulasan</h1>
<div class="text-xs text-slate-500 mb-3">Hapus ulasan yang tidak pantas / spam.</div>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Waktu</th><th class="px-4 py-2">Produk</th><th class="px-4 py-2">User</th><th class="px-4 py-2">Rating</th><th class="px-4 py-2">Ulasan</th><th class="px-4 py-2"></th></tr></thead>
<tbody>
@foreach($reviews as $rv)<tr class="border-t border-slate-100">
<td class="px-4 py-2 text-xs whitespace-nowrap">{{ $rv->created_at->format('d M H:i') }}</td>
<td class="px-4 py-2 font-bold">{{ $rv->product->nama ?? '' }}</td>
<td class="px-4 py-2">{{ $rv->user->name ?? '' }}</td>
<td class="px-4 py-2 text-amber-500 text-xs whitespace-nowrap">{{ str_repeat('★',$rv->rating) }}{{ str_repeat('☆',5-$rv->rating) }}</td>
<td class="px-4 py-2 text-xs">@if($rv->ulasan){{ $rv->ulasan }}@endif @if($rv->foto)<a href="/storage/{{ $rv->foto }}" target="_blank" class="text-blue-700 font-bold">📷 foto</a>@endif</td>
<td class="px-4 py-2"><form method="POST" action="/admin/ulasan/{{ $rv->id }}" onsubmit="return confirm('Hapus ulasan ini?')">@csrf @method('DELETE')<button class="text-[11px] text-red-500 font-bold">🗑️ Hapus</button></form></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3">
@foreach($reviews as $rv)
<div class="bg-white rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>{{ $rv->product->nama ?? '' }}</b><span class="text-amber-500 text-xs">{{ str_repeat('★',$rv->rating) }}{{ str_repeat('☆',5-$rv->rating) }}</span></div>
<div class="text-xs text-slate-500">{{ $rv->user->name ?? '' }} • {{ $rv->created_at->format('d M H:i') }}</div>
@if($rv->ulasan)<div class="text-xs mt-1">{{ $rv->ulasan }}</div>@endif
@if($rv->foto)<a href="/storage/{{ $rv->foto }}" target="_blank"><img src="/storage/{{ $rv->foto }}" class="w-16 h-16 rounded-xl object-cover mt-1" alt=""></a>@endif
<form method="POST" action="/admin/ulasan/{{ $rv->id }}" onsubmit="return confirm('Hapus ulasan ini?')">@csrf @method('DELETE')<button class="text-[11px] text-red-500 font-bold mt-1">🗑️ Hapus</button></form>
</div>
@endforeach
</div>
@if($reviews->isEmpty())<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3">Belum ada ulasan.</div>@endif
<div class="mt-2">{{ $reviews->links() }}</div>
@endsection
