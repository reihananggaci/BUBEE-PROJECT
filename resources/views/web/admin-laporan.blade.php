@extends('layouts.web-admin')
@section('title','Laporan')
@section('content')
<div class="flex justify-between items-center flex-wrap gap-3">
    <div>
        <h1 class="font-extrabold text-2xl">📊 Laporan Bubee</h1>
        <div class="text-xs text-slate-500 mt-0.5">
            <b>{{ $labelPeriode }}</b> • Diperbarui {{ $generatedAt->format('d M H:i:s') }} • 
            <a href="/admin/laporan?periode={{ $periode }}&tanggal={{ $tanggal }}&minggu={{ $minggu }}&bulan={{ $bulan }}&dari={{ $dari }}&sampai={{ $sampai }}" class="font-bold text-blue-700" style="text-decoration:none">↻ Muat ulang</a>
        </div>
    </div>
    
    {{-- DROPDOWN FILTER PERIODE DENGAN PANAH KE BAWAH (SEPERTI DI ORDER) & KONTROL --}}
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
            <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-2xl text-xs">Lihat</button>
            <a href="/admin/laporan?periode=harian&tanggal={{ now()->toDateString() }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ $tanggal === now()->toDateString() ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Hari Ini</a>
            <a href="/admin/laporan?periode=harian&tanggal={{ now()->subDay()->toDateString() }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ $tanggal === now()->subDay()->toDateString() ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Kemarin</a>
        </form>
        @elseif($periode === 'mingguan')
        <form method="GET" action="/admin/laporan" class="flex items-center gap-1.5">
            <input type="hidden" name="periode" value="mingguan">
            <input type="date" name="minggu" value="{{ $minggu }}" max="{{ now()->toDateString() }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold" onchange="this.form.submit()">
            <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-2xl text-xs">Lihat</button>
            <a href="/admin/laporan?periode=mingguan&minggu={{ now()->toDateString() }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ \Carbon\Carbon::parse($minggu)->isCurrentWeek() ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Minggu Ini</a>
            <a href="/admin/laporan?periode=mingguan&minggu={{ now()->subWeek()->toDateString() }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ \Carbon\Carbon::parse($minggu)->isSameWeek(now()->subWeek()) ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Minggu Lalu</a>
        </form>
        @elseif($periode === 'bulanan')
        <form method="GET" action="/admin/laporan" class="flex items-center gap-1.5">
            <input type="hidden" name="periode" value="bulanan">
            <input type="month" name="bulan" value="{{ $bulan }}" max="{{ now()->format('Y-m') }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold" onchange="this.form.submit()">
            <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-2xl text-xs">Lihat</button>
            <a href="/admin/laporan?periode=bulanan&bulan={{ now()->format('Y-m') }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ $bulan === now()->format('Y-m') ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Bulan Ini</a>
            <a href="/admin/laporan?periode=bulanan&bulan={{ now()->subMonth()->format('Y-m') }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold {{ $bulan === now()->subMonth()->format('Y-m') ? 'bg-purple-900 text-white' : 'bg-white text-slate-700 shadow-sm' }}" style="text-decoration:none">Bulan Lalu</a>
        </form>
        @else
        <form method="GET" action="/admin/laporan" class="flex items-center gap-1.5">
            <input type="hidden" name="periode" value="kustom">
            <input type="date" name="dari" value="{{ $dari }}" max="{{ now()->toDateString() }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold">
            <span class="text-xs text-slate-400">→</span>
            <input type="date" name="sampai" value="{{ $sampai }}" max="{{ now()->toDateString() }}" class="border border-slate-300 rounded-2xl px-3 py-1.5 text-xs font-bold">
            <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-2xl text-xs">OK</button>
        </form>
        @endif

        {{-- TOMBOL AKSI CETAK & EXPORT CSV --}}
        <div class="flex items-center gap-1.5">
            <button onclick="window.print()" class="bg-slate-900 text-white font-bold py-1.5 px-3 rounded-2xl text-xs shadow-sm">🖨️ Cetak</button>
            <a href="/admin/laporan/export?periode={{ $periode }}&tanggal={{ $tanggal }}&minggu={{ $minggu }}&bulan={{ $bulan }}&dari={{ $dari }}&sampai={{ $sampai }}" class="bg-emerald-50 text-emerald-700 font-bold py-1.5 px-3.5 rounded-2xl text-xs border border-emerald-200" style="text-decoration:none">📊 Export CSV</a>
        </div>
    </div>
