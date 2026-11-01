@extends('layouts.web')
@section('title','Riwayat Hemat')
@section('content')
<a href="/reseller" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Reseller Center</a>
<h1 class="font-extrabold text-2xl mt-1">💰 Riwayat Keuntungan</h1>
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="rounded-3xl p-6 text-white shadow-card lg:sticky lg:top-20 self-start" style="background:linear-gradient(135deg,#083344,#0e7490)">
<div class="text-xs opacity-70">Total hemat dari harga modal</div>
<div class="font-extrabold text-3xl">Rp {{ number_format($totalHemat) }}</div>
<div class="text-xs opacity-70 mt-1">{{ $orders->count() }} order tercatat</div>
</div>
<div class="lg:col-span-2">
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Order</th><th class="px-4 py-2">Tanggal</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Voucher</th><th class="px-4 py-2 text-right">Hemat</th></tr></thead>
<tbody>
@foreach($orders as $o)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold"><a href="/pesanan/{{ $o->id }}" class="text-blue-700" style="text-decoration:none">{{ $o->kode }}</a></td>
<td class="px-4 py-2 text-xs">{{ $o->created_at->format('d M Y') }}</td>
<td class="px-4 py-2 text-xs">{{ $o->status }}</td>
<td class="px-4 py-2 text-xs">@if($o->voucher)🎟️ {{ $o->voucher->kode }} (-Rp {{ number_format($o->diskon) }})@else - @endif</td>
<td class="px-4 py-2 text-right font-extrabold text-emerald-600">Rp {{ number_format($o->hemat) }}</td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">
@foreach($orders as $o)
<a href="/pesanan/{{ $o->id }}" class="block bg-white rounded-3xl shadow-card p-3 mt-2 text-sm" style="text-decoration:none">
<div class="flex justify-between"><b class="text-slate-900">{{ $o->kode }}</b><b class="text-emerald-600">hemat Rp {{ number_format($o->hemat) }}</b></div>
<div class="text-xs text-slate-500">{{ $o->created_at->format('d M Y') }} • {{ $o->status }}@if($o->voucher) • 🎟️ {{ $o->voucher->kode }} (-Rp {{ number_format($o->diskon) }})@endif</div>
</a>
@endforeach
</div>
@if($orders->isEmpty())<div class="bg-white rounded-3xl p-8 text-center text-sm mt-2">Belum ada order. Mulai dari <a href="/reseller/etalase" class="font-bold text-cyan-700">Etalase →</a></div>@endif
</div>
</div>
@endsection
