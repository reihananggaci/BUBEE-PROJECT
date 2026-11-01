@extends('layouts.web-admin')
@section('title','Admin Order')
@section('content')
<div class="flex justify-between items-center">
<div><h1 class="font-extrabold text-2xl">Kelola Order</h1><div class="text-xs text-slate-500">{{ $orders->count() }} order • ubah status + isi resi langsung dari tabel</div></div>
<div class="flex gap-2">
<form method="POST" action="/admin/order/bersihkan-kedaluwarsa" onsubmit="return confirm('Batalkan pesanan yang belum dibayar > 24 jam dan kembalikan stok?')">@csrf<button class="bg-rose-50 text-rose-700 font-bold py-2.5 px-4 rounded-2xl text-xs">⏳ Auto-Batal 24 Jam</button></form>
<a href="/admin/order/export" class="bg-emerald-50 text-emerald-700 font-bold py-2.5 px-5 rounded-2xl text-xs" style="text-decoration:none">📊 Export CSV</a>
</div>
</div>
@if($orders->isEmpty())<div class="bg-white rounded-3xl p-8 text-center text-sm mt-4">Belum ada order.</div>@endif
<div class="hidden lg:block bg-white rounded-3xl shadow-card mt-4 overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Kode</th><th class="px-4 py-2">Pembeli</th><th class="px-4 py-2">Kurir</th><th class="px-4 py-2 text-right">Total</th><th class="px-4 py-2">Status + Resi</th></tr></thead>
<tbody>
@foreach($orders as $o)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold whitespace-nowrap"><a href="/admin/order/{{ $o->id }}" class="text-blue-700" style="text-decoration:none">{{ $o->kode }}</a></td>
<td class="px-4 py-2">{{ $o->user->name ?? '' }}</td>
<td class="px-4 py-2 text-xs">{{ $o->kurir }} {{ $o->layanan }}</td>
<td class="px-4 py-2 text-right font-bold whitespace-nowrap">Rp {{ number_format($o->total_bayar) }}</td>
<td class="px-4 py-2"><form method="POST" action="/admin/order/{{ $o->id }}" class="flex gap-1">@csrf @method('PUT')
<select name="status" class="border rounded-xl px-2 py-1 text-xs font-bold">@foreach(['menunggu_bayar','diproses','dikirim','selesai','batal'] as $s)<option value="{{ $s }}" {{ $o->status===$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
<input name="resi" value="{{ $o->resi }}" placeholder="No resi" class="border rounded-xl px-2 py-1 text-xs w-28">
<button class="bg-blue-900 text-white text-xs font-bold px-3 rounded-xl">OK</button>
</form></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3">
@foreach($orders as $o)
<div class="bg-white rounded-3xl shadow-card p-4 mb-2 text-sm">
<a href="/admin/order/{{ $o->id }}" class="flex justify-between" style="text-decoration:none"><b class="text-slate-900">{{ $o->kode }}</b><span class="text-blue-700 font-bold text-xs">Detail →</span></a>
<div class="text-xs text-slate-500">{{ $o->user->name ?? '' }} • {{ $o->kurir }} {{ $o->layanan }} • Rp {{ number_format($o->total_bayar) }}</div>
<form method="POST" action="/admin/order/{{ $o->id }}" class="flex gap-1 mt-2">@csrf @method('PUT')
<select name="status" class="border rounded-xl px-2 py-1.5 text-xs font-bold">@foreach(['menunggu_bayar','diproses','dikirim','selesai','batal'] as $s)<option value="{{ $s }}" {{ $o->status===$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
<input name="resi" value="{{ $o->resi }}" placeholder="No resi" class="flex-1 border rounded-xl px-2 py-1.5 text-xs">
<button class="bg-blue-900 text-white text-xs font-bold px-3 rounded-xl">OK</button>
</form>
</div>
@endforeach
</div>
@endsection
