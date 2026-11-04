@extends('layouts.admin')
@section('title','Laporan')
@section('back', '/admin')
@section('content')
<div class="flex justify-between items-center">
    <h1 class="font-extrabold text-lg flex items-center gap-2">
        <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
        </span>
        <span>Laporan Bubee</span>
    </h1>
    <a href="/admin/laporan?periode={{ $periode }}&tanggal={{ $tanggal }}&minggu={{ $minggu }}&bulan={{ $bulan }}&dari={{ $dari }}&sampai={{ $sampai }}" class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-xl" style="text-decoration:none">↻ Muat ulang</a>
</div>
<div class="text-[11px] text-slate-400 mt-0.5">Diperbarui {{ $generatedAt->format('d M H:i:s') }}</div>

{{-- DROPDOWN FILTER PERIODE DENGAN PANAH KE BAWAH (SEPERTI DI ORDER) --}}
<div class="glass rounded-3xl p-3.5 mt-2.5 shadow-card border border-purple-100">
    <div class="text-[11px] font-bold text-slate-500 mb-1.5 flex items-center justify-between">
        <span>Pilih Periode Laporan:</span>
        <span class="text-[10px] bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full font-bold uppercase">{{ $periode }}</span>
    </div>
    <div class="relative">
        <select id="select-periode" onchange="pilihPeriode(this.value)" class="w-full appearance-none bg-white border-2 border-purple-800 text-purple-950 font-black text-xs rounded-2xl px-3.5 py-2.5 pr-9 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 cursor-pointer">
            <option value="harian" {{ $periode==='harian'?'selected':'' }}>Laporan Harian (Per Tanggal)</option>
            <option value="mingguan" {{ $periode==='mingguan'?'selected':'' }}>Laporan Mingguan (7 Hari)</option>
            <option value="bulanan" {{ $periode==='bulanan'?'selected':'' }}>Laporan Bulanan (Per Bulan)</option>
            <option value="kustom" {{ $periode==='kustom'?'selected':'' }}>Kustom Tanggal (Rentang Bebas)</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-purple-800">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </div>
    </div>

    {{-- KONTROL FILTER SPESIFIK SESUAI PERIODE --}}
    @if($periode === 'harian')
    <form method="GET" action="/admin/laporan" class="flex flex-wrap items-center gap-1.5 mt-2.5">
        <input type="hidden" name="periode" value="harian">
        <input type="date" name="tanggal" value="{{ $tanggal }}" max="{{ now()->toDateString() }}" class="flex-1 min-w-[130px] border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs font-bold" onchange="this.form.submit()">
        <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-xl text-xs">Lihat</button>
        <a href="/admin/laporan?periode=harian&tanggal={{ now()->toDateString() }}" class="px-2.5 py-1.5 rounded-xl text-xs font-bold {{ $tanggal === now()->toDateString() ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700' }}" style="text-decoration:none">Hari Ini</a>
        <a href="/admin/laporan?periode=harian&tanggal={{ now()->subDay()->toDateString() }}" class="px-2.5 py-1.5 rounded-xl text-xs font-bold {{ $tanggal === now()->subDay()->toDateString() ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700' }}" style="text-decoration:none">Kemarin</a>
    </form>
    @elseif($periode === 'mingguan')
    <form method="GET" action="/admin/laporan" class="flex flex-wrap items-center gap-1.5 mt-2.5">
        <input type="hidden" name="periode" value="mingguan">
        <input type="date" name="minggu" value="{{ $minggu }}" max="{{ now()->toDateString() }}" class="flex-1 min-w-[130px] border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs font-bold" onchange="this.form.submit()">
        <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-xl text-xs">Lihat</button>
        <a href="/admin/laporan?periode=mingguan&minggu={{ now()->toDateString() }}" class="px-2.5 py-1.5 rounded-xl text-xs font-bold {{ \Carbon\Carbon::parse($minggu)->isCurrentWeek() ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700' }}" style="text-decoration:none">Minggu Ini</a>
        <a href="/admin/laporan?periode=mingguan&minggu={{ now()->subWeek()->toDateString() }}" class="px-2.5 py-1.5 rounded-xl text-xs font-bold {{ \Carbon\Carbon::parse($minggu)->isSameWeek(now()->subWeek()) ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700' }}" style="text-decoration:none">Minggu Lalu</a>
    </form>
    @elseif($periode === 'bulanan')
    <form method="GET" action="/admin/laporan" class="flex flex-wrap items-center gap-1.5 mt-2.5">
        <input type="hidden" name="periode" value="bulanan">
        <input type="month" name="bulan" value="{{ $bulan }}" max="{{ now()->format('Y-m') }}" class="flex-1 min-w-[130px] border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs font-bold" onchange="this.form.submit()">
        <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-xl text-xs">Lihat</button>
        <a href="/admin/laporan?periode=bulanan&bulan={{ now()->format('Y-m') }}" class="px-2.5 py-1.5 rounded-xl text-xs font-bold {{ $bulan === now()->format('Y-m') ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700' }}" style="text-decoration:none">Bulan Ini</a>
        <a href="/admin/laporan?periode=bulanan&bulan={{ now()->subMonth()->format('Y-m') }}" class="px-2.5 py-1.5 rounded-xl text-xs font-bold {{ $bulan === now()->subMonth()->format('Y-m') ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-700' }}" style="text-decoration:none">Bulan Lalu</a>
    </form>
    @else
    <form method="GET" action="/admin/laporan" class="flex flex-wrap items-center gap-1.5 mt-2.5">
        <input type="hidden" name="periode" value="kustom">
        <input type="date" name="dari" value="{{ $dari }}" max="{{ now()->toDateString() }}" class="flex-1 min-w-[110px] border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs font-bold">
        <span class="text-xs text-slate-400">→</span>
        <input type="date" name="sampai" value="{{ $sampai }}" max="{{ now()->toDateString() }}" class="flex-1 min-w-[110px] border border-slate-300 rounded-xl px-2.5 py-1.5 text-xs font-bold">
        <button type="submit" class="bg-purple-900 text-white font-bold px-3 py-1.5 rounded-xl text-xs">OK</button>
    </form>
    @endif

    {{-- TOMBOL AKSI CETAK & EXPORT CSV --}}
    <div class="flex gap-2 mt-2.5">
        <button onclick="window.print()" class="flex-1 bg-slate-900 text-white font-bold py-2 rounded-xl text-xs shadow-sm inline-flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            <span>Cetak</span>
        </button>
        <a href="/admin/laporan/export?periode={{ $periode }}&tanggal={{ $tanggal }}&minggu={{ $minggu }}&bulan={{ $bulan }}&dari={{ $dari }}&sampai={{ $sampai }}" class="flex-1 text-center bg-emerald-50 text-emerald-700 font-bold py-2 rounded-xl text-xs border border-emerald-200 inline-flex items-center justify-center gap-1.5" style="text-decoration:none">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            <span>Export CSV</span>
        </a>
    </div>
