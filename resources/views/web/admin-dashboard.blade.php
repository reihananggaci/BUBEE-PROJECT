@extends('layouts.web-admin')
@section('title','Admin')
@section('content')

{{-- BANNER UTAMA COMMAND CENTER --}}
<div class="rounded-3xl p-6 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(135deg,#050B2E,#7c3aed 60%,#00E5FF)">
    <div class="flex items-center gap-1.5 text-[11px] tracking-widest text-cyan-200 font-extrabold uppercase">
        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/><circle cx="12" cy="12" r="3"/></svg>
        <span>COMMAND CENTER</span>
    </div>
    
    <div class="font-extrabold text-2xl lg:text-3xl mt-1 tracking-tight">Halo Admin</div>
    <div class="text-xs text-white/80 mt-1">
        Omzet total: <b class="text-white">Rp {{ number_format($omzet) }}</b> • Order baru: <b class="text-cyan-200">{{ $orderBaru }}</b>
    </div>
    
    {{-- 4 TOMBOL AKSI CEPAT DENGAN ICON VECTOR CLEAN --}}
    <div class="grid gap-2.5 mt-5 grid-cols-2 lg:grid-cols-4 text-center text-xs font-bold">
        <a href="/admin/verifikasi" class="flex items-center justify-center gap-2 rounded-2xl p-3.5 transition active:scale-95 shadow-sm hover:opacity-95" style="background:#f0abfc;color:#0f0d2e;text-decoration:none">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 2.8v5.4c0 4.6-3 7.6-7 9.3-4-1.7-7-4.7-7-9.3V5.8L12 3z"/><path d="M9.3 11.8l2 2 3.6-4"/></svg>
            <span>Verifikasi</span>
        </a>
        <a href="/admin/produk" class="flex items-center justify-center gap-2 rounded-2xl p-3.5 bg-white/15 hover:bg-white/20 transition active:scale-95 backdrop-blur-sm border border-white/20" style="text-decoration:none;color:#fff">
            <svg class="w-4 h-4 shrink-0 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg>
            <span>Stok</span>
        </a>
        <a href="/admin/order" class="flex items-center justify-center gap-2 rounded-2xl p-3.5 bg-white/15 hover:bg-white/20 transition active:scale-95 backdrop-blur-sm border border-white/20" style="text-decoration:none;color:#fff">
            <svg class="w-4 h-4 shrink-0 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12V21l-3-2-3 2-3-2-3 2v-17.5z"/><path d="M9 8.5h6M9 12h6"/></svg>
            <span>Order</span>
        </a>
        <a href="/admin/laporan" class="flex items-center justify-center gap-2 rounded-2xl p-3.5 bg-white/15 hover:bg-white/20 transition active:scale-95 backdrop-blur-sm border border-white/20" style="text-decoration:none;color:#fff">
            <svg class="w-4 h-4 shrink-0 text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 20v-7"/><path d="M11 20V5"/><path d="M17 20v-10"/><path d="M3 20h18"/></svg>
            <span>Laporan</span>
        </a>
    </div>

    @if($gagalLogin>0)
    <a href="/admin/aktivitas" class="flex items-center gap-2 mt-3 rounded-2xl p-3 text-xs font-bold text-white transition hover:opacity-95" style="background:linear-gradient(90deg,#b45309,#ef4444);text-decoration:none">
        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <span>{{ $gagalLogin }} upaya login gagal 24 jam terakhir — cek siapa →</span>
    </a>
    @endif
</div>

{{-- STATISTIK + PROGRES HARIAN --}}
<div class="grid gap-4 mt-4 lg:grid-cols-3">
    <div class="bg-white rounded-3xl shadow-card p-5 lg:col-span-2 border border-slate-100">
        <div class="flex justify-between items-center">
            <b class="text-sm text-slate-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                <span>Progres Hari Ini ({{ now()->format('d M Y') }})</span>
            </b>
            <a href="/admin/laporan" class="text-[11px] font-bold text-blue-700 hover:underline" style="text-decoration:none">Detail →</a>
        </div>
        
        <div class="grid grid-cols-3 gap-2 mt-3 text-center">
            <div class="rounded-2xl p-3 border border-blue-50" style="background:linear-gradient(180deg,#EEF4FF,#fff)">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">ORDER</div>
                <div class="font-black text-2xl text-slate-900 mt-0.5">{{ $orderHariIni }}</div>
            </div>
            <div class="rounded-2xl p-3 border border-sky-50" style="background:linear-gradient(180deg,#E0F7FF,#fff)">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">PCS</div>
                <div class="font-black text-2xl text-slate-900 mt-0.5">{{ $pcsHariIni }}</div>
            </div>
            <div class="rounded-2xl p-3 border border-emerald-50" style="background:linear-gradient(180deg,#D1FAE5,#fff)">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">OMZET</div>
                <div class="font-black text-sm mt-1 text-slate-900">Rp {{ number_format($omzetHariIni) }}</div>
            </div>
        </div>
        
        <div class="mt-3 text-xs flex justify-between text-slate-600">
            <span>Target harian Rp {{ number_format($targetHarian) }}</span>
            <b class="text-purple-900 font-extrabold">{{ $progressPersen }}%</b>
        </div>
        
        <div class="h-2 rounded-full bg-slate-100 overflow-hidden mt-1.5">
            <div class="h-full bg-purple-700 rounded-full transition-all duration-500" style="width:{{ $progressPersen }}%"></div>
        </div>
        
        @if($progressPersen>=100)
            <div class="text-xs font-bold text-emerald-600 mt-1.5 flex items-center gap-1">
                <span>✓</span>
                <span>Target harian tercapai! Gas pertahankan besok.</span>
            </div>
        @else
            <div class="text-[11px] text-slate-400 mt-1.5">
                Kurang Rp {{ number_format(max(0,$targetHarian-$omzetHariIni)) }} lagi ke target harian.
            </div>
        @endif
    </div>

    {{-- GRAFIK 7 HARI TERAKHIR --}}
    <div class="bg-white rounded-3xl shadow-card p-5 border border-slate-100">
        <b class="text-sm text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
            <span>Tren 7 Hari Terakhir</span>
        </b>
        
        <div class="flex items-end gap-1.5 mt-3 h-28">
            @foreach($grafik as $g)
            @php $h = $maxOmzet>0 ? max(6, round($g['omzet']/$maxOmzet*100)) : 6; @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-[9px] font-bold text-slate-600">{{ $g['order'] }} ord</span>
                <div class="w-full rounded-t-xl transition-all" style="height:{{ $h }}px;background:linear-gradient(180deg,#00E5FF,#7c3aed)" title="Rp {{ number_format($g['omzet']) }}"></div>
                <span class="text-[9px] text-slate-400 font-medium">{{ $g['label'] }}</span>
            </div>
            @endforeach
        </div>
        <div class="text-[11px] text-slate-400 mt-2">Bar = omzet diproses/dikirim/selesai. Angka = jumlah order.</div>
    </div>
