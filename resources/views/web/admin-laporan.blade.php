@extends('layouts.web-admin')
@section('title','Laporan')
@section('content')

@php
// Helper pemetaan status database menjadi bahasa Indonesia yang ramah manusia
$formatStatus = function($st) {
    return match($st) {
        'selesai' => [
            'label' => 'Pesanan Selesai / Diterima',
            'icon' => '✅',
            'badge' => 'bg-emerald-100 text-emerald-800 border border-emerald-200'
        ],
        'dikirim' => [
            'label' => 'Dalam Pengiriman (Kurir)',
            'icon' => '🚚',
            'badge' => 'bg-blue-100 text-blue-800 border border-blue-200'
        ],
        'diproses' => [
            'label' => 'Sedang Dikemas / Diproses',
            'icon' => '📦',
            'badge' => 'bg-amber-100 text-amber-800 border border-amber-200'
        ],
        'menunggu_bayar' => [
            'label' => 'Menunggu Pembayaran Pembeli',
            'icon' => '⏳',
            'badge' => 'bg-orange-100 text-orange-800 border border-orange-200'
        ],
        'batal' => [
            'label' => 'Pesanan Dibatalkan',
            'icon' => '❌',
            'badge' => 'bg-red-100 text-red-800 border border-red-200'
        ],
        default => [
            'label' => ucfirst(str_replace('_', ' ', (string) $st)),
            'icon' => '📄',
            'badge' => 'bg-slate-100 text-slate-700 border border-slate-200'
        ]
    };
};

// Helper pemetaan jenis mutasi stok
$formatMutasi = function($tipe) {
    return match($tipe) {
        'masuk' => ['label' => 'Barang Masuk (Penerimaan Pabrik)', 'badge' => 'text-emerald-700 bg-emerald-50 border-emerald-200'],
        'keluar' => ['label' => 'Barang Keluar (Pesanan Terjual)', 'badge' => 'text-red-700 bg-red-50 border-red-200'],
        'penyesuaian_opname' => ['label' => 'Koreksi Stok (Audit Fisik Rak)', 'badge' => 'text-purple-700 bg-purple-50 border-purple-200'],
        'retur' => ['label' => 'Pengembalian Barang (Klaim Retur)', 'badge' => 'text-amber-700 bg-amber-50 border-amber-200'],
        default => ['label' => ucfirst(str_replace('_', ' ', (string) $tipe)), 'badge' => 'text-slate-700 bg-slate-50 border-slate-200']
    };
};
@endphp

