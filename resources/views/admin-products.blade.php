@extends('layouts.admin')
@section('title','Admin Stok')
@section('back', '/admin')
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Kelola Barang + Varian</h1>
</div>
<div class="text-xs text-slate-500 mb-2">Stok dicatat <b>per ukuran/warna</b>. Total = jumlah semua varian. Semua tercatat di mutasi.</div>

<div class="grid grid-cols-4 gap-1.5 text-center">
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold">{{ $ringkas['sku'] }}</div><div class="text-[9px] text-slate-500">SKU AKTIF</div></div>
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold">{{ $ringkas['pcs'] }}</div><div class="text-[9px] text-slate-500">PCS</div></div>
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold text-[11px] mt-0.5">Rp {{ number_format($ringkas['nilai']) }}</div><div class="text-[9px] text-slate-500">NILAI STOK</div></div>
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold text-red-500">{{ $ringkas['kritis'] + $ringkas['habis'] }}</div><div class="text-[9px] text-slate-500">KRITIS/HABIS</div></div>
</div>

<form method="GET" action="/admin/produk" class="flex gap-1.5 mt-2">
<input name="q" value="{{ $q ?? '' }}" placeholder="Cari nama/SKU…" class="flex-1 border rounded-2xl px-3 py-2 text-xs">
<input type="hidden" name="filter" value="{{ $filter }}">
<input type="hidden" name="view" value="{{ $view ?? 'tabel' }}">
<button class="bg-purple-900 text-white font-bold px-3 rounded-2xl text-xs">Cari</button>
</form>

<div class="flex gap-1.5 text-[11px] font-bold mt-1.5 overflow-x-auto pb-1 items-center">
@foreach(['semua'=>'Semua','kritis'=>'Kritis','habis'=>'Habis','nonaktif'=>'Off'] as $k=>$l)
<a href="/admin/produk?filter={{ $k }}&q={{ $q }}&sort={{ $sort }}&view={{ $view ?? 'tabel' }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($filter??'semua')===$k?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}">{{ $l }}</a>
@endforeach
<form method="GET" action="/admin/produk" class="ml-auto flex-none">
<input type="hidden" name="filter" value="{{ $filter }}">
<input type="hidden" name="q" value="{{ $q }}">
<input type="hidden" name="view" value="{{ $view ?? 'tabel' }}">
<select name="sort" onchange="this.form.submit()" class="rounded-full text-[11px] font-bold px-2 py-1.5 bg-slate-100 text-slate-700 outline-none">
<option value="menipis" {{ $sort==='menipis'?'selected':'' }}>Menipis dulu</option>
<option value="terlaris" {{ $sort==='terlaris'?'selected':'' }}>Terlaris</option>
<option value="nama" {{ $sort==='nama'?'selected':'' }}>Nama A-Z</option>
<option value="stok" {{ $sort==='stok'?'selected':'' }}>Stok terbanyak</option>
</select>
</form>
</div>