</div>

{{-- BANNER RINGKASAN OMZET --}}
<div class="rounded-3xl p-5 text-white mt-3 shadow-card" style="background:linear-gradient(135deg,#050B2E,#059669)">
    <div class="text-xs opacity-75 font-medium">{{ $labelPeriode }}</div>
    <div class="font-extrabold text-2xl mt-0.5">Rp {{ number_format($ringkas['omzet']) }}</div>
    <div class="text-[11px] opacity-85 mt-1">{{ $ringkas['jml_order'] }} order • {{ $ringkas['pcs'] }} pcs • rata-rata Rp {{ number_format($ringkas['aov']) }}/order</div>
</div>

{{-- 4 KOTAK METRIK CEPAT --}}
<div class="grid grid-cols-2 gap-2 mt-2 text-center text-sm">
    <div class="glass rounded-3xl p-3 shadow-card"><div class="text-[10px] text-slate-500">DISKON VOUCHER</div><div class="font-extrabold text-emerald-600">-Rp {{ number_format($ringkas['diskon']) }}</div></div>
    <div class="glass rounded-3xl p-3 shadow-card"><div class="text-[10px] text-slate-500">RETUR AKTIF</div><div class="font-extrabold text-amber-600">{{ $ringkas['retur_aktif'] }}</div></div>
    <div class="glass rounded-3xl p-3 shadow-card"><div class="text-[10px] text-slate-500">BATAL ({{ $ringkas['batal_jml'] }})</div><div class="font-extrabold text-red-500">Rp {{ number_format($ringkas['batal_nominal']) }}</div></div>
    <div class="glass rounded-3xl p-3 shadow-card"><div class="text-[10px] text-slate-500">NUNGGU BAYAR ({{ $ringkas['tunggu_jml'] }})</div><div class="font-extrabold">Rp {{ number_format($ringkas['tunggu_nominal']) }}</div></div>