{{-- ==================== HEADER HALAMAN & PILIHAN PERIODE ==================== --}}
<div class="flex justify-between items-center flex-wrap gap-3">
    <div>
        <h1 class="font-extrabold text-2xl text-slate-900">📊 Laporan & Keuangan Bubee</h1>
        <div class="text-xs text-slate-500 mt-0.5">
            <b>{{ $labelPeriode }}</b> • Diperbarui {{ $generatedAt->format('d M H:i:s') }} • 
            <a href="/admin/laporan?periode={{ $periode }}&tanggal={{ $tanggal }}&minggu={{ $minggu }}&bulan={{ $bulan }}&dari={{ $dari }}&sampai={{ $sampai }}" class="font-bold text-blue-700 hover:underline" style="text-decoration:none">↻ Segarkan Data</a>
        </div>
    </div>
    
    {{-- DROPDOWN FILTER PERIODE --}}
    <div class="flex items-center flex-wrap gap-2">
        <div class="relative inline-flex items-center">
            <select id="pilih-periode" onchange="pilihPeriode(this.value)" class="appearance-none bg-white border-2 border-purple-800 text-purple-950 font-black text-sm rounded-2xl pl-3.5 pr-9 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-600 cursor-pointer">
                <option value="harian" {{ $periode==='harian'?'selected':'' }}>📅 Laporan Harian</option>
                <option value="mingguan" {{ $periode==='mingguan'?'selected':'' }}>📆 Laporan Mingguan</option>
                <option value="bulanan" {{ $periode==='bulanan'?'selected':'' }}>📊 Laporan Bulanan</option>
                <option value="kustom" {{ $periode==='kustom'?'selected':'' }}>🗓️ Kustom Tanggal</option>
            </select>
            <div class="pointer-events-none absolute right-3 flex items-center text-purple-800">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </div>
        </div>

        {{-- FORM INPUT SPESIFIK PERIODE --}}
        @if($periode === 'harian')
        <form method="GET" action="/admin/laporan" class="flex items-center gap-1.5">
            <input type="hidden" name="periode" value="harian">
            <input type="date" name="tanggal" value="{{ $tanggal }}" max="{{ now()->toDateString() }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold" onchange="this.form.submit()">
            <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-2xl text-xs">Pilih</button>
            <a href="/admin/laporan?periode=harian&tanggal={{ now()->toDateString() }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ $tanggal === now()->toDateString() ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Hari Ini</a>
            <a href="/admin/laporan?periode=harian&tanggal={{ now()->subDay()->toDateString() }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ $tanggal === now()->subDay()->toDateString() ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Kemarin</a>
        </form>
        @elseif($periode === 'mingguan')
        <form method="GET" action="/admin/laporan" class="flex items-center gap-1.5">
            <input type="hidden" name="periode" value="mingguan">
            <input type="date" name="minggu" value="{{ $minggu }}" max="{{ now()->toDateString() }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold" onchange="this.form.submit()">
            <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-2xl text-xs">Pilih</button>
            <a href="/admin/laporan?periode=mingguan&minggu={{ now()->toDateString() }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ \Carbon\Carbon::parse($minggu)->isCurrentWeek() ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Minggu Ini</a>
            <a href="/admin/laporan?periode=mingguan&minggu={{ now()->subWeek()->toDateString() }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ \Carbon\Carbon::parse($minggu)->isSameWeek(now()->subWeek()) ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Minggu Lalu</a>
        </form>
        @elseif($periode === 'bulanan')
        <form method="GET" action="/admin/laporan" class="flex items-center gap-1.5">
            <input type="hidden" name="periode" value="bulanan">
            <input type="month" name="bulan" value="{{ $bulan }}" max="{{ now()->format('Y-m') }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold" onchange="this.form.submit()">
            <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-2xl text-xs">Pilih</button>
            <a href="/admin/laporan?periode=bulanan&bulan={{ now()->format('Y-m') }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ $bulan === now()->format('Y-m') ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Bulan Ini</a>
            <a href="/admin/laporan?periode=bulanan&bulan={{ now()->subMonth()->format('Y-m') }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ $bulan === now()->subMonth()->format('Y-m') ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Bulan Lalu</a>
        </form>
        @else
        <form method="GET" action="/admin/laporan" class="flex items-center gap-1.5">
            <input type="hidden" name="periode" value="kustom">
            <input type="date" name="dari" value="{{ $dari }}" max="{{ now()->toDateString() }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold">
            <span class="text-xs text-slate-400">→</span>
            <input type="date" name="sampai" value="{{ $sampai }}" max="{{ now()->toDateString() }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold">
            <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-2xl text-xs">Tampilkan</button>
        </form>
        @endif

        {{-- TOMBOL CETAK & EXPORT --}}
        <div class="flex items-center gap-1.5">
            <button onclick="window.print()" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-1.5 px-3.5 rounded-2xl text-xs shadow-sm transition">🖨️ Cetak</button>
            <a href="/admin/laporan/export?periode={{ $periode }}&tanggal={{ $tanggal }}&minggu={{ $minggu }}&bulan={{ $bulan }}&dari={{ $dari }}&sampai={{ $sampai }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold py-1.5 px-3.5 rounded-2xl text-xs border border-emerald-200 transition" style="text-decoration:none">📊 Export CSV</a>
        </div>
    </div>
