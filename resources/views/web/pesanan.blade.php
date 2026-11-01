@extends('layouts.web')
@section('title','Pesanan Saya')
@section('content')
<h1 class="font-extrabold text-2xl">Pesanan Saya 📦</h1>
<div class="text-xs text-slate-500 mb-3">{{ $orders->count() }} order • klik untuk detail & tracking</div>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Kode</th><th class="px-4 py-2">Waktu</th><th class="px-4 py-2">Kurir</th><th class="px-4 py-2">Isi</th><th class="px-4 py-2">Status</th><th class="px-4 py-2 text-right">Total</th></tr></thead>
<tbody>
@foreach($orders as $o)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold whitespace-nowrap"><a href="/pesanan/{{ $o->id }}" class="text-blue-700" style="text-decoration:none">{{ $o->kode }}</a></td>
<td class="px-4 py-2 text-xs whitespace-nowrap">{{ $o->created_at->format('d M H:i') }}</td>
<td class="px-4 py-2 text-xs">{{ $o->kurir }} {{ $o->layanan }} @if($o->resi)<br>Resi {{ $o->resi }}@endif</td>
<td class="px-4 py-2 text-xs">@foreach($o->items as $it)<div class="flex items-center gap-1.5">@if($it->product?->foto)<img src="/storage/{{ $it->product->foto }}" alt="{{ $it->product->nama }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif<span>{{ $it->product->nama ?? '' }} <b>({{ $it->variant?->label() }})</b> ×{{ $it->qty }}</span></div>@endforeach</td>
<td class="px-4 py-2"><span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $o->status==='selesai'?'bg-emerald-400':($o->status==='batal'?'bg-red-400 text-white':'bg-cyan-300') }}">{{ strtoupper($o->status) }}</span></td>
<td class="px-4 py-2 text-right font-extrabold whitespace-nowrap">Rp {{ number_format($o->total_bayar) }}</td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3">
@foreach($orders as $o)
<a href="/pesanan/{{ $o->id }}" class="block bg-white rounded-3xl shadow-card p-4 mb-2 text-sm" style="text-decoration:none">
<div class="flex justify-between items-center"><b class="text-slate-900">{{ $o->kode }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $o->status==='selesai'?'bg-emerald-400':($o->status==='batal'?'bg-red-400 text-white':'bg-cyan-300') }}">{{ strtoupper($o->status) }}</span></div>
<div class="text-xs text-slate-500 mt-0.5">{{ $o->created_at->format('d M H:i') }} • {{ $o->kurir }} {{ $o->layanan }} @if($o->resi)• Resi {{ $o->resi }}@endif</div>
@foreach($o->items as $it)<div class="text-[12px] text-slate-900 flex items-center gap-1.5">@if($it->product?->foto)<img src="/storage/{{ $it->product->foto }}" alt="{{ $it->product->nama }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif<span>{{ $it->product->nama ?? '' }} <b>({{ $it->variant?->label() }})</b> ×{{ $it->qty }} <span class="text-slate-400">({{ $it->tipe_harga }})</span></span></div>@endforeach
<div class="font-extrabold mt-1 text-slate-900">Rp {{ number_format($o->total_bayar) }} <span class="font-normal text-blue-700">→ Detail & Tracking</span></div>
</a>
@endforeach
</div>
@if($orders->isEmpty())<div class="bg-white rounded-3xl p-8 text-center text-sm shadow-card mt-3"><div class="mt-2">Belum ada pesanan.<br><a href="/" class="font-bold text-blue-700">Mulai belanja →</a></div></div>@endif
@endsection