{{-- FORM TAMBAH BARANG BARU --}}
<details class="rounded-[1.75rem] p-5 text-white shadow-card mt-2 mb-3" style="background:linear-gradient(135deg,#050B2E,#1D4ED8)">
<summary class="font-extrabold cursor-pointer inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Tambah Barang Baru</summary>
<form method="POST" action="/admin/produk" enctype="multipart/form-data" class="grid grid-cols-2 gap-2 mt-3 text-sm">@csrf
<input name="nama" placeholder="Nama barang *" class="col-span-2 rounded-2xl px-3 py-2.5 text-slate-900" required>
<textarea name="deskripsi" placeholder="Deskripsi barang (bahan, ukuran, warna…)" class="col-span-2 rounded-2xl px-3 py-2.5 text-slate-900" rows="2"></textarea>
<input name="kategori" placeholder="Kategori cth: Kaos (default Fashion)" maxlength="50" class="col-span-2 rounded-2xl px-3 py-2.5 text-slate-900">
<input name="ukuran_list" placeholder="Ukuran koma: S,M,L,XL (kosongkan=All Size)" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="warna" placeholder="Warna cth: Hitam" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="stok_awal" type="number" min="0" value="10" placeholder="Stok awal PER varian" class="rounded-2xl px-3 py-2.5 text-slate-900" required>
<input name="berat_gram" type="number" min="1" value="250" placeholder="Berat (gram)" class="rounded-2xl px-3 py-2.5 text-slate-900" required>
<div class="col-span-2 grid grid-cols-3 gap-2">
<input name="panjang_cm" type="number" min="0" placeholder="P (cm)" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="lebar_cm" type="number" min="0" placeholder="L (cm)" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="tinggi_cm" type="number" min="0" placeholder="T (cm)" class="rounded-2xl px-3 py-2.5 text-slate-900">
</div>
<input name="harga_enduser" type="number" min="0" placeholder="Harga reguler *" class="rounded-2xl px-3 py-2.5 text-slate-900" required>
<input name="harga_reseller" type="number" min="0" placeholder="Harga reseller *" class="rounded-2xl px-3 py-2.5 text-slate-900" required>
<input name="min_reseller" type="number" min="1" value="6" placeholder="Min reseller" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="keterangan" placeholder="Keterangan stok awal" class="rounded-2xl px-3 py-2.5 text-slate-900">
<div class="col-span-2 bg-white/10 rounded-2xl p-2.5">
<label class="text-xs inline-flex items-center gap-1"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>Foto barang (opsional, max 2MB)</label>
<input type="file" name="foto" accept="image/*" class="w-full text-xs mt-1">
<label class="text-xs flex items-center gap-1 mt-1"><input type="checkbox" name="is_active" value="1" checked> Tampilkan di katalog</label>
</div>
<button class="col-span-2 font-extrabold py-3 rounded-2xl text-slate-900 flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#38E1FF,#fff)"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg><span>Simpan Barang</span></button>
</form>
</details>

{{-- TAB PEMISAH: TABEL RINGKASAN VS KARTU KELOLA & EDIT --}}
<div class="flex items-center p-1 bg-slate-100 rounded-2xl shadow-inner mb-3">
    <a href="{{ request()->fullUrlWithQuery(['view' => 'tabel', 'page' => 1]) }}" 
       class="flex-1 py-2 px-2 rounded-xl text-xs font-extrabold text-center transition flex items-center justify-center gap-1.5 {{ ($view ?? 'tabel') === 'tabel' ? 'bg-white text-purple-900 shadow-sm' : 'text-slate-500' }}">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 20v-7"/><path d="M11 20V5"/><path d="M17 20v-10"/><path d="M3 20h18"/></svg> <span>Tabel Ringkasan</span> <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ ($view ?? 'tabel') === 'tabel' ? 'bg-purple-100 text-purple-800' : 'bg-slate-200' }}">{{ $products->total() }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['view' => 'kartu', 'page' => 1]) }}" 
       class="flex-1 py-2 px-2 rounded-xl text-xs font-extrabold text-center transition flex items-center justify-center gap-1.5 {{ ($view ?? 'tabel') === 'kartu' ? 'bg-white text-purple-900 shadow-sm' : 'text-slate-500' }}">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg> <span>Kartu Kelola & Edit</span> <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ ($view ?? 'tabel') === 'kartu' ? 'bg-purple-100 text-purple-800' : 'bg-slate-200' }}">{{ $products->total() }}</span>
    </a>
</div>

<form method="POST" action="/admin/produk/arsip-massal" id="bulkarsip" onsubmit="return confirm('Arsipkan (nonaktifkan) barang terpilih?')">@csrf</form>