</div>

{{-- ==================== METRIK RINGKASAN ATAS ==================== --}}
<div class="grid gap-4 mt-4 lg:grid-cols-4">
    <div class="rounded-3xl p-6 text-white shadow-card lg:col-span-2 relative overflow-hidden" style="background:linear-gradient(135deg,#050B2E,#059669)">
        <div class="text-xs opacity-80 font-bold uppercase tracking-wider">Total Pendapatan • {{ $labelPeriode }}</div>
        <div class="font-black text-3xl lg:text-4xl mt-1 tracking-tight">Rp {{ number_format($ringkas['omzet']) }}</div>
        <div class="text-xs opacity-90 mt-2 font-medium flex items-center gap-2 flex-wrap">
            <span class="bg-white/20 px-2.5 py-0.5 rounded-full">📦 {{ $ringkas['jml_order'] }} Pesanan Berhasil</span>
            <span class="bg-white/20 px-2.5 py-0.5 rounded-full">🍶 {{ $ringkas['pcs'] }} Botol Terjual</span>
            <span class="bg-white/20 px-2.5 py-0.5 rounded-full">🎯 Rata-rata Rp {{ number_format($ringkas['aov']) }} / Pesanan</span>
        </div>
    </div>
    
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm flex flex-col justify-between border border-slate-100">
        <div>
            <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Potongan Kupon Diskon (Voucher)</div>
            <div class="font-extrabold text-xl text-emerald-600 mt-0.5">-Rp {{ number_format($ringkas['diskon']) }}</div>
            <div class="text-[10px] text-slate-400">Total hemat yang dinikmati pembeli</div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Klaim Garansi / Retur Aktif</div>
            <div class="font-extrabold text-xl text-amber-600 mt-0.5">{{ $ringkas['retur_aktif'] }} Permohonan</div>
            <div class="text-[10px] text-slate-400">Barang cacat/rusak dalam proses tindak lanjut</div>
        </div>
    </div>
    
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm flex flex-col justify-between border border-slate-100">
        <div>
            <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Pesanan Dibatalkan ({{ $ringkas['batal_jml'] }})</div>
            <div class="font-extrabold text-xl text-red-500 mt-0.5">Rp {{ number_format($ringkas['batal_nominal']) }}</div>
            <div class="text-[10px] text-slate-400">Nilai transaksi yang gagal atau dibatalkan</div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <div class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Menunggu Pembayaran ({{ $ringkas['tunggu_jml'] }})</div>
            <div class="font-extrabold text-xl text-slate-800 mt-0.5">Rp {{ number_format($ringkas['tunggu_nominal']) }}</div>
            <div class="text-[10px] text-slate-400">Tagihan pembeli yang belum ditransfer</div>
        </div>
    </div>
</div>