</div>

@if($selisih>0)
<div class="rounded-3xl p-4 mt-4 text-sm text-white shadow-card flex items-center justify-between flex-wrap gap-2" style="background:linear-gradient(90deg,#7c2d12,#dc2626)">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0 text-amber-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
        <span><b>{{ $selisih }} barang totalnya selisih dari jumlah varian.</b></span>
    </div>
    <form method="POST" action="/admin/rekonsiliasi" class="m-0" onsubmit="return confirm('Selaraskan semua total dengan jumlah varian?')">
        @csrf
        <button class="bg-white hover:bg-slate-100 text-red-700 font-black text-xs px-4 py-2 rounded-2xl shadow-sm transition inline-flex items-center gap-1.5 cursor-pointer">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            <span>REKONSILIASI SEKARANG</span>
        </button>
    </form>
</div>
@endif

@if($stokMenipis->count())
<div class="rounded-3xl p-4 mt-4 text-sm text-white shadow-card flex items-start gap-2.5" style="background:linear-gradient(90deg,#b45309,#ef4444)">
    <svg class="w-5 h-5 shrink-0 text-amber-200 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
    <div class="flex-1">
        <b>Stok kritis (≤10):</b>
        <div class="mt-1 space-y-0.5 text-xs text-white/90">
            @foreach($stokMenipis as $s)
                <div>• {{ $s->nama }} — sisa {{ $s->stok }} botol</div>
            @endforeach
        </div>
        <div class="mt-2">
            <a href="/admin/produk" class="underline font-bold text-white hover:text-cyan-200 text-xs">Restock sekarang →</a>
        </div>
    </div>
</div>
@endif

{{-- TABEL ORDER TERBARU --}}
<div class="bg-white rounded-3xl shadow-card p-5 mt-4 border border-slate-100">
    <div class="flex justify-between items-center mb-3">
        <b class="text-sm text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12V21l-3-2-3 2-3-2-3 2v-17.5z"/><path d="M9 8.5h6M9 12h6"/></svg>
            <span>Order Terbaru</span>
        </b>
        <a href="/admin/order" class="text-[11px] font-bold text-blue-700 hover:underline" style="text-decoration:none">Lihat Semua →</a>
    </div>

    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-sm lg:table">
            <thead>
                <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                    <th class="py-2.5 px-3">Kode Order</th>
                    <th class="py-2.5 px-3">Nama Pembeli</th>
                    <th class="py-2.5 px-3 text-center">Status</th>
                    <th class="py-2.5 px-3 text-right">Total Transaksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($orders as $o)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="py-2.5 px-3 font-mono font-bold">
                        <a href="/admin/order/{{ $o->id }}" class="text-blue-700 hover:underline" style="text-decoration:none">{{ $o->kode }}</a>
                    </td>
                    <td class="py-2.5 px-3 text-slate-800 font-medium">{{ $o->user->name ?? 'Pelanggan Tamu' }}</td>
                    <td class="py-2.5 px-3 text-center">
                        <span class="text-[10px] bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full font-extrabold uppercase">{{ $o->status }}</span>
                    </td>
                    <td class="py-2.5 px-3 text-right font-black text-slate-900">Rp {{ number_format($o->total_bayar) }}</td>
                </tr>
                @endforeach
                @if($orders->isEmpty())
                <tr><td colspan="4" class="py-6 text-center text-slate-400 text-xs">Belum ada order masuk.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="lg:hidden divide-y divide-slate-100">
        @foreach($orders as $o)
        <a href="/admin/order/{{ $o->id }}" class="block py-2.5 hover:bg-slate-50 transition" style="text-decoration:none">
            <div class="flex justify-between items-center">
                <b class="text-slate-900 font-mono text-xs">{{ $o->kode }}</b>
                <span class="text-[10px] bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full font-extrabold uppercase">{{ $o->status }}</span>
            </div>
            <div class="text-xs text-slate-500 mt-1 flex justify-between">
                <span>{{ $o->user->name ?? '' }}</span>
                <span class="font-bold text-slate-800">Rp {{ number_format($o->total_bayar) }}</span>
            </div>
        </a>
        @endforeach
    </div>
    
    <div class="text-[11px] text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
        <span>Total Master Produk: <b>{{ $totalProduk }} Item</b></span>
        <span>Command Center Bubee</span>
    </div>
</div>

@endsection