@if(($view ?? 'tabel') === 'tabel')
    {{-- BAGIAN 1: TABEL RINGKASAN MOBILE --}}
    <div class="flex justify-between items-center mb-2">
        <button form="bulkarsip" class="bg-slate-100 text-slate-700 font-bold py-1.5 px-3 rounded-xl text-[11px] inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg><span>Arsipkan dipilih</span></button>
        <span class="text-[11px] text-slate-500 font-medium">Hal {{ $products->currentPage() }}/{{ $products->lastPage() }}</span>
    </div>

    <div class="glass rounded-3xl shadow-card overflow-x-auto mb-2">
        <table class="w-full text-xs">
            <thead>
                <tr class="text-left text-[10px] text-slate-400 border-b border-slate-100">
                    <th class="p-2 text-center">Pilih</th>
                    <th class="p-2">Barang</th>
                    <th class="p-2 text-center">Stok</th>
                    <th class="p-2 text-right">Reseller</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr class="border-t border-slate-100">
                    <td class="p-2 text-center">
                        <input type="checkbox" name="ids[]" value="{{ $p->id }}" form="bulkarsip" class="w-4 h-4 rounded">
                    </td>
                    <td class="p-2">
                        <div class="font-bold text-slate-900 leading-tight">{{ $p->nama }}</div>
                        <div class="text-[10px] text-slate-400">{{ $p->kategori }} • {{ $p->variants->count() }} var</div>
                        <div class="text-[10px] mt-0.5 flex items-center">
                            @if($p->sinyal==='habis')<span class="text-red-600 font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Habis — restock sekarang</span>
                            @elseif($p->sinyal==='segera')<span class="text-amber-600 font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>±{{ $p->hari_habis }} hari — restock segera</span>
                            @elseif($p->sinyal==='kritis')<span class="text-amber-600 font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Stok kritis</span>
                            @elseif($p->sinyal==='diam')<span class="text-slate-400 inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>Tidak laku 30 hari</span>
                            @else<span class="text-emerald-600 font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aman • laku {{ $p->terjual30 }} pcs/30 hari</span>
                            @endif
                        </div>
                    </td>
                    <td class="p-2 text-center">
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ $p->stok<=0?'bg-red-500 text-white':($p->stok<=10?'bg-amber-300 text-amber-900':'bg-emerald-300 text-emerald-950') }}">{{ $p->stok }}</span>
                    </td>
                    <td class="p-2 text-right font-bold text-slate-900 whitespace-nowrap">
                        Rp {{ number_format($p->harga_reseller) }}
                    </td>
                    <td class="p-2 text-center whitespace-nowrap">
                        <form method="POST" action="/admin/produk/{{ $p->id }}/toggle" class="inline">
                            @csrf
                            <button type="submit" 
                                    title="{{ $p->is_active ? 'Klik untuk OFF' : 'Klik untuk ON' }}"
                                    class="px-2 py-0.5 rounded-full text-[10px] font-black border transition active:scale-95 cursor-pointer {{ $p->is_active ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-200 text-slate-700 border-slate-300' }}">
                                {{ $p->is_active ? 'ON' : 'OFF' }}
                            </button>
                        </form>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'kartu', 'q' => $p->nama]) }}" class="px-2 py-1 bg-purple-50 text-purple-700 font-bold rounded-lg text-[10px] ml-1 inline-flex items-center gap-1">
                            <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            <span>Edit</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-6 text-center text-xs text-slate-400">Tidak ada barang yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Navigasi Panah Samping (Pagination) --}}
    <div class="mt-3">{{ $products->links() }}</div>