</div>

{{-- METRIK RINGKASAN ATAS --}}
<div class="grid gap-4 mt-4 lg:grid-cols-4">
    <div class="rounded-3xl p-6 text-white shadow-card lg:col-span-2" style="background:linear-gradient(135deg,#050B2E,#059669)">
        <div class="text-xs opacity-75 font-medium">{{ $labelPeriode }}</div>
        <div class="font-extrabold text-3xl mt-1">Rp {{ number_format($ringkas['omzet']) }}</div>
        <div class="text-xs opacity-85 mt-2">{{ $ringkas['jml_order'] }} order • {{ $ringkas['pcs'] }} pcs • Rata-rata Rp {{ number_format($ringkas['aov']) }}/order</div>
    </div>
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm flex flex-col justify-between">
        <div>
            <div class="text-[11px] font-bold text-slate-400">DISKON VOUCHER</div>
            <div class="font-extrabold text-xl text-emerald-600">-Rp {{ number_format($ringkas['diskon']) }}</div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <div class="text-[11px] font-bold text-slate-400">RETUR AKTIF</div>
            <div class="font-extrabold text-xl text-amber-600">{{ $ringkas['retur_aktif'] }} pengajuan</div>
        </div>
    </div>
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm flex flex-col justify-between">
        <div>
            <div class="text-[11px] font-bold text-slate-400">BATAL ({{ $ringkas['batal_jml'] }})</div>
            <div class="font-extrabold text-xl text-red-500">Rp {{ number_format($ringkas['batal_nominal']) }}</div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <div class="text-[11px] font-bold text-slate-400">NUNGGU BAYAR ({{ $ringkas['tunggu_jml'] }})</div>
            <div class="font-extrabold text-xl text-slate-800">Rp {{ number_format($ringkas['tunggu_nominal']) }}</div>
        </div>
    </div>
</div>