</div>

@if($periode === 'harian')
{{-- TAMPILAN LAPORAN HARIAN --}}
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Per Status</b>
    @foreach($perStatus as $s)
    <div class="flex justify-between py-1 border-b border-slate-100 text-xs"><span>{{ $s->status }} ({{ $s->jml }})</span><b>Rp {{ number_format($s->total) }}</b></div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-blue-700">●</span> Per Kurir (lunas)</b>
    @php $totKurir = max(1, $perKurir->sum('total')); @endphp
    @foreach($perKurir as $k)
    <div class="py-1 border-b border-slate-100 text-xs">
        <div class="flex justify-between"><span>{{ $k->kurir }} ({{ $k->jml }})</span><b>Rp {{ number_format($k->total) }}</b></div>
        <div class="progress-line mt-1"><div style="width:{{ round($k->total/$totKurir*100) }}%"></div></div>
    </div>
    @endforeach
    @if($perKurir->isEmpty())<div class="text-xs text-slate-400 py-1">Belum ada order lunas.</div>@endif
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-amber-600">●</span> Produk Terlaris Hari Ini</b>
    @foreach($terlaris as $t)
    <div class="py-1 border-b border-slate-100 text-xs">
        <span class="inline-flex items-center gap-1.5 align-middle">
            @if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="w-5 h-5 rounded bg-purple-50 text-purple-700 inline-flex items-center justify-center text-[10px] shrink-0 font-bold">P</span>@endif
            <span>{{ $t->product->nama ?? '?' }}</span>
        </span> — <b>{{ $t->terjual }} pcs</b> • Rp {{ number_format($t->omzet) }}
    </div>
    @endforeach
    @if($terlaris->isEmpty())<div class="text-xs text-slate-400 py-1">Belum ada penjualan.</div>@endif
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Daftar Order Hari Ini ({{ $rekOrders->count() }})</b>
    @foreach($rekOrders as $o)
    <div class="text-xs py-2 border-b border-blue-50 flex justify-between items-center">
        <div>
            <a href="/admin/order/{{ $o->id }}" class="font-bold text-blue-700" style="text-decoration:none">{{ $o->kode }}</a>
            <div class="text-[11px] text-slate-500">{{ $o->user->name ?? 'Tamu' }} • {{ $o->kurir }} {{ $o->layanan }}</div>
        </div>
        <div class="text-right">
            <b>Rp {{ number_format($o->total_bayar) }}</b>
            <div><span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $o->status==='selesai'?'bg-emerald-100 text-emerald-700':($o->status==='batal'?'bg-red-100 text-red-700':'bg-cyan-100 text-cyan-800') }}">{{ $o->status }}</span></div>
        </div>
    </div>
    @endforeach
    @if($rekOrders->isEmpty())<div class="text-xs text-slate-400 py-2">Tidak ada order tanggal ini.</div>@endif
