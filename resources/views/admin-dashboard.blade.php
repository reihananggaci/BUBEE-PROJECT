@extends('layouts.admin')
@section('title','Admin')
@section('content')
<div class="rounded-[2rem] p-5 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(135deg,#050B2E,#7c3aed 60%,#00E5FF)">
<div class="flex items-center gap-1.5 text-[11px] tracking-widest text-cyan-200 font-bold uppercase">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/><circle cx="12" cy="12" r="3"/></svg>
    <span>COMMAND CENTER</span>
</div>
<div class="flex items-center gap-3 mt-1">
<div class="flex-1 min-w-0">
<div class="font-extrabold text-xl">Halo Admin</div>
<div class="text-xs opacity-80 mt-0.5">Omzet total: <b>Rp {{ number_format($omzet) }}</b> • Order baru: <b>{{ $orderBaru }}</b></div>
</div>
<div class="hexbee">🐝</div>
</div>
<div class="grid grid-cols-3 gap-1.5 mt-3 text-center text-[11px] font-bold">
<a href="/admin/verifikasi" class="rounded-2xl p-2.5 flex flex-col items-center justify-center gap-1" style="background:#f0abfc;color:#0f0d2e">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 2.8v5.4c0 4.6-3 7.6-7 9.3-4-1.7-7-4.7-7-9.3V5.8L12 3z"/><path d="M9.3 11.8l2 2 3.6-4"/></svg>
    <span>Verifikasi</span>
</a>
<a href="/admin/produk" class="bg-white/15 rounded-2xl p-2.5 backdrop-blur flex flex-col items-center justify-center gap-1 text-white">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg>
    <span>Stok</span>
</a>
<a href="/admin/penerimaan" class="bg-white/15 rounded-2xl p-2.5 backdrop-blur flex flex-col items-center justify-center gap-1 text-white">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    <span>Masuk</span>
</a>
<a href="/admin/order" class="bg-white/15 rounded-2xl p-2.5 backdrop-blur flex flex-col items-center justify-center gap-1 text-white">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12V21l-3-2-3 2-3-2-3 2v-17.5z"/><path d="M9 8.5h6M9 12h6"/></svg>
    <span>Order</span>
</a>
<a href="/admin/laporan" class="bg-white/15 rounded-2xl p-2.5 backdrop-blur flex flex-col items-center justify-center gap-1 text-white">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 20v-7"/><path d="M11 20V5"/><path d="M17 20v-10"/><path d="M3 20h18"/></svg>
    <span>Laporan</span>
</a>
<a href="/admin/reseller" class="bg-white/15 rounded-2xl p-2.5 backdrop-blur flex flex-col items-center justify-center gap-1 text-white">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
    <span>Reseller</span>
</a>
<a href="/admin/users" class="bg-white/15 rounded-2xl p-2.5 backdrop-blur flex flex-col items-center justify-center gap-1 text-white">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8.5" r="3"/><path d="M2.8 19.5c1.2-3 3.4-4.5 6.2-4.5s5 1.5 6.2 4.5"/><circle cx="16.8" cy="9.5" r="2.4"/><path d="M16 15c2.3.2 4 1.5 5 4"/></svg>
    <span>Users</span>
</a>
<a href="/admin/mutasi" class="bg-white/15 rounded-2xl p-2.5 backdrop-blur flex flex-col items-center justify-center gap-1 text-white">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h13"/><path d="M14 3.5 17.5 7 14 10.5"/><path d="M20 17H7"/><path d="M10 13.5 6.5 17 10 20.5"/></svg>
    <span>Mutasi</span>
</a>
<a href="/admin/pengaturan" class="bg-white/15 rounded-2xl p-2.5 backdrop-blur flex flex-col items-center justify-center gap-1 text-white">
    <svg class="w-4 h-4 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h9"/><circle cx="16.5" cy="7" r="2"/><path d="M20 17H9"/><circle cx="6" cy="17" r="2"/></svg>
    <span>Setting</span>
</a>
</div>
<div class="mt-2 rounded-2xl p-2.5 text-[11px] font-bold flex items-center gap-1.5" style="background:rgba(240,171,252,.2);border:1px dashed rgba(240,171,252,.6)">
    <svg class="w-3.5 h-3.5 shrink-0 text-fuchsia-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    <span>Zona khusus admin — reseller & akun reguler tidak bisa buka halaman ini.</span>
</div>
@if($gagalLogin>0)
<a href="/admin/aktivitas" class="flex items-center gap-1.5 mt-2 rounded-2xl p-2.5 text-[11px] font-bold text-white" style="background:linear-gradient(90deg,#b45309,#ef4444);text-decoration:none">
    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    <span>{{ $gagalLogin }} upaya login gagal 24 jam terakhir — cek siapa →</span>
</a>
@endif
</div>

{{-- PROGRES HARIAN --}}
<div class="glass rounded-[1.75rem] shadow-card p-4 mt-3">
<div class="flex justify-between items-center">
<b class="text-sm text-slate-900 flex items-center gap-1.5">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
    <span>Progres Hari Ini ({{ now()->format('d M Y') }})</span>