{{-- ==================== NAVIGASI SLIDER TAB DENGAN PANAH KE SAMPING ==================== --}}
<div class="mt-6 bg-white rounded-3xl p-3 shadow-card border border-slate-100 flex items-center justify-between flex-wrap gap-2 print:hidden">
    {{-- Tombol Panah Kiri --}}
    <button type="button" onclick="geserSlide(-1)" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-purple-100 text-slate-700 hover:text-purple-900 font-black text-lg flex items-center justify-center transition shadow-sm active:scale-95" title="Bagian Sebelumnya (Panah Kiri)">
        ❮
    </button>
    
    {{-- Tombol Tab Pilihan --}}
    <div class="flex items-center gap-1.5 overflow-x-auto py-1 scrollbar-none" id="laporanTabList">
        <button type="button" onclick="pilihSlide(1)" id="tab-btn-1" class="tab-laporan-btn px-4 py-2 rounded-2xl text-xs font-black transition flex items-center gap-2 bg-purple-900 text-white shadow-md">
            <span>📊</span>
            <span>1. Status & Ekspedisi Kurir</span>
        </button>
        <button type="button" onclick="pilihSlide(2)" id="tab-btn-2" class="tab-laporan-btn px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200/60">
            <span>🔥</span>
            <span>2. Produk Terlaris & Pelanggan</span>
        </button>
        <button type="button" onclick="pilihSlide(3)" id="tab-btn-3" class="tab-laporan-btn px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200/60">
            <span>📅</span>
            <span>3. Rincian Penjualan Harian</span>
        </button>
        <button type="button" onclick="pilihSlide(4)" id="tab-btn-4" class="tab-laporan-btn px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200/60">
            <span>📋</span>
            <span>4. Daftar Pesanan & Stok</span>
        </button>
    </div>

    {{-- Info Posisi & Tombol Panah Kanan --}}
    <div class="flex items-center gap-2">
        <span id="slide-indicator" class="text-xs font-extrabold text-purple-900 bg-purple-50 px-3 py-1.5 rounded-xl border border-purple-200 hidden sm:inline-block">Bagian 1 dari 4</span>
        <button type="button" onclick="geserSlide(1)" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-purple-100 text-slate-700 hover:text-purple-900 font-black text-lg flex items-center justify-center transition shadow-sm active:scale-95" title="Bagian Selanjutnya (Panah Kanan)">
            ❯
        </button>
    </div>
</div>

<style>
@media print {
    .slide-panel-item { display: block !important; margin-bottom: 24px; }
}
</style>

{{-- ==================== SLIDE 1: STATUS PESANAN & EKSPEDISI KURIR ==================== --}}
<div id="slide-panel-1" class="slide-panel-item mt-4">
    <div class="grid gap-4 lg:grid-cols-2 lg:items-start">
        {{-- KARTU 1: PER STATUS PESANAN --}}
        <div class="bg-white rounded-3xl shadow-card p-6 border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <b class="text-base text-slate-900">🧾 Status Pesanan Pelanggan <span class="text-xs text-slate-400 font-normal">(Rincian Per Status)</span></b>
                    <div class="text-[11px] text-slate-500 mt-0.5">Ringkasan kondisi seluruh pesanan yang tercatat pada periode ini</div>
                </div>
                <span class="text-xs font-extrabold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-xl">Total: {{ $perStatus->sum('jml') }} Pesanan</span>
            </div>
            
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                            <th class="py-2.5 px-1">Kondisi / Status Pesanan</th>
                            <th class="py-2.5 px-2 text-center">Banyak Pesanan (Order)</th>
                            <th class="py-2.5 px-2 text-right">Total Nilai Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($perStatus as $s)
                        @php $stInfo = $formatStatus($s->status); @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-2.5 px-1">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold {{ $stInfo['badge'] }}">
                                    <span>{{ $stInfo['icon'] }}</span>
                                    <span>{{ $stInfo['label'] }}</span>
                                </span>
                            </td>
                            <td class="py-2.5 px-2 text-center font-extrabold text-slate-800">{{ $s->jml }} Pesanan</td>
                            <td class="py-2.5 px-2 text-right font-black text-slate-900 text-sm">Rp {{ number_format($s->total) }}</td>
                        </tr>
                        @endforeach
                        @if($perStatus->isEmpty())
                        <tr><td colspan="3" class="py-6 text-center text-slate-400 text-xs">Belum ada pesanan pada periode ini.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- KARTU 2: PER KURIR EKSPEDISI --}}
        <div class="bg-white rounded-3xl shadow-card p-6 border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <b class="text-base text-slate-900">🚚 Jasa Ekspedisi Pengiriman <span class="text-xs text-slate-400 font-normal">(Rincian Per Kurir - Lunas)</span></b>
                    <div class="text-[11px] text-slate-500 mt-0.5">Distribusi pilihan kurir ekspedisi dari pesanan yang telah lunas dibayar</div>
                </div>
                <span class="text-xs font-bold text-slate-400">{{ $dari }} → {{ $sampai }}</span>
            </div>
            @php $totKurir = max(1, $perKurir->sum('total')); @endphp
            
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                            <th class="py-2.5 px-1">Jasa Kurir</th>
                            <th class="py-2.5 px-2 text-center">Paket Dikirim (Order)</th>
                            <th class="py-2.5 px-2">Porsi Pengiriman (Share)</th>
                            <th class="py-2.5 px-2 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($perKurir as $k)
                        @php $persen = round($k->total / $totKurir * 100); @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-2.5 px-1 font-black text-slate-900 flex items-center gap-1.5">
                                <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-bold">📦</span>
                                <span>{{ $k->kurir ?: 'Standar' }}</span>
                            </td>
                            <td class="py-2.5 px-2 text-center font-bold text-slate-700">{{ $k->jml }} Paket</td>
                            <td class="py-2.5 px-2">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden flex-1 max-w-[120px]">
                                        <div class="h-full bg-blue-600 rounded-full" style="width:{{ $persen }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500">{{ $persen }}%</span>
                                </div>
                            </td>
                            <td class="py-2.5 px-2 text-right font-black text-slate-900 text-sm">Rp {{ number_format($k->total) }}</td>
                        </tr>
                        @endforeach
                        @if($perKurir->isEmpty())
                        <tr><td colspan="4" class="py-6 text-center text-slate-400 text-xs">Belum ada pesanan lunas dengan kurir.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ==================== SLIDE 2: PRODUK TERLARIS & PELANGGAN ==================== --}}