</div>

@elseif($periode === 'mingguan')
{{-- TAMPILAN LAPORAN MINGGUAN --}}
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Grafik Harian Minggu Ini (7 Hari)</b>
    <div class="text-[11px] text-slate-400 mb-2">Periode {{ $dari }} → {{ $sampai }}</div>
    @foreach($harian as $h)
    <div class="py-1.5 border-b border-slate-100">
        <div class="flex justify-between text-xs">
            <span class="font-bold">{{ $h['hari'] }}, {{ $h['tgl'] }}</span>
            <span class="text-slate-500">{{ $h['order'] }} ord • {{ $h['pcs'] }} pcs</span>
            <b>Rp {{ number_format($h['omzet']) }}</b>
        </div>
        <div class="progress-line mt-1"><div style="width:{{ $maxOmzet>0?round($h['omzet']/$maxOmzet*100):0 }}%"></div></div>
    </div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Per Status</b>
    @foreach($perStatus as $s)
    <div class="flex justify-between py-1 border-b border-slate-100 text-xs"><span>{{ $s->status }} ({{ $s->jml }})</span><b>Rp {{ number_format($s->total) }}</b></div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-blue-700">●</span> Per Kurir (lunas)</b>
    @php $totKurir = max(1, $perKurir->sum('total')); @endphp
    @foreach($perKurir as $k)
    <div class="py-1 border-b border-slate-100 text-xs">
        <div class="flex justify-between"><span>{{ $k->kurir }} ({{ $k->jml }})</span><b>Rp {{ number_format($k->total) }}</b></div>
        <div class="progress-line mt-1"><div style="width:{{ round($k->total/$totKurir*100) }}%"></div></div>
    </div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-amber-600">●</span> Terlaris Top 10 Minggu Ini</b>
    @foreach($terlaris as $t)
    <div class="py-1 border-b border-slate-100 text-xs">
        <span class="inline-flex items-center gap-1.5 align-middle">
            @if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="w-5 h-5 rounded bg-purple-50 text-purple-700 inline-flex items-center justify-center text-[10px] shrink-0 font-bold">P</span>@endif
            <span>{{ $t->product->nama ?? '?' }}</span>
        </span> — <b>{{ $t->terjual }} pcs</b> • Rp {{ number_format($t->omzet) }}
        <div class="progress-line mt-1"><div style="width:{{ min(100,$t->terjual*8) }}%"></div></div>
    </div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Top Pembeli</b>
    @foreach($topPembeli as $t)
    <div class="flex justify-between py-1 border-b border-slate-100 text-xs"><span>{{ $t->user->name ?? '?' }} ({{ $t->jml }} order)</span><b>Rp {{ number_format($t->total) }}</b></div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Daftar Pesanan Minggu Ini ({{ $rekOrders->count() }})</b>
    @foreach($rekOrders as $o)
    <div class="text-xs py-2 border-b border-blue-50 flex justify-between items-center">
        <div>
            <a href="/admin/order/{{ $o->id }}" class="font-bold text-blue-700" style="text-decoration:none">{{ $o->kode }}</a>
            <div class="text-[11px] text-slate-500">{{ $o->user->name ?? 'Tamu' }} • {{ $o->created_at->format('d/m H:i') }}</div>
        </div>
        <div class="text-right">
            <b>Rp {{ number_format($o->total_bayar) }}</b>
            <div><span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $o->status==='selesai'?'bg-emerald-100 text-emerald-700':($o->status==='batal'?'bg-red-100 text-red-700':'bg-cyan-100 text-cyan-800') }}">{{ $o->status }}</span></div>
        </div>
    </div>
    @endforeach
    @if($rekOrders->isEmpty())<div class="text-xs text-slate-400 py-2">Tidak ada order pada minggu ini.</div>@endif
