@extends('layouts.web-admin')
@section('title','Moderasi Ulasan')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    </span>
    <span>Moderasi Ulasan</span>
</h1>
<div class="text-xs text-slate-500 mb-3 mt-0.5">Hapus ulasan yang tidak pantas / spam.</div>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Waktu</th><th class="px-4 py-2">Produk</th><th class="px-4 py-2">User</th><th class="px-4 py-2">Rating</th><th class="px-4 py-2">Ulasan</th><th class="px-4 py-2"></th></tr></thead>
<tbody>
@foreach($reviews as $rv)<tr class="border-t border-slate-100">
<td class="px-4 py-2 text-xs whitespace-nowrap">{{ $rv->created_at->format('d M H:i') }}</td>
<td class="px-4 py-2 font-bold">{{ $rv->product->nama ?? '' }}</td>
<td class="px-4 py-2">{{ $rv->user->name ?? '' }}</td>
<td class="px-4 py-2 text-amber-500 text-xs whitespace-nowrap">{{ str_repeat('★',$rv->rating) }}{{ str_repeat('☆',5-$rv->rating) }}</td>
<td class="px-4 py-2 text-xs">@if($rv->ulasan){{ $rv->ulasan }}@endif @if($rv->foto)<a href="/storage/{{ $rv->foto }}" target="_blank" class="text-blue-700 font-bold inline-flex items-center gap-1"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg> Foto</a>@endif</td>
<td class="px-4 py-2"><form method="POST" action="/admin/ulasan/{{ $rv->id }}" onsubmit="return confirm('Hapus ulasan ini?')">@csrf @method('DELETE')<button class="text-[11px] text-red-500 font-bold">Hapus</button></form></td>
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
<form method="POST" action="/admin/ulasan/{{ $rv->id }}" onsubmit="return confirm('Hapus ulasan ini?')">@csrf @method('DELETE')<button class="text-[11px] text-red-500 font-bold mt-1">Hapus</button></form>
</div>
@endforeach
</div>
@if($reviews->isEmpty())<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3">Belum ada ulasan.</div>@endif
<div class="mt-2">{{ $reviews->links() }}</div>
@endsection