@else
    {{-- BAGIAN 2: KARTU KELOLA & EDIT MOBILE --}}
    <div class="mb-2">
        <button form="bulkarsip" class="w-full bg-slate-100 text-slate-600 font-bold py-2.5 rounded-2xl text-xs inline-flex items-center justify-center gap-1.5"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg><span>Arsipkan yang dicentang</span></button>
    </div>

    @forelse($products as $p)
    <div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
        <div class="flex gap-2 items-center">
            <input type="checkbox" name="ids[]" value="{{ $p->id }}" form="bulkarsip" class="w-5 h-5 flex-none rounded" title="Pilih untuk arsip massal">
            @if($p->foto)
                <img src="/storage/{{ $p->foto }}" alt="{{ $p->nama }}" class="w-12 h-12 rounded-2xl object-cover bg-slate-100">
            @else
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-none" style="background:linear-gradient(180deg,#D8E6FF,#fff)">👕</div>
            @endif
            <div class="flex-1 min-w-0">
                <b class="truncate block">{{ $p->nama }}</b>
                <div class="text-[11px] text-slate-500 truncate">{{ $p->deskripsi ? \Str::limit($p->deskripsi,60) : 'Belum ada deskripsi' }}</div>
                <form method="POST" action="/admin/produk/{{ $p->id }}/duplikat" class="inline" onsubmit="return confirm('Duplikat {{ $p->nama }}? (varian+tier ikut, stok 0, nonaktif)')">
                    @csrf
                    <button class="text-[10px] text-purple-700 font-bold underline">⧉ Duplikat</button>
                </form>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p->stok<=0?'bg-red-500 text-white':($p->stok<=10?'bg-amber-300':'bg-emerald-300') }}">Σ {{ $p->stok }}</span>
            <form method="POST" action="/admin/produk/{{ $p->id }}/toggle" class="inline">
                @csrf
                <button type="submit" 
                        title="{{ $p->is_active ? 'Klik untuk OFF' : 'Klik untuk ON' }}"
                        class="px-2 py-0.5 rounded-full text-[10px] font-black border transition active:scale-95 cursor-pointer {{ $p->is_active ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-200 text-slate-700 border-slate-300' }}">
                    {{ $p->is_active ? 'ON' : 'OFF' }}
                </button>
            </form>
        </div>

        <div class="text-[11px] mt-1.5">
            @if($p->sinyal==='habis')<span class="font-bold text-red-600 inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Habis — restock sekarang</span>
            @elseif($p->sinyal==='segera')<span class="font-bold text-amber-600 inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>±{{ $p->hari_habis }} hari habis — restock segera</span>
            @elseif($p->sinyal==='kritis')<span class="font-bold text-amber-600 inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Stok kritis</span>
            @elseif($p->sinyal==='diam')<span class="text-slate-400 inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>Tidak laku 30 hari</span>
            @else<span class="text-emerald-600 font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aman • laku {{ $p->terjual30 }} pcs/30 hari</span>
            @endif
            @if($p->terjual30>0 && $p->sinyal!=='aman')<span class="text-slate-400"> • laku {{ $p->terjual30 }} pcs/30 hari</span>@endif
        </div>

        {{-- VARIAN --}}
        <div class="mt-2 rounded-2xl bg-purple-50/70 border border-purple-100 p-2.5">
            <div class="text-[11px] font-extrabold text-purple-900 mb-1.5 flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg><span>VARIAN ({{ $p->variants->count() }})</span></div>
            @foreach($p->variants as $v)
            <form method="POST" action="/admin/varian/{{ $v->id }}" class="flex items-center gap-1.5 py-1 border-b border-purple-100 last:border-0">
                @csrf @method('PUT')
                <span class="text-xs font-bold flex-1">{{ $v->label() }} <span class="text-[10px] text-slate-400">{{ $v->sku }}</span></span>
                <input name="stok" type="number" min="0" value="{{ $v->stok }}" class="w-16 border rounded-xl px-2 py-1 text-xs font-bold text-center">
                <button class="text-[11px] bg-purple-900 text-white font-bold px-2.5 py-1 rounded-xl">OK</button>
            </form>
            @endforeach
            <form method="POST" action="/admin/produk/{{ $p->id }}/varian" class="flex gap-1 mt-1.5">
                @csrf
                <input name="ukuran" placeholder="Ukuran" maxlength="20" class="flex-1 border rounded-xl px-2 py-1.5 text-xs" required>
                <input name="warna" placeholder="Warna" maxlength="30" class="flex-1 border rounded-xl px-2 py-1.5 text-xs">
                <input name="stok" type="number" min="0" value="0" class="w-16 border rounded-xl px-2 py-1.5 text-xs text-center">
                <button class="text-[11px] bg-emerald-500 text-white font-bold px-2.5 rounded-xl">+ Varian</button>
            </form>
            @foreach($p->variants as $v)
            <form method="POST" action="/admin/varian/{{ $v->id }}" onsubmit="return confirm('Hapus varian {{ $v->label() }}? (gagal jika sudah pernah terjual)')">
                @csrf @method('DELETE')
                <button class="text-[10px] text-red-400 underline">hapus {{ $v->label() }}</button>
            </form>
            @endforeach
        </div>

        {{-- TIER HARGA --}}
        <div class="mt-2 rounded-2xl bg-emerald-50/70 border border-emerald-100 p-2.5">
            <div class="text-[11px] font-extrabold text-emerald-900 mb-1.5 flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/><path d="M12 6v12M15 9.5a3.5 3.5 0 0 0-5 0c0 2 3 2.5 3 4.5 0 1.2-1 2-2.5 2"/></svg><span>TIER HARGA (level + min qty)</span></div>
            @foreach($p->prices as $t)
            <div class="flex items-center gap-1.5 py-1 border-b border-emerald-100 last:border-0 text-xs">
                <span class="font-bold flex-1">{{ $t->level === 'reseller' ? 'Reseller' : 'Reguler' }} <span class="text-slate-400">min {{ $t->min_qty }} pcs</span></span>
                <span class="font-extrabold">Rp {{ number_format($t->price) }}</span>
                <form method="POST" action="/admin/tier/{{ $t->id }}" onsubmit="return confirm('Hapus tier ini?')">
                    @csrf @method('DELETE')
                    <button class="text-red-400 font-bold px-1">×</button>
                </form>
            </div>
            @endforeach
            <form method="POST" action="/admin/produk/{{ $p->id }}/tier" class="flex gap-1 mt-1.5">
                @csrf
                <select name="level" class="border rounded-xl px-2 py-1.5 text-xs font-bold"><option value="reseller">Reseller</option><option value="user">Reguler</option></select>
                <input name="min_qty" type="number" min="1" value="12" placeholder="Min" class="w-16 border rounded-xl px-2 py-1.5 text-xs text-center" required>
                <input name="price" type="number" min="0" placeholder="Rp" class="flex-1 border rounded-xl px-2 py-1.5 text-xs" required>
                <button class="text-[11px] bg-emerald-500 text-white font-bold px-2.5 rounded-xl">+ Tier</button>
            </form>
        </div>

        {{-- EDIT PRODUK --}}
        <form method="POST" action="/admin/produk/{{ $p->id }}" enctype="multipart/form-data" class="mt-2 space-y-1.5">
            @csrf @method('PUT')
            <details>
                <summary class="text-xs font-bold text-purple-700 cursor-pointer select-none inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg><span>Ubah info, harga & foto</span></summary>
                <div class="space-y-1.5 mt-1.5">
                    <input name="nama" value="{{ $p->nama }}" class="w-full border rounded-xl px-2 py-1.5 font-bold">
                    <div class="flex gap-1.5">
                        <textarea name="deskripsi" rows="2" placeholder="Deskripsi barang…" class="flex-[2] border rounded-xl px-2 py-1.5 text-xs">{{ $p->deskripsi }}</textarea>
                        <input name="kategori" value="{{ $p->kategori }}" placeholder="Kategori" maxlength="50" class="flex-1 border rounded-xl px-2 py-1.5 text-xs">
                    </div>
                    <div class="grid grid-cols-3 gap-1.5">
                        <label class="text-[11px]">Berat(gr)<input name="berat_gram" type="number" value="{{ $p->berat_gram }}" class="w-full border rounded-xl px-2 py-1.5"></label>
                        <label class="text-[11px]">P(cm)<input name="panjang_cm" type="number" min="0" value="{{ $p->panjang_cm }}" class="w-full border rounded-xl px-2 py-1.5"></label>
                        <label class="text-[11px]">L(cm)<input name="lebar_cm" type="number" min="0" value="{{ $p->lebar_cm }}" class="w-full border rounded-xl px-2 py-1.5"></label>
                        <label class="text-[11px]">T(cm)<input name="tinggi_cm" type="number" min="0" value="{{ $p->tinggi_cm }}" class="w-full border rounded-xl px-2 py-1.5"></label>
                        <label class="text-[11px]">Min Rslr<input name="min_reseller" type="number" value="{{ $p->min_reseller }}" class="w-full border rounded-xl px-2 py-1.5"></label>
                        <label class="text-[11px]">Tampil<select name="is_active" class="w-full border rounded-xl px-2 py-1.5"><option value="1" {{ $p->is_active?'selected':'' }}>Ya</option><option value="0" {{ !$p->is_active?'selected':'' }}>Tidak</option></select></label>
                        <label class="text-[11px]">Reguler<input name="harga_enduser" type="number" value="{{ $p->harga_enduser }}" class="w-full border rounded-xl px-2 py-1.5"></label>
                        <label class="text-[11px]">Reseller<input name="harga_reseller" type="number" value="{{ $p->harga_reseller }}" class="w-full border rounded-xl px-2 py-1.5"></label>
                        <label class="text-[11px]">Foto<input type="file" name="foto" accept="image/*" class="w-full text-[10px]"></label>
                    </div>
                    <button class="w-full text-white font-bold py-2.5 rounded-2xl text-xs" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">SIMPAN</button>
                </div>
            </details>
        </form>
        <form method="POST" action="/admin/produk/{{ $p->id }}" onsubmit="return confirm('Hapus/nonaktifkan {{ $p->nama }}?')">
            @csrf @method('DELETE')
            <button class="w-full mt-1 text-red-400 hover:text-red-600 text-[11px] underline">Hapus / nonaktifkan barang</button>
        </form>
    </div>
    @empty
    <div class="glass rounded-3xl p-8 text-center text-sm text-slate-400">Tidak ada barang yang cocok.</div>
    @endforelse

    {{-- Navigasi Panah Samping (Pagination) --}}
    <div class="mt-3">{{ $products->links() }}</div>
@endif

@endsection
