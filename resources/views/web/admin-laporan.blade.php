@extends('layouts.web-admin')
@section('title','Laporan')
@section('content')
<div class="flex justify-between items-center flex-wrap gap-2">
<div><h1 class="font-extrabold text-2xl">📊 Laporan Bubee</h1><div class="text-xs text-slate-500">Periode {{ $dari }} → {{ $sampai }} • Diperbarui {{ $generatedAt->format('d M H:i:s') }} • <a href="/admin/laporan?dari={{ $dari }}&sampai={{ $sampai }}" class="font-bold text-blue-700" style="text-decoration:none">↻ Muat ulang</a></div></div>
<div class="flex gap-2">
<form method="GET" action="/admin/laporan" class="flex gap-1.5">
<input type="date" name="dari" value="{{ $dari }}" max="{{ now()->toDateString() }}" class="border rounded-2xl px-3 py-2 text-sm">
<input type="date" name="sampai" value="{{ $sampai }}" max="{{ now()->toDateString() }}" class="border rounded-2xl px-3 py-2 text-sm">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">OK</button>
</form>
<a href="/admin/laporan/export?dari={{ $dari }}&sampai={{ $sampai }}" class="bg-emerald-50 text-emerald-700 font-bold py-2 px-4 rounded-2xl text-xs" style="text-decoration:none">📊 Export CSV</a>
</div>
</div>
<div class="flex gap-2 mt-3 text-sm font-bold">
<a href="/admin/laporan?tab=periode" class="px-5 py-2 rounded-2xl {{ ($tab??'periode')==='periode'?'bg-purple-900 text-white':'bg-white text-slate-600 shadow-card' }}" style="text-decoration:none">📊 Periode</a>
<a href="/admin/laporan?tab=harian" class="px-5 py-2 rounded-2xl {{ ($tab??'periode')==='harian'?'bg-purple-900 text-white':'bg-white text-slate-600 shadow-card' }}" style="text-decoration:none">🧾 Harian</a>
</div>
@if(($tab??'periode')==='harian')
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="bg-white rounded-3xl shadow-card p-5 text-sm">
<form method="GET" action="/admin/laporan" class="flex gap-2">
<input type="hidden" name="tab" value="harian">
<input type="date" name="tanggal" value="{{ $rek['tanggal'] }}" max="{{ now()->toDateString() }}" class="flex-1 border rounded-2xl px-3 py-2 text-sm">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Lihat</button>
</form>
<div class="rounded-3xl p-5 text-white mt-3" style="background:linear-gradient(135deg,#050B2E,#059669)">
<div class="text-xs opacity-70">Rekap {{ \Carbon\Carbon::parse($rek['tanggal'])->format('d M Y') }}</div>
<div class="font-extrabold text-3xl">Rp {{ number_format($rek['omzet']) }}</div>
<div class="text-xs opacity-80 mt-1">{{ $rek['jml_order'] }} order • {{ $rek['pcs'] }} pcs</div>
</div>
<div class="mt-2"><b>Per kurir</b>
@foreach($rek['per_kurir'] as $k=>$v)<div class="flex justify-between text-xs py-1 border-b border-blue-50"><span>{{ $k }} ({{ $v['jml'] }})</span><b>Rp {{ number_format($v['total']) }}</b></div>@endforeach
</div>
<div class="mt-2"><b>Per status</b>
<div class="text-xs">@foreach($rek['per_status'] as $s=>$j)<span class="inline-block bg-slate-100 rounded-full px-2 py-0.5 mr-1 mb-1">{{ $s }}: {{ $j }}</span>@endforeach</div>
</div>
<div class="flex gap-2 mt-3">
<button onclick="window.print()" class="flex-1 bg-slate-900 text-white font-bold py-3 rounded-3xl text-sm">🖨️ CETAK</button>
<a href="/admin/laporan/export?dari={{ $rek['tanggal'] }}&sampai={{ $rek['tanggal'] }}" class="flex-1 text-center bg-emerald-50 text-emerald-700 font-bold py-3 rounded-3xl text-sm" style="text-decoration:none">📊 CSV</a>
</div>
</div>
<div class="lg:col-span-2 bg-white rounded-3xl shadow-card p-5 text-sm">
<b>Order tanggal ini ({{ $rekOrders->count() }})</b>
<div class="hidden lg:block overflow-x-auto mt-2">
<table class="w-full text-xs lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Kode</th><th class="py-1">Pembeli</th><th class="py-1">Status</th><th class="py-1 text-right">Total</th></tr></thead>
<tbody>@foreach($rekOrders as $o)<tr class="border-t border-blue-50"><td class="py-1 font-bold">{{ $o->kode }}</td><td class="py-1">{{ $o->user->name ?? '' }}</td><td class="py-1">{{ $o->status }}</td><td class="py-1 text-right font-bold">Rp {{ number_format($o->total_bayar) }}</td></tr>@endforeach</tbody>
</table>
</div>
<div class="lg:hidden mt-1">@foreach($rekOrders as $o)<div class="text-xs py-1 border-b border-blue-50">{{ $o->kode }} • {{ $o->user->name ?? '' }} • Rp {{ number_format($o->total_bayar) }} • {{ $o->status }}</div>@endforeach</div>
@if($rekOrders->isEmpty())<div class="text-xs text-slate-400 mt-1">Tidak ada order tanggal ini.</div>@endif
</div>
</div>
@else
<div class="grid gap-4 mt-3 lg:grid-cols-4">
<div class="rounded-3xl p-6 text-white shadow-card lg:col-span-2" style="background:linear-gradient(135deg,#050B2E,#059669)"><div class="text-xs opacity-70">Omzet lunas</div><div class="font-extrabold text-3xl">Rp {{ number_format($ringkas['omzet']) }}</div><div class="text-xs opacity-80 mt-1">{{ $ringkas['jml_order'] }} order • {{ $ringkas['pcs'] }} pcs • AOV Rp {{ number_format($ringkas['aov']) }}</div></div>
<div class="bg-white rounded-3xl shadow-card p-5 text-sm"><div class="text-[11px] text-slate-400">DISKON VOUCHER</div><div class="font-extrabold text-xl text-emerald-600">-Rp {{ number_format($ringkas['diskon']) }}</div><div class="text-[11px] text-slate-400 mt-2">RETUR AKTIF</div><div class="font-extrabold text-xl text-amber-600">{{ $ringkas['retur_aktif'] }}</div></div>
<div class="bg-white rounded-3xl shadow-card p-5 text-sm"><div class="text-[11px] text-slate-400">BATAL ({{ $ringkas['batal_jml'] }})</div><div class="font-extrabold text-xl text-red-500">Rp {{ number_format($ringkas['batal_nominal']) }}</div><div class="text-[11px] text-slate-400 mt-2">NUNGGU BAYAR ({{ $ringkas['tunggu_jml'] }})</div><div class="font-extrabold text-xl">Rp {{ number_format($ringkas['tunggu_nominal']) }}</div></div>
</div>
<div class="grid gap-4 mt-4 lg:grid-cols-2 lg:items-start">
<div class="bg-white rounded-3xl shadow-card p-5 text-sm"><b>🧾 Per Status</b>
<div class="hidden lg:block overflow-x-auto mt-2">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Status</th><th class="py-1 text-center">Order</th><th class="py-1 text-right">Total</th></tr></thead>
<tbody>@foreach($perStatus as $s)<tr class="border-t border-slate-100"><td class="py-1 font-bold">{{ $s->status }}</td><td class="py-1 text-center">{{ $s->jml }}</td><td class="py-1 text-right font-bold">Rp {{ number_format($s->total) }}</td></tr>@endforeach</tbody>
</table>
</div>
<div class="lg:hidden">@foreach($perStatus as $s)<div class="flex justify-between py-1 border-b"><span>{{ $s->status }} ({{ $s->jml }})</span><b>Rp {{ number_format($s->total) }}</b></div>@endforeach</div>
</div>
<div class="bg-white rounded-3xl shadow-card p-5 text-sm"><b>🚚 Per Kurir (lunas)</b>
<div class="text-[11px] text-slate-400 font-normal">Periode {{ $dari }} → {{ $sampai }}</div>
@php $totKurir = max(1, $perKurir->sum('total')); @endphp
<div class="hidden lg:block overflow-x-auto mt-2">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Kurir</th><th class="py-1 text-center">Order</th><th class="py-1">Share</th><th class="py-1 text-right">Total</th></tr></thead>
<tbody>@foreach($perKurir as $k)<tr class="border-t border-slate-100"><td class="py-1 font-bold">{{ $k->kurir }}</td><td class="py-1 text-center">{{ $k->jml }}</td><td class="py-1"><div class="h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-blue-700 rounded-full" style="width:{{ round($k->total/$totKurir*100) }}%"></div></div></td><td class="py-1 text-right font-bold">Rp {{ number_format($k->total) }}</td></tr>@endforeach</tbody>
</table>
</div>
<div class="lg:hidden">@foreach($perKurir as $k)<div class="py-1 border-b"><div class="flex justify-between"><span>{{ $k->kurir }} ({{ $k->jml }})</span><b>Rp {{ number_format($k->total) }}</b></div></div>@endforeach</div>
</div>
</div>
<div class="grid gap-4 mt-4 lg:grid-cols-2 lg:items-start">
<div class="bg-white rounded-3xl shadow-card p-5 text-sm"><b>🔥 Terlaris Top 10</b>
<div class="text-[11px] text-slate-400 font-normal">Periode {{ $dari }} → {{ $sampai }}</div>
<div class="hidden lg:block overflow-x-auto mt-2">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Barang</th><th class="py-1 text-center">Pcs</th><th class="py-1 text-right">Omzet</th></tr></thead>
<tbody>@foreach($terlaris as $t)<tr class="border-t border-slate-100"><td class="py-1 font-bold"><span class="inline-flex items-center gap-1.5 align-middle">@if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif<span>{{ $t->product->nama ?? '?' }}</span></span></td><td class="py-1 text-center">{{ $t->terjual }}</td><td class="py-1 text-right font-bold">Rp {{ number_format($t->omzet) }}</td></tr>@endforeach</tbody>
</table>
</div>
<div class="lg:hidden">@foreach($terlaris as $t)<div class="py-1 border-b"><span class="inline-flex items-center gap-1.5 align-middle">@if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif<span>{{ $t->product->nama ?? '?' }}</span></span> — <b>{{ $t->terjual }} pcs</b> • Rp {{ number_format($t->omzet) }}</div>@endforeach</div>
</div>
<div class="space-y-4">
<div class="bg-white rounded-3xl shadow-card p-5 text-sm"><b>👑 Top Pembeli</b>
@foreach($topPembeli as $t)<div class="flex justify-between py-1 border-b"><span>{{ $t->user->name ?? '?' }} ({{ $t->jml }} order)</span><b>Rp {{ number_format($t->total) }}</b></div>@endforeach
</div>
<div class="bg-white rounded-3xl shadow-card p-5 text-sm"><b>🎟️ Voucher Terpakai</b>
@foreach($topVoucher as $v)<div class="flex justify-between py-1 border-b"><span>{{ $v->voucher->kode ?? '?' }} ({{ $v->dipakai }}x)</span><b>-Rp {{ number_format($v->hemat) }}</b></div>@endforeach
</div>
</div>
</div>
<div class="bg-white rounded-3xl shadow-card p-5 mt-4 text-sm"><details open><summary class="font-extrabold cursor-pointer">📅 Harian <span class="text-[11px] font-normal text-slate-400">(klik untuk sembunyikan)</span></summary>
<div class="hidden lg:block overflow-x-auto mt-2">
<table class="w-full text-xs lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Tanggal</th><th class="py-1 text-center">Order</th><th class="py-1 text-center">Pcs</th><th class="py-1">Grafik</th><th class="py-1 text-right">Omzet</th></tr></thead>
<tbody>@foreach($harian as $h)<tr class="border-t border-blue-50"><td class="py-1 font-bold">{{ $h['tgl'] }}</td><td class="py-1 text-center">{{ $h['order'] }}</td><td class="py-1 text-center">{{ $h['pcs'] }}</td><td class="py-1"><div class="h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-blue-700 rounded-full" style="width:{{ $maxOmzet>0?round($h['omzet']/$maxOmzet*100):0 }}%"></div></div></td><td class="py-1 text-right font-bold">Rp {{ number_format($h['omzet']) }}</td></tr>@endforeach</tbody>
</table>
</div>
<div class="lg:hidden grid grid-cols-4 text-[10px] font-bold text-slate-400 mt-2 pb-1 border-b"><span>Tanggal</span><span class="text-center">Order</span><span class="text-center">Pcs</span><span class="text-right">Omzet</span></div>
<div class="lg:hidden">@foreach($harian as $h)<div class="grid grid-cols-4 text-xs py-1.5 border-b border-blue-50"><span class="font-bold">{{ $h['tgl'] }}</span><span class="text-center">{{ $h['order'] }}</span><span class="text-center">{{ $h['pcs'] }}</span><span class="text-right font-bold">Rp {{ number_format($h['omzet']) }}</span></div>@endforeach</div>
</details></div>
<div class="bg-white rounded-3xl shadow-card p-5 mt-4 text-sm"><details><summary class="font-extrabold cursor-pointer">📦 Mutasi Stok Terakhir <span class="text-[11px] font-normal text-slate-400">(klik untuk buka)</span></summary>@foreach($mutasi as $m)<div class="text-xs py-1 border-b">{{ $m->created_at->format('d/m H:i') }} • {{ $m->product->nama ?? '' }} • {{ $m->tipe }} {{ $m->qty }} ({{ $m->stok_sebelum }}→{{ $m->stok_sesudah }})<br><span class="text-slate-400">{{ $m->keterangan }}</span></div>@endforeach</details></div>
@endif
@endsection