<div id="slide-panel-2" class="slide-panel-item mt-4 hidden">
    <div class="grid gap-4 lg:grid-cols-2 lg:items-start">
        {{-- PRODUK TERLARIS --}}
        <div class="bg-white rounded-3xl shadow-card p-6 border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <b class="text-base text-slate-900">🔥 10 Produk Paling Laris & Diminati <span class="text-xs text-slate-400 font-normal">(Terlaris Top 10)</span></b>
                    <div class="text-[11px] text-slate-500 mt-0.5">Produk tumbler dengan kuantitas pembelian tertinggi pada periode ini</div>
                </div>
            </div>
            
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                            <th class="py-2.5 px-1">Nama Produk Tumbler (Barang)</th>
                            <th class="py-2.5 px-2 text-center">Jumlah Terjual (Pcs)</th>
                            <th class="py-2.5 px-2 text-right">Total Penjualan (Omzet)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($terlaris as $t)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-2.5 px-1">
                                <div class="inline-flex items-center gap-2.5">
                                    @if($t->product?->foto)
                                        <img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-9 h-9 rounded-xl object-cover shrink-0 border border-slate-200 bg-slate-100" loading="lazy">
                                    @else
                                        <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-sm shrink-0">🍶</span>
                                    @endif
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs leading-tight">{{ $t->product->nama ?? 'Produk Tidak Ditemukan' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $t->product->kategori ?? 'Tumbler' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-2 text-center font-extrabold text-blue-800 text-sm">{{ $t->terjual }} Botol</td>
                            <td class="py-2.5 px-2 text-right font-black text-slate-900 text-sm">Rp {{ number_format($t->omzet) }}</td>
                        </tr>
                        @endforeach
                        @if($terlaris->isEmpty())
                        <tr><td colspan="3" class="py-6 text-center text-slate-400 text-xs">Belum ada barang terjual pada periode ini.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOP PEMBELI & VOUCHER --}}
        <div class="space-y-4">
            {{-- TOP PEMBELI --}}
            <div class="bg-white rounded-3xl shadow-card p-6 border border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <b class="text-base text-slate-900">👑 Pelanggan Paling Sering Belanja <span class="text-xs text-slate-400 font-normal">(Top Pembeli)</span></b>
                        <div class="text-[11px] text-slate-500">Mitra reseller dan pembeli dengan nilai transaksi terbesar</div>
                    </div>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @foreach($topPembeli as $idx => $t)
                    <div class="flex items-center justify-between py-2.5 hover:bg-slate-50/50 px-1 rounded-xl transition">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-800 font-black text-[11px] flex items-center justify-center">{{ $idx + 1 }}</span>
                            <div>
                                <div class="font-bold text-xs text-slate-900">{{ $t->user->name ?? 'Pelanggan Umum' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $t->jml }} kali transaksi pemesanan</div>
                            </div>
                        </div>
                        <div class="font-black text-xs text-slate-900">Rp {{ number_format($t->total) }}</div>
                    </div>
                    @endforeach
                    @if($topPembeli->isEmpty())
                    <div class="text-xs text-slate-400 py-4 text-center">Belum ada data transaksi pembeli.</div>
                    @endif
                </div>
            </div>

            {{-- VOUCHER TERPAKAI --}}
            <div class="bg-white rounded-3xl shadow-card p-6 border border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <b class="text-base text-slate-900">🎟️ Kupon Diskon yang Digunakan <span class="text-xs text-slate-400 font-normal">(Voucher Terpakai)</span></b>
                        <div class="text-[11px] text-slate-500">Kupon promosi belanja yang berhasil dimanfaatkan pelanggan</div>
                    </div>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @foreach($topVoucher as $v)
                    <div class="flex items-center justify-between py-2.5 hover:bg-slate-50/50 px-1 rounded-xl transition">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-lg bg-pink-100 text-pink-700 font-mono font-extrabold text-[11px] border border-pink-200">
                                {{ $v->voucher->kode ?? 'KUPON' }}
                            </span>
                            <div class="text-[11px] text-slate-500 font-medium">Digunakan {{ $v->dipakai }} kali transaksi</div>
                        </div>
                        <div class="font-extrabold text-xs text-emerald-600">-Rp {{ number_format($v->hemat) }}</div>
                    </div>
                    @endforeach
                    @if($topVoucher->isEmpty())
                    <div class="text-xs text-slate-400 py-4 text-center">Belum ada kupon diskon yang dipakai.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== SLIDE 3: RINCIAN PENJUALAN HARIAN ==================== --}}