</b>
<a href="/admin/laporan" class="text-[11px] font-bold text-blue-700">Detail →</a>
</div>
<div class="grid grid-cols-3 gap-2 mt-2 text-center">
<div class="rounded-2xl p-2.5" style="background:linear-gradient(180deg,#EEF4FF,#fff)"><div class="text-[10px] text-slate-500">ORDER</div><div class="font-extrabold text-lg">{{ $orderHariIni }}</div></div>
<div class="rounded-2xl p-2.5" style="background:linear-gradient(180deg,#E0F7FF,#fff)"><div class="text-[10px] text-slate-500">PCS</div><div class="font-extrabold text-lg">{{ $pcsHariIni }}</div></div>
<div class="rounded-2xl p-2.5" style="background:linear-gradient(180deg,#D1FAE5,#fff)"><div class="text-[10px] text-slate-500">OMZET</div><div class="font-extrabold text-[13px] mt-1">Rp {{ number_format($omzetHariIni) }}</div></div>
</div>
<div class="mt-2 text-xs flex justify-between"><span>Target harian Rp {{ number_format($targetHarian) }}</span><b>{{ $progressPersen }}%</b></div>
<div class="progress-line mt-1"><div style="width:{{ $progressPersen }}%"></div></div>
@if($progressPersen>=100)<div class="text-xs font-bold text-emerald-600 mt-1">✓ Target tercapai! Gas pertahankan besok.</div>
@else<div class="text-[11px] text-slate-500 mt-1">Kurang Rp {{ number_format(max(0,$targetHarian-$omzetHariIni)) }} lagi ke target.</div>@endif
</div>

{{-- GRAFIK 7 HARI --}}
<div class="glass rounded-[1.75rem] shadow-card p-4 mt-3">
<b class="text-sm text-slate-900 flex items-center gap-1.5">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
    <span>Tren 7 Hari Terakhir</span>
</b>
<div class="flex items-end gap-1.5 mt-2 h-28">
@foreach($grafik as $g)
@php $h = $maxOmzet>0 ? max(6, round($g['omzet']/$maxOmzet*100)) : 6; @endphp
<div class="flex-1 flex flex-col items-center gap-1">
<span class="text-[9px] font-bold">{{ $g['order'] }} ord</span>
<div class="w-full rounded-t-xl" style="height:{{ $h }}px;background:linear-gradient(180deg,#00E5FF,#1D4ED8)" title="Rp {{ number_format($g['omzet']) }}"></div>
<span class="text-[9px] text-slate-500">{{ $g['label'] }}</span>
</div>
@endforeach
</div>
<div class="text-[11px] text-slate-500 mt-1">Bar = omzet diproses/dikirim/selesai. Angka = jumlah order.</div>
</div>

@if($selisih>0)
<div class="rounded-3xl p-4 mt-3 text-sm text-white shadow-card" style="background:linear-gradient(90deg,#7c2d12,#dc2626)">
<div class="flex items-center gap-2">
    <svg class="w-5 h-5 shrink-0 text-amber-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
    <b>{{ $selisih }} barang totalnya selisih dari jumlah varian.</b>
</div>
<form method="POST" action="/admin/rekonsiliasi" class="mt-2" onsubmit="return confirm('Selaraskan semua total dengan jumlah varian?')">@csrf<button class="bg-white text-red-700 font-extrabold text-xs px-4 py-2 rounded-2xl">🔧 REKONSILIASI SEKARANG</button></form>
</div>
@endif
@if($stokMenipis->count())
<div class="rounded-3xl p-4 mt-3 text-sm text-white shadow-card" style="background:linear-gradient(90deg,#b45309,#ef4444)">
<div class="flex items-center gap-2 mb-1">
    <svg class="w-4 h-4 shrink-0 text-amber-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
    <b>Stok kritis (≤10):</b>
</div>
@foreach($stokMenipis as $s)<div>• {{ $s->nama }} — sisa {{ $s->stok }}</div>@endforeach
<a href="/admin/produk" class="underline font-bold text-white mt-1 block">Restock sekarang →</a>
</div>
@endif
<div class="glass rounded-3xl shadow-card p-4 mt-3 text-sm">
<b class="text-slate-900 flex items-center gap-1.5 mb-2">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12V21l-3-2-3 2-3-2-3 2v-17.5z"/><path d="M9 8.5h6M9 12h6"/></svg>
    <span>Order terbaru</span>
</b>
@foreach($orders as $o)<a href="/admin/order/{{ $o->id }}" class="block py-2 border-b border-blue-50"><div class="flex justify-between"><b>{{ $o->kode }}</b><span class="text-[10px] bg-blue-50 px-2 py-0.5 rounded-full font-bold">{{ $o->status }}</span></div><div class="text-xs text-slate-500">{{ $o->user->name ?? '' }} • Rp {{ number_format($o->total_bayar) }}</div></a>@endforeach
</div>
@endsection
