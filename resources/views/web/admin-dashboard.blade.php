@extends('layouts.web-admin')
@section('title','Admin')
@section('content')
<div class="rounded-3xl p-6 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(135deg,#050B2E,#7c3aed 60%,#00E5FF)">
<div class="text-[11px] tracking-widest text-cyan-200 font-bold">🤖 COMMAND CENTER</div>
<div class="font-extrabold text-2xl mt-1">Halo Admin 👋</div>
<div class="text-xs opacity-80">Omzet total: <b>Rp {{ number_format($omzet) }}</b> • Order baru: <b>{{ $orderBaru }}</b></div>
<div class="grid gap-2 mt-4 lg:grid-cols-4 text-center text-[12px] font-bold">
<a href="/admin/verifikasi" class="rounded-2xl p-3" style="background:#f0abfc;color:#0f0d2e;text-decoration:none">✅<br>Verifikasi</a>
<a href="/admin/produk" class="bg-white/15 rounded-2xl p-3" style="text-decoration:none;color:#fff">📦<br>Stok</a>
<a href="/admin/order" class="bg-white/15 rounded-2xl p-3" style="text-decoration:none;color:#fff">🧾<br>Order</a>
<a href="/admin/laporan" class="bg-white/15 rounded-2xl p-3" style="text-decoration:none;color:#fff">📊<br>Laporan</a>
</div>
@if($gagalLogin>0)
<a href="/admin/aktivitas" class="block mt-3 rounded-2xl p-3 text-[12px] font-bold text-white" style="background:linear-gradient(90deg,#b45309,#ef4444);text-decoration:none">🛡️ {{ $gagalLogin }} upaya login gagal 24 jam terakhir — cek siapa →</a>
@endif
</div>

{{-- Statistik + progres --}}
<div class="grid gap-4 mt-4 lg:grid-cols-3">
<div class="bg-white rounded-3xl shadow-card p-5 lg:col-span-2">
<div class="flex justify-between items-center">
<b class="text-sm">📅 Progres Hari Ini ({{ now()->format('d M Y') }})</b>
<a href="/admin/laporan" class="text-[11px] font-bold text-blue-700" style="text-decoration:none">Detail →</a>
</div>
<div class="grid grid-cols-3 gap-2 mt-2 text-center">
<div class="rounded-2xl p-3" style="background:linear-gradient(180deg,#EEF4FF,#fff)"><div class="text-[10px] text-slate-500">ORDER</div><div class="font-extrabold text-2xl">{{ $orderHariIni }}</div></div>
<div class="rounded-2xl p-3" style="background:linear-gradient(180deg,#E0F7FF,#fff)"><div class="text-[10px] text-slate-500">PCS</div><div class="font-extrabold text-2xl">{{ $pcsHariIni }}</div></div>
<div class="rounded-2xl p-3" style="background:linear-gradient(180deg,#D1FAE5,#fff)"><div class="text-[10px] text-slate-500">OMZET</div><div class="font-extrabold text-sm mt-1">Rp {{ number_format($omzetHariIni) }}</div></div>
</div>
<div class="mt-2 text-xs flex justify-between"><span>Target harian Rp {{ number_format($targetHarian) }}</span><b>{{ $progressPersen }}%</b></div>
<div class="h-1.5 rounded-full bg-slate-100 overflow-hidden mt-1"><div class="h-full bg-violet-600 rounded-full" style="width:{{ $progressPersen }}%"></div></div>
@if($progressPersen>=100)<div class="text-xs font-bold text-emerald-600 mt-1">🎉 Target tercapai! Gas pertahankan besok.</div>
@else<div class="text-[11px] text-slate-500 mt-1">Kurang Rp {{ number_format(max(0,$targetHarian-$omzetHariIni)) }} lagi ke target.</div>@endif
</div>
<div class="bg-white rounded-3xl shadow-card p-5">
<b class="text-sm">📈 7 Hari Terakhir</b>
<div class="flex items-end gap-1.5 mt-2 h-28">
@foreach($grafik as $g)
@php $h = $maxOmzet>0 ? max(6, round($g['omzet']/$maxOmzet*100)) : 6; @endphp
<div class="flex-1 flex flex-col items-center gap-1">
<span class="text-[9px] font-bold">{{ $g['order'] }} ord</span>
<div class="w-full rounded-t-xl" style="height:{{ $h }}px;background:linear-gradient(180deg,#00E5FF,#7c3aed)" title="Rp {{ number_format($g['omzet']) }}"></div>
<span class="text-[9px] text-slate-500">{{ $g['label'] }}</span>
</div>
@endforeach
</div>
<div class="text-[11px] text-slate-500 mt-1">Bar = omzet diproses/dikirim/selesai. Angka = jumlah order.</div>
</div>
</div>

@if($selisih>0)
<div class="rounded-3xl p-4 mt-4 text-sm text-white shadow-card" style="background:linear-gradient(90deg,#7c2d12,#dc2626)">
⚠️ <b>{{ $selisih }} barang totalnya selisih dari jumlah varian.</b>
<form method="POST" action="/admin/rekonsiliasi" class="mt-2" onsubmit="return confirm('Selaraskan semua total dengan jumlah varian?')">@csrf<button class="bg-white text-red-700 font-extrabold text-xs px-4 py-2 rounded-2xl">🔧 REKONSILIASI SEKARANG</button></form>
</div>
@endif
@if($stokMenipis->count())
<div class="rounded-3xl p-4 mt-4 text-sm text-white shadow-card" style="background:linear-gradient(90deg,#b45309,#ef4444)">⚠️ <b>Stok kritis (≤10):</b>@foreach($stokMenipis as $s)<div>• {{ $s->nama }} — sisa {{ $s->stok }}</div>@endforeach<a href="/admin/produk" class="underline font-bold text-white">Restock sekarang →</a></div>
@endif

{{-- Tabel order terbaru desktop --}}
<div class="bg-white rounded-3xl shadow-card p-5 mt-4">
<div class="flex justify-between items-center"><b class="text-sm">🧾 Order terbaru</b><a href="/admin/order" class="text-[11px] font-bold text-blue-700" style="text-decoration:none">Semua →</a></div>
<div class="hidden lg:block overflow-x-auto mt-2">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-2">Kode</th><th class="py-2">User</th><th class="py-2">Status</th><th class="py-2 text-right">Total</th></tr></thead>
<tbody>
@foreach($orders as $o)<tr class="border-t border-slate-100"><td class="py-2 font-bold"><a href="/admin/order/{{ $o->id }}" class="text-blue-700" style="text-decoration:none">{{ $o->kode }}</a></td><td class="py-2">{{ $o->user->name ?? '' }}</td><td class="py-2"><span class="text-[10px] bg-blue-50 px-2 py-0.5 rounded-full font-bold">{{ $o->status }}</span></td><td class="py-2 text-right font-bold">Rp {{ number_format($o->total_bayar) }}</td></tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">
@foreach($orders as $o)<a href="/admin/order/{{ $o->id }}" class="block py-2 border-b border-blue-50" style="text-decoration:none"><div class="flex justify-between"><b class="text-slate-900">{{ $o->kode }}</b><span class="text-[10px] bg-blue-50 px-2 py-0.5 rounded-full font-bold">{{ $o->status }}</span></div><div class="text-xs text-slate-500">{{ $o->user->name ?? '' }} • Rp {{ number_format($o->total_bayar) }}</div></a>@endforeach
</div>
<div class="text-[11px] text-slate-400 mt-2">Total produk: {{ $totalProduk }} • Sidebar admin aktif di layar lebar.</div>
</div>
@endsection