<div id="slide-panel-3" class="slide-panel-item mt-4 hidden">
    <div class="bg-white rounded-3xl shadow-card p-6 border border-slate-100">
        <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
            <div>
                <b class="text-base text-slate-900">📅 Grafik & Rincian Penjualan Harian <span class="text-xs text-slate-400 font-normal">(Laporan Harian)</span></b>
                <div class="text-[11px] text-slate-500 mt-0.5">Pergerakan total penjualan dan botol terjual dari hari ke hari dalam periode ini</div>
            </div>
            <span class="text-xs font-bold text-slate-400">{{ count($harian) }} Hari Tercatat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs lg:table">
                <thead>
                    <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                        <th class="py-2.5 px-3">Hari & Tanggal</th>
                        <th class="py-2.5 px-3 text-center">Transaksi (Order)</th>
                        <th class="py-2.5 px-3 text-center">Barang Terjual (Pcs)</th>
                        <th class="py-2.5 px-3">Grafik Perkembangan Omzet</th>
                        <th class="py-2.5 px-3 text-right">Pendapatan Bersih (Omzet)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($harian as $h)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 px-3 font-bold text-slate-800">
                            <span class="text-purple-900 font-extrabold">{{ $h['hari'] }},</span> {{ $h['tgl'] }}
                        </td>
                        <td class="py-2.5 px-3 text-center font-bold text-slate-700">
                            @if($h['order'] > 0)
                                <span class="bg-blue-50 text-blue-700 font-extrabold px-2 py-0.5 rounded-lg">{{ $h['order'] }} Order</span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-3 text-center font-medium text-slate-600">
                            {{ $h['pcs'] > 0 ? $h['pcs'] . ' Botol' : '-' }}
                        </td>
                        <td class="py-2.5 px-3">
                            <div class="flex items-center gap-2">
                                <div class="h-2 rounded-full bg-slate-100 overflow-hidden flex-1 max-w-xs">
                                    <div class="h-full bg-purple-700 rounded-full" style="width:{{ $maxOmzet>0 ? round($h['omzet']/$maxOmzet*100) : 0 }}%"></div>
                                </div>
                                <span class="text-[9.5px] font-bold text-slate-400 w-8">{{ $maxOmzet>0 ? round($h['omzet']/$maxOmzet*100) : 0 }}%</span>
                            </div>
                        </td>
                        <td class="py-2.5 px-3 text-right font-black text-slate-900 text-sm">
                            Rp {{ number_format($h['omzet']) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== SLIDE 4: DAFTAR PESANAN & MUTASI STOK ==================== --}}