@if($periode === 'harian')
{{-- ==================== TAMPILAN HARIAN ==================== --}}
<div class="grid gap-4 mt-4 lg:grid-cols-3 lg:items-start">
    <div class="space-y-4">
        {{-- PER STATUS --}}
        <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
            <b class="text-base">🧾 Per Status</b>
            <div class="overflow-x-auto mt-2">
                <table class="w-full text-xs lg:table">
                    <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Status</th><th class="py-1 text-center">Order</th><th class="py-1 text-right">Total</th></tr></thead>
                    <tbody>
                        @foreach($perStatus as $s)
                        <tr class="border-t border-slate-100"><td class="py-1.5 font-bold">{{ $s->status }}</td><td class="py-1.5 text-center">{{ $s->jml }}</td><td class="py-1.5 text-right font-bold">Rp {{ number_format($s->total) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PER KURIR --}}
        <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
            <b class="text-base">🚚 Per Kurir (lunas)</b>
            @php $totKurir = max(1, $perKurir->sum('total')); @endphp
            <div class="overflow-x-auto mt-2">
                <table class="w-full text-xs lg:table">
                    <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Kurir</th><th class="py-1 text-center">Order</th><th class="py-1 text-right">Total</th></tr></thead>
                    <tbody>
                        @foreach($perKurir as $k)
                        <tr class="border-t border-slate-100"><td class="py-1.5 font-bold">{{ $k->kurir }}</td><td class="py-1.5 text-center">{{ $k->jml }}</td><td class="py-1.5 text-right font-bold">Rp {{ number_format($k->total) }}</td></tr>
                        @endforeach
                        @if($perKurir->isEmpty())<tr><td colspan="3" class="py-2 text-slate-400 text-center">Belum ada order lunas.</td></tr>@endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PRODUK TERLARIS --}}
        <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
            <b class="text-base">🔥 Produk Terlaris Hari Ini</b>
            <div class="overflow-x-auto mt-2">
                <table class="w-full text-xs lg:table">
                    <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Produk</th><th class="py-1 text-center">Pcs</th><th class="py-1 text-right">Omzet</th></tr></thead>
                    <tbody>
                        @foreach($terlaris as $t)
                        <tr class="border-t border-slate-100">
                            <td class="py-1.5 font-bold">
                                <span class="inline-flex items-center gap-1.5 align-middle">
                                    @if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif
                                    <span>{{ $t->product->nama ?? '?' }}</span>
                                </span>
                            </td>
                            <td class="py-1.5 text-center font-bold">{{ $t->terjual }}</td>
                            <td class="py-1.5 text-right font-bold">Rp {{ number_format($t->omzet) }}</td>
                        </tr>
                        @endforeach
                        @if($terlaris->isEmpty())<tr><td colspan="3" class="py-2 text-slate-400 text-center">Belum ada penjualan.</td></tr>@endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DAFTAR ORDER HARI INI --}}
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-card p-5 text-sm">
        <div class="flex justify-between items-center mb-3">
            <b class="text-base">📋 Order Tanggal Ini ({{ $rekOrders->count() }})</b>
            <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs lg:table">
                <thead>
                    <tr class="text-left text-[11px] text-slate-400">
                        <th class="py-2 px-3">Kode</th>
                        <th class="py-2 px-3">Waktu</th>
                        <th class="py-2 px-3">Pembeli</th>
                        <th class="py-2 px-3">Kurir</th>
                        <th class="py-2 px-3 text-right">Total</th>
                        <th class="py-2 px-3 text-center">Status</th>
                        <th class="py-2 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekOrders as $o)
                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="py-2 px-3 font-extrabold text-blue-700 whitespace-nowrap"><a href="/admin/order/{{ $o->id }}" style="text-decoration:none">{{ $o->kode }}</a></td>
                        <td class="py-2 px-3 text-slate-500 whitespace-nowrap">{{ $o->created_at->format('H:i') }}</td>
                        <td class="py-2 px-3">{{ $o->user->name ?? 'Tamu' }}</td>
                        <td class="py-2 px-3 text-slate-600 whitespace-nowrap">{{ $o->kurir }} {{ $o->layanan }}</td>
                        <td class="py-2 px-3 text-right font-extrabold whitespace-nowrap">Rp {{ number_format($o->total_bayar) }}</td>
                        <td class="py-2 px-3 text-center whitespace-nowrap">
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $o->status==='selesai'?'bg-emerald-100 text-emerald-700':($o->status==='batal'?'bg-red-100 text-red-700':'bg-cyan-100 text-cyan-800') }}">
                                {{ $o->status }}
                            </span>
                        </td>
                        <td class="py-2 px-3 text-center whitespace-nowrap">
                            <a href="/admin/order/{{ $o->id }}" class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-xl text-[11px]" style="text-decoration:none">Detail →</a>
                        </td>
                    </tr>
                    @endforeach
                    @if($rekOrders->isEmpty())
                    <tr><td colspan="7" class="py-6 text-center text-slate-400">Tidak ada order tanggal ini.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@elseif($periode === 'mingguan')
{{-- ==================== TAMPILAN MINGGUAN ==================== --}}
<div class="bg-white rounded-3xl shadow-card p-5 mt-4 text-sm">
    <div class="flex justify-between items-center mb-2">
        <b class="text-base">📅 Grafik & Rekap Harian 7 Hari (Senin - Minggu)</b>
        <span class="text-xs text-slate-400">Periode {{ $dari }} → {{ $sampai }}</span>
    </div>
    <div class="overflow-x-auto mt-2">
        <table class="w-full text-xs lg:table">
            <thead>
                <tr class="text-left text-[11px] text-slate-400">
                    <th class="py-2 px-3">Hari</th>
                    <th class="py-2 px-3">Tanggal</th>
                    <th class="py-2 px-3 text-center">Order</th>
                    <th class="py-2 px-3 text-center">Pcs</th>
                    <th class="py-2 px-3">Grafik Omzet</th>
                    <th class="py-2 px-3 text-right">Omzet</th>
                </tr>
            </thead>
            <tbody>
                @foreach($harian as $h)
                <tr class="border-t border-slate-100">
                    <td class="py-2 px-3 font-extrabold text-purple-900">{{ $h['hari'] }}</td>
                    <td class="py-2 px-3 text-slate-600">{{ $h['tgl'] }}</td>
                    <td class="py-2 px-3 text-center font-bold">{{ $h['order'] }}</td>
                    <td class="py-2 px-3 text-center">{{ $h['pcs'] }}</td>
                    <td class="py-2 px-3">
                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden w-full max-w-xs">
                            <div class="h-full bg-purple-700 rounded-full" style="width:{{ $maxOmzet>0?round($h['omzet']/$maxOmzet*100):0 }}%"></div>
                        </div>
                    </td>
                    <td class="py-2 px-3 text-right font-extrabold">Rp {{ number_format($h['omzet']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="grid gap-4 mt-4 lg:grid-cols-2 lg:items-start">
    {{-- PER STATUS --}}
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
        <b class="text-base">🧾 Per Status</b>
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-sm lg:table">
                <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Status</th><th class="py-1 text-center">Order</th><th class="py-1 text-right">Total</th></tr></thead>
                <tbody>
                    @foreach($perStatus as $s)
                    <tr class="border-t border-slate-100"><td class="py-1.5 font-bold">{{ $s->status }}</td><td class="py-1.5 text-center">{{ $s->jml }}</td><td class="py-1.5 text-right font-bold">Rp {{ number_format($s->total) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- PER KURIR --}}
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
        <b class="text-base">🚚 Per Kurir (lunas)</b>
        @php $totKurir = max(1, $perKurir->sum('total')); @endphp
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-sm lg:table">
                <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Kurir</th><th class="py-1 text-center">Order</th><th class="py-1">Share</th><th class="py-1 text-right">Total</th></tr></thead>
                <tbody>
                    @foreach($perKurir as $k)
                    <tr class="border-t border-slate-100">
                        <td class="py-1.5 font-bold">{{ $k->kurir }}</td>
                        <td class="py-1.5 text-center">{{ $k->jml }}</td>
                        <td class="py-1.5"><div class="h-1.5 rounded-full bg-slate-100 overflow-hidden w-24"><div class="h-full bg-blue-700 rounded-full" style="width:{{ round($k->total/$totKurir*100) }}%"></div></div></td>
                        <td class="py-1.5 text-right font-bold">Rp {{ number_format($k->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid gap-4 mt-4 lg:grid-cols-2 lg:items-start">
    {{-- PRODUK TERLARIS --}}
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
        <b class="text-base">🔥 Terlaris Top 10 Minggu Ini</b>
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-sm lg:table">
                <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Barang</th><th class="py-1 text-center">Pcs</th><th class="py-1 text-right">Omzet</th></tr></thead>
                <tbody>
                    @foreach($terlaris as $t)
                    <tr class="border-t border-slate-100">
                        <td class="py-1 font-bold">
                            <span class="inline-flex items-center gap-1.5 align-middle">
                                @if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif
                                <span>{{ $t->product->nama ?? '?' }}</span>
                            </span>
                        </td>
                        <td class="py-1 text-center font-bold">{{ $t->terjual }}</td>
                        <td class="py-1 text-right font-bold">Rp {{ number_format($t->omzet) }}</td>
                    </tr>
                    @endforeach
                    @if($terlaris->isEmpty())<tr><td colspan="3" class="py-2 text-slate-400 text-center">Belum ada penjualan.</td></tr>@endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOP PEMBELI & VOUCHER --}}
    <div class="space-y-4">
        <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
            <b class="text-base">👑 Top Pembeli</b>
            @foreach($topPembeli as $t)
            <div class="flex justify-between py-1.5 border-b border-slate-100 text-xs"><span>{{ $t->user->name ?? '?' }} ({{ $t->jml }} order)</span><b>Rp {{ number_format($t->total) }}</b></div>
            @endforeach
            @if($topPembeli->isEmpty())<div class="text-xs text-slate-400 py-1">Belum ada pembeli.</div>@endif
        </div>
        <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
            <b class="text-base">🎟️ Voucher Terpakai</b>
            @foreach($topVoucher as $v)
            <div class="flex justify-between py-1.5 border-b border-slate-100 text-xs"><span>{{ $v->voucher->kode ?? '?' }} ({{ $v->dipakai }}x)</span><b>-Rp {{ number_format($v->hemat) }}</b></div>
            @endforeach
            @if($topVoucher->isEmpty())<div class="text-xs text-slate-400 py-1">Belum ada voucher digunakan.</div>@endif
        </div>
    </div>
</div>

{{-- DAFTAR ORDER MINGGU INI --}}
<div class="bg-white rounded-3xl shadow-card p-5 mt-4 text-sm">
    <div class="flex justify-between items-center mb-3">
        <b class="text-base">📋 Daftar Pesanan Minggu Ini ({{ $rekOrders->count() }})</b>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs lg:table">
            <thead>
                <tr class="text-left text-[11px] text-slate-400">
                    <th class="py-2 px-3">Kode</th>
                    <th class="py-2 px-3">Tanggal & Waktu</th>
                    <th class="py-2 px-3">Pembeli</th>
                    <th class="py-2 px-3">Kurir</th>
                    <th class="py-2 px-3 text-right">Total</th>
                    <th class="py-2 px-3 text-center">Status</th>
                    <th class="py-2 px-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekOrders as $o)
                <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                    <td class="py-2 px-3 font-extrabold text-blue-700 whitespace-nowrap"><a href="/admin/order/{{ $o->id }}" style="text-decoration:none">{{ $o->kode }}</a></td>
                    <td class="py-2 px-3 text-slate-500 whitespace-nowrap">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-2 px-3">{{ $o->user->name ?? 'Tamu' }}</td>
                    <td class="py-2 px-3 text-slate-600 whitespace-nowrap">{{ $o->kurir }} {{ $o->layanan }}</td>
                    <td class="py-2 px-3 text-right font-extrabold whitespace-nowrap">Rp {{ number_format($o->total_bayar) }}</td>
                    <td class="py-2 px-3 text-center whitespace-nowrap">
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $o->status==='selesai'?'bg-emerald-100 text-emerald-700':($o->status==='batal'?'bg-red-100 text-red-700':'bg-cyan-100 text-cyan-800') }}">
                            {{ $o->status }}
                        </span>
                    </td>
                    <td class="py-2 px-3 text-center whitespace-nowrap">
                        <a href="/admin/order/{{ $o->id }}" class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-xl text-[11px]" style="text-decoration:none">Detail →</a>
                    </td>
                </tr>
                @endforeach
                @if($rekOrders->isEmpty())
                <tr><td colspan="7" class="py-6 text-center text-slate-400">Tidak ada order pada minggu ini.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@else
{{-- ==================== TAMPILAN BULANAN & KUSTOM ==================== --}}
<div class="grid gap-4 mt-4 lg:grid-cols-2 lg:items-start">
    {{-- PER STATUS --}}
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
        <b class="text-base">🧾 Per Status</b>
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-sm lg:table">
                <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Status</th><th class="py-1 text-center">Order</th><th class="py-1 text-right">Total</th></tr></thead>
                <tbody>
                    @foreach($perStatus as $s)
                    <tr class="border-t border-slate-100"><td class="py-1 font-bold">{{ $s->status }}</td><td class="py-1 text-center">{{ $s->jml }}</td><td class="py-1 text-right font-bold">Rp {{ number_format($s->total) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- PER KURIR --}}
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
        <b class="text-base">🚚 Per Kurir (lunas)</b>
        <div class="text-[11px] text-slate-400 font-normal">Periode {{ $dari }} → {{ $sampai }}</div>
        @php $totKurir = max(1, $perKurir->sum('total')); @endphp
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-sm lg:table">
                <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Kurir</th><th class="py-1 text-center">Order</th><th class="py-1">Share</th><th class="py-1 text-right">Total</th></tr></thead>
                <tbody>
                    @foreach($perKurir as $k)
                    <tr class="border-t border-slate-100">
                        <td class="py-1 font-bold">{{ $k->kurir }}</td>
                        <td class="py-1 text-center">{{ $k->jml }}</td>
                        <td class="py-1"><div class="h-1.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-full bg-blue-700 rounded-full" style="width:{{ round($k->total/$totKurir*100) }}%"></div></div></td>
                        <td class="py-1 text-right font-bold">Rp {{ number_format($k->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid gap-4 mt-4 lg:grid-cols-2 lg:items-start">
    {{-- PRODUK TERLARIS --}}
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
        <b class="text-base">🔥 Terlaris Top 10</b>
        <div class="text-[11px] text-slate-400 font-normal">Periode {{ $dari }} → {{ $sampai }}</div>
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-sm lg:table">
                <thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Barang</th><th class="py-1 text-center">Pcs</th><th class="py-1 text-right">Omzet</th></tr></thead>
                <tbody>
                    @foreach($terlaris as $t)
                    <tr class="border-t border-slate-100">
                        <td class="py-1 font-bold">
                            <span class="inline-flex items-center gap-1.5 align-middle">
                                @if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="shrink-0">📦</span>@endif
                                <span>{{ $t->product->nama ?? '?' }}</span>
                            </span>
                        </td>
                        <td class="py-1 text-center font-bold">{{ $t->terjual }}</td>
                        <td class="py-1 text-right font-bold">Rp {{ number_format($t->omzet) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOP PEMBELI & VOUCHER --}}
    <div class="space-y-4">
        <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
            <b class="text-base">👑 Top Pembeli</b>
            @foreach($topPembeli as $t)
            <div class="flex justify-between py-1 border-b border-slate-100 text-xs"><span>{{ $t->user->name ?? '?' }} ({{ $t->jml }} order)</span><b>Rp {{ number_format($t->total) }}</b></div>
            @endforeach
        </div>
        <div class="bg-white rounded-3xl shadow-card p-5 text-sm">
            <b class="text-base">🎟️ Voucher Terpakai</b>
            @foreach($topVoucher as $v)
            <div class="flex justify-between py-1 border-b border-slate-100 text-xs"><span>{{ $v->voucher->kode ?? '?' }} ({{ $v->dipakai }}x)</span><b>-Rp {{ number_format($v->hemat) }}</b></div>
            @endforeach
        </div>
    </div>
</div>

{{-- TABEL DAN GRAFIK HARIAN --}}
<div class="bg-white rounded-3xl shadow-card p-5 mt-4 text-sm">
    <details open>
        <summary class="font-extrabold cursor-pointer text-base">📅 Harian <span class="text-[11px] font-normal text-slate-400">(klik untuk sembunyikan)</span></summary>
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-xs lg:table">
                <thead>
                    <tr class="text-left text-[11px] text-slate-400">
                        <th class="py-1 px-2">Tanggal</th>
                        <th class="py-1 px-2 text-center">Order</th>
                        <th class="py-1 px-2 text-center">Pcs</th>
                        <th class="py-1 px-2">Grafik</th>
                        <th class="py-1 px-2 text-right">Omzet</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($harian as $h)
                    <tr class="border-t border-slate-100">
                        <td class="py-1.5 px-2 font-bold">{{ $h['tgl'] }}</td>
                        <td class="py-1.5 px-2 text-center">{{ $h['order'] }}</td>
                        <td class="py-1.5 px-2 text-center">{{ $h['pcs'] }}</td>
                        <td class="py-1.5 px-2">
                            <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden w-full max-w-xs">
                                <div class="h-full bg-blue-700 rounded-full" style="width:{{ $maxOmzet>0?round($h['omzet']/$maxOmzet*100):0 }}%"></div>
                            </div>
                        </td>
                        <td class="py-1.5 px-2 text-right font-bold">Rp {{ number_format($h['omzet']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
</div>

<div class="bg-white rounded-3xl shadow-card p-5 mt-4 text-sm">
    <details>
        <summary class="font-extrabold cursor-pointer text-base">📦 Mutasi Stok Terakhir <span class="text-[11px] font-normal text-slate-400">(klik untuk buka)</span></summary>
        @foreach($mutasi as $m)
        <div class="text-xs py-1.5 border-b border-slate-100">
            {{ $m->created_at->format('d/m H:i') }} • {{ $m->product->nama ?? '' }} • {{ $m->tipe }} {{ $m->qty }} ({{ $m->stok_sebelum }}→{{ $m->stok_sesudah }})<br>
            <span class="text-slate-400">{{ $m->keterangan }}</span>
        </div>
        @endforeach
    </details>
</div>
@endif

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
</script>
@endsection