</div>

@else
{{-- TAMPILAN BULANAN & KUSTOM --}}
<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Per Status</b>
    @foreach($perStatus as $s)
    <div class="flex justify-between py-1 border-b border-slate-100 text-xs"><span>{{ $s->status }} ({{ $s->jml }})</span><b>Rp {{ number_format($s->total) }}</b></div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-blue-700">●</span> Per Kurir (lunas)</b>
    <div class="text-[11px] text-slate-400">Periode {{ $dari }} → {{ $sampai }}</div>
    @php $totKurir = max(1, $perKurir->sum('total')); @endphp
    @foreach($perKurir as $k)
    <div class="py-1 border-b border-slate-100 text-xs">
        <div class="flex justify-between"><span>{{ $k->kurir }} ({{ $k->jml }})</span><b>Rp {{ number_format($k->total) }}</b></div>
        <div class="progress-line mt-1"><div style="width:{{ round($k->total/$totKurir*100) }}%"></div></div>
    </div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-amber-600">●</span> Terlaris Top 10</b>
    <div class="text-[11px] text-slate-400">Periode {{ $dari }} → {{ $sampai }}</div>
    @foreach($terlaris as $t)
    <div class="py-1 border-b border-slate-100 text-xs">
        <span class="inline-flex items-center gap-1.5 align-middle">
            @if($t->product?->foto)<img src="/storage/{{ $t->product->foto }}" alt="{{ $t->product->nama ?? 'Barang' }}" class="w-5 h-5 rounded object-cover shrink-0">@else<span class="w-5 h-5 rounded bg-purple-50 text-purple-700 inline-flex items-center justify-center text-[10px] shrink-0 font-bold">P</span>@endif
            <span>{{ $t->product->nama ?? '?' }}</span>
        </span> — <b>{{ $t->terjual }} pcs</b> • Rp {{ number_format($t->omzet) }}
        <div class="progress-line mt-1"><div style="width:{{ min(100,$t->terjual*8) }}%"></div></div>
    </div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Top Pembeli</b>
    @foreach($topPembeli as $t)
    <div class="flex justify-between py-1 border-b border-slate-100 text-xs"><span>{{ $t->user->name ?? '?' }} ({{ $t->jml }} order)</span><b>Rp {{ number_format($t->total) }}</b></div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-pink-600">●</span> Voucher Terpakai</b>
    @foreach($topVoucher as $v)
    <div class="flex justify-between py-1 border-b border-slate-100 text-xs"><span>{{ $v->voucher->kode ?? '?' }} ({{ $v->dipakai }}x)</span><b>-Rp {{ number_format($v->hemat) }}</b></div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Harian</b>
    @foreach($harian as $h)
    <div class="py-1 border-b border-slate-100 text-xs">
        <div class="flex justify-between">
            <b>{{ $h['tgl'] }}</b>
            <span class="text-slate-500">{{ $h['order'] }} ord • {{ $h['pcs'] }} pcs</span>
            <b>Rp {{ number_format($h['omzet']) }}</b>
        </div>
        <div class="progress-line mt-1"><div style="width:{{ $maxOmzet>0?round($h['omzet']/$maxOmzet*100):0 }}%"></div></div>
    </div>
    @endforeach
</div>

<div class="glass rounded-3xl p-4 mt-2 text-sm shadow-card">
    <b class="flex items-center gap-1.5"><span class="text-emerald-700">●</span> Mutasi Stok Terakhir</b>
    @foreach($mutasi as $m)
    <div class="text-xs py-1 border-b border-slate-100">
        {{ $m->created_at->format('d/m H:i') }} • {{ $m->product->nama ?? '' }} • {{ $m->tipe }} {{ $m->qty }} ({{ $m->stok_sebelum }}→{{ $m->stok_sesudah }})<br>
        <span class="text-slate-400">{{ $m->keterangan }}</span>
    </div>
    @endforeach
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