<div id="slide-panel-4" class="slide-panel-item mt-4 hidden">
    <div class="space-y-4">
        {{-- DAFTAR PESANAN --}}
        <div class="bg-white rounded-3xl shadow-card p-6 border border-slate-100">
            <div class="flex justify-between items-center mb-3 flex-wrap gap-2">
                <div>
                    <b class="text-base text-slate-900">📋 Daftar Rincian Pesanan Masuk ({{ $rekOrders->count() }} Pesanan)</b>
                    <div class="text-[11px] text-slate-500">Histori faktur pemesanan produk dalam rentang periode ini</div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs lg:table">
                    <thead>
                        <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                            <th class="py-2.5 px-3">Nomor Faktur</th>
                            <th class="py-2.5 px-3">Waktu Pemesanan</th>
                            <th class="py-2.5 px-3">Nama Pembeli</th>
                            <th class="py-2.5 px-3">Kurir & Layanan</th>
                            <th class="py-2.5 px-3 text-right">Total Tagihan</th>
                            <th class="py-2.5 px-3 text-center">Status Pesanan</th>
                            <th class="py-2.5 px-3 text-center">Rincian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rekOrders as $o)
                        @php $stO = $formatStatus($o->status); @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-2.5 px-3 font-mono font-extrabold text-blue-700 whitespace-nowrap">
                                <a href="/admin/order/{{ $o->id }}" class="hover:underline" style="text-decoration:none">{{ $o->kode }}</a>
                            </td>
                            <td class="py-2.5 px-3 text-slate-500 whitespace-nowrap">{{ $o->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-2.5 px-3 font-bold text-slate-800">{{ $o->user->name ?? 'Pelanggan Tamu' }}</td>
                            <td class="py-2.5 px-3 text-slate-600 whitespace-nowrap">{{ $o->kurir ?: 'Kurir' }} <span class="text-slate-400">({{ $o->layanan ?: 'Standar' }})</span></td>
                            <td class="py-2.5 px-3 text-right font-black text-slate-900 whitespace-nowrap">Rp {{ number_format($o->total_bayar) }}</td>
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2.5 py-1 rounded-xl {{ $stO['badge'] }}">
                                    <span>{{ $stO['icon'] }}</span>
                                    <span>{{ $stO['label'] }}</span>
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <a href="/admin/order/{{ $o->id }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold px-3 py-1 rounded-xl text-[11px] transition" style="text-decoration:none">Buka Detail →</a>
                            </td>
                        </tr>
                        @endforeach
                        @if($rekOrders->isEmpty())
                        <tr><td colspan="7" class="py-8 text-center text-slate-400 text-xs">Belum ada transaksi pemesanan pada periode ini.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MUTASI STOK --}}
        <div class="bg-white rounded-3xl shadow-card p-6 border border-slate-100">
            <div class="mb-3">
                <b class="text-base text-slate-900">📦 Riwayat Pergerakan Stok Barang <span class="text-xs text-slate-400 font-normal">(Mutasi Stok Terakhir)</span></b>
                <div class="text-[11px] text-slate-500">Log audit keluar-masuk stok barang tumbler di gudang</div>
            </div>
            
            <div class="divide-y divide-slate-100">
                @foreach($mutasi as $m)
                @php $mutInfo = $formatMutasi($m->tipe); @endphp
                <div class="text-xs py-2.5 flex items-center justify-between flex-wrap gap-2 hover:bg-slate-50/50 px-2 rounded-xl transition">
                    <div>
                        <div class="font-bold text-slate-900 flex items-center gap-2">
                            <span>{{ $m->product->nama ?? 'Produk' }}</span>
                            @if($m->variant)
                                <span class="text-[10px] font-normal text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">Varian: {{ $m->variant->label() }}</span>
                            @endif
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full border {{ $mutInfo['badge'] }}">
                                {{ $mutInfo['label'] }}
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5">
                            {{ $m->created_at->format('d M Y, H:i') }} • {{ $m->keterangan ?: 'Tidak ada catatan tambahan' }}
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="font-black text-sm {{ $m->tipe === 'masuk' ? 'text-emerald-700' : 'text-slate-800' }}">
                            {{ $m->tipe === 'masuk' ? '+' : '-' }}{{ $m->qty }} Botol
                        </div>
                        <div class="text-[10px] text-slate-400">Saldo: {{ $m->stok_sebelum }} → <b>{{ $m->stok_sesudah }}</b></div>
                    </div>
                </div>
                @endforeach
                @if($mutasi->isEmpty())
                <div class="text-xs text-slate-400 py-4 text-center">Belum ada riwayat mutasi stok.</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT KONTROL PERIODE DAN SLIDER HORIZONTAL DENGAN PANAH KE SAMPING --}}
