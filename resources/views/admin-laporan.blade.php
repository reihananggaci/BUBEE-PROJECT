@extends('layouts.admin')
@section('title','Laporan')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">📊 Laporan Bubee</h1>
<div class="text-[11px] text-slate-400">Diperbarui {{ $generatedAt->format('d M H:i:s') }} • <a href="/admin/laporan?dari={{ $dari }}&sampai={{ $sampai }}" class="font-bold text-blue-700">↻ Muat ulang</a></div>
<div class="flex gap-1.5 mt-2 text-[12px] font-bold">
<a href="/admin/laporan?tab=periode" class="flex-1 text-center px-3 py-2 rounded-2xl {{ ($tab??'periode')==='periode'?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">📊 Periode</a>
<a href="/admin/laporan?tab=harian" class="flex-1 text-center px-3 py-2 rounded-2xl {{ ($tab??'periode')==='harian'?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">🧾 Harian</a>
</div>
@if(($tab??'periode')==='harian')
<form method="GET" action="/admin/laporan" class="flex gap-1.5 mt-2">
<input type="hidden" name="tab" value="harian">
<input type="date" name="tanggal" value="{{ $rek['tanggal'] }}" max="{{ now()->toDateString() }}" class="flex-1 border rounded-2xl px-2 py-2 text-xs">
<button class="bg-purple-900 text-white font-bold px-3 rounded-2xl text-xs">Lihat</button>
</form>
<div class="rounded-3xl p-5 text-white mt-2 shadow-card" style="background:linear-gradient(135deg,#050B2E,#059669)">
<div class="text-xs opacity-70">Rekap {{ \Carbon\Carbon::parse($rek['tanggal'])->format('d M Y') }}</div>
<div class="font-extrabold text-2xl">Rp {{ number_format($rek['omzet']) }}</div>
<div class="text-[11px] opacity-80 mt-1">{{ $rek['jml_order'] }} order • {{ $rek['pcs'] }} pcs</div>
</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>🚚 Per kurir</b>
@foreach($rek['per_kurir'] as $k=>$v)<div class="flex justify-between text-xs py-1 border-b border-blue-50"><span>{{ $k }} ({{ $v['jml'] }})</span><b>Rp {{ number_format($v['total']) }}</b></div>@endforeach
</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>Per status</b>
<div class="text-xs">@foreach($rek['per_status'] as $s=>$j)<span class="inline-block bg-slate-100 rounded-full px-2 py-0.5 mr-1 mb-1">{{ $s }}: {{ $j }}</span>@endforeach</div>
</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>Order</b>
@foreach($rekOrders as $o)<div class="text-xs py-1 border-b border-blue-50">{{ $o->kode }} • {{ $o->user->name ?? '' }} • Rp {{ number_format($o->total_bayar) }} • {{ $o->status }}</div>@endforeach
@if($rekOrders->isEmpty())<div class="text-xs text-slate-400">Tidak ada order tanggal ini.</div>@endif
</div>
<div class="flex gap-2 mt-2">
<button onclick="window.print()" class="flex-1 bg-slate-900 text-white font-bold py-3 rounded-3xl text-sm">🖨️ CETAK</button>
<a href="/admin/laporan/export?dari={{ $rek['tanggal'] }}&sampai={{ $rek['tanggal'] }}" class="flex-1 text-center bg-emerald-50 text-emerald-700 font-bold py-3 rounded-3xl text-sm" style="text-decoration:none">📊 CSV</a>
</div>
@else
<form method="GET" action="/admin/laporan" class="flex gap-1.5 mt-2">
<input type="date" name="dari" value="{{ $dari }}" max="{{ now()->toDateString() }}" class="flex-1 border rounded-2xl px-2 py-2 text-xs">
<input type="date" name="sampai" value="{{ $sampai }}" max="{{ now()->toDateString() }}" class="flex-1 border rounded-2xl px-2 py-2 text-xs">
<button class="bg-purple-900 text-white font-bold px-3 rounded-2xl text-xs">OK</button>
</form>
<a href="/admin/laporan/export?dari={{ $dari }}&sampai={{ $sampai }}" class="block text-center mt-1 mb-2 bg-emerald-50 text-emerald-700 font-bold py-2.5 rounded-2xl text-xs">📊 Export CSV ({{ $dari }} → {{ $sampai }})</a>
<div class="rounded-3xl p-5 text-white mt-2 shadow-card" style="background:linear-gradient(135deg,#050B2E,#059669)">
<div class="text-xs opacity-70">Omzet lunas ({{ $dari }} → {{ $sampai }})</div>
<div class="font-extrabold text-2xl">Rp {{ number_format($ringkas['omzet']) }}</div>
<div class="text-[11px] opacity-80 mt-1">{{ $ringkas['jml_order'] }} order • {{ $ringkas['pcs'] }} pcs • rata-rata Rp {{ number_format($ringkas['aov']) }}/order</div>
</div>
<div class="grid grid-cols-2 gap-2 mt-2 text-center text-sm">
<div class="glass rounded-3xl p-3 shadow-card"><div class="text-[10px] text-slate-500">DISKON VOUCHER</div><div class="font-extrabold text-emerald-600">-Rp {{ number_format($ringkas['diskon']) }}</div></div>
<div class="glass rounded-3xl p-3 shadow-card"><div class="text-[10px] text-slate-500">RETUR AKTIF</div><div class="font-extrabold text-amber-600">{{ $ringkas['retur_aktif'] }}</div></div>
<div class="glass rounded-3xl p-3 shadow-card"><div class="text-[10px] text-slate-500">BATAL ({{ $ringkas['batal_jml'] }})</div><div class="font-extrabold text-red-500">Rp {{ number_format($ringkas['batal_nominal']) }}</div></div>
<div class="glass rounded-3xl p-3 shadow-card"><div class="text-[10px] text-slate-500">NUNGGU BAYAR ({{ $ringkas['tunggu_jml'] }})</div><div class="font-extrabold">Rp {{ number_format($ringkas['tunggu_nominal']) }}</div></div>
</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>🧾 Per Status</b>@foreach($perStatus as $s)<div class="flex justify-between py-1 border-b"><span>{{ $s->status }} ({{ $s->jml }})</span><b>Rp {{ number_format($s->total) }}</b></div>@endforeach</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>🚚 Per Kurir (lunas)</b>
<div class="text-[11px] text-slate-400">Periode {{ $dari }} → {{ $sampai }}</div>
@php $totKurir = max(1, $perKurir->sum('total')); @endphp
@foreach($perKurir as $k)<div class="py-1 border-b"><div class="flex justify-between"><span>{{ $k->kurir }} ({{ $k->jml }})</span><b>Rp {{ number_format($k->total) }}</b></div><div class="progress-line mt-1"><div style="width:{{ round($k->total/$totKurir*100) }}%"></div></div></div>@endforeach</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>🔥 Terlaris Top 10</b>
<div class="text-[11px] text-slate-400">Periode {{ $dari }} → {{ $sampai }}</div>@foreach($terlaris as $t)<div class="py-1 border-b"><span class="inline-flex items-center gap-1.5 align-middle">@if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif<span>{{ $t->product->nama ?? '?' }}</span></span> — <b>{{ $t->terjual }} pcs</b> • Rp {{ number_format($t->omzet) }}<div class="progress-line mt-1"><div style="width:{{ min(100,$t->terjual*8) }}%"></div></div></div>@endforeach</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>👑 Top Pembeli</b>@foreach($topPembeli as $t)<div class="flex justify-between py-1 border-b"><span>{{ $t->user->name ?? '?' }} ({{ $t->jml }} order)</span><b>Rp {{ number_format($t->total) }}</b></div>@endforeach</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>🎟️ Voucher Terpakai</b>@foreach($topVoucher as $v)<div class="flex justify-between py-1 border-b"><span>{{ $v->voucher->kode ?? '?' }} ({{ $v->dipakai }}x)</span><b>-Rp {{ number_format($v->hemat) }}</b></div>@endforeach</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>📅 Harian</b>
@foreach($harian as $h)<div class="py-1 border-b"><div class="flex justify-between text-xs"><b>{{ $h['tgl'] }}</b><span>{{ $h['order'] }} ord • {{ $h['pcs'] }} pcs</span><b>Rp {{ number_format($h['omzet']) }}</b></div><div class="progress-line mt-1"><div style="width:{{ $maxOmzet>0?round($h['omzet']/$maxOmzet*100):0 }}%"></div></div></div>@endforeach</div>
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card"><b>📦 Mutasi Stok Terakhir</b>@foreach($mutasi as $m)<div class="text-xs py-1 border-b">{{ $m->created_at->format('d/m H:i') }} • {{ $m->product->nama ?? '' }} • {{ $m->tipe }} {{ $m->qty }} ({{ $m->stok_sebelum }}→{{ $m->stok_sesudah }})<br><span class="text-slate-400">{{ $m->keterangan }}</span></div>@endforeach</div>
@endif
@endsection