<script>
function pilihPeriode(val) {
    var url = '/admin/laporan?periode=' + val;
    if (val === 'harian') {
        url += '&tanggal={{ $tanggal }}';
    } else if (val === 'mingguan') {
        url += '&minggu={{ $minggu }}';
    } else if (val === 'bulanan') {
        url += '&bulan={{ $bulan }}';
    } else if (val === 'kustom') {
        url += '&dari={{ $dari }}&sampai={{ $sampai }}';
    }
    window.location.href = url;
}

// LOGIKA NAVIGASI SLIDER TAB DENGAN PANAH KE SAMPING
let currentSlide = 1;
const totalSlides = 4;

function pilihSlide(n) {
    currentSlide = n;
    updateSlideDisplay();
}

function geserSlide(dir) {
    currentSlide += dir;
    if (currentSlide < 1) currentSlide = totalSlides;
    if (currentSlide > totalSlides) currentSlide = 1;
    updateSlideDisplay();
}

function updateSlideDisplay() {
    for (let i = 1; i <= totalSlides; i++) {
        const slide = document.getElementById('slide-panel-' + i);
        const btn = document.getElementById('tab-btn-' + i);
        if (slide) {
            if (i === currentSlide) {
                slide.classList.remove('hidden');
                slide.classList.add('block');
            } else {
                slide.classList.add('hidden');
                slide.classList.remove('block');
            }
        }
        if (btn) {
            if (i === currentSlide) {
                btn.className = "tab-laporan-btn px-4 py-2 rounded-2xl text-xs font-black transition flex items-center gap-2 bg-purple-900 text-white shadow-md";
            } else {
                btn.className = "tab-laporan-btn px-4 py-2 rounded-2xl text-xs font-bold transition flex items-center gap-2 bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200/60";
            }
        }
    }
    const indicator = document.getElementById('slide-indicator');
    if (indicator) {
        indicator.innerText = 'Bagian ' + currentSlide + ' dari ' + totalSlides;
    }
}
</script>

@endsection
