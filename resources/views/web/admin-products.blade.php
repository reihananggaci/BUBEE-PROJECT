@extends('layouts.web-admin')
@section('title','Admin Stok')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/></svg>
    </span>
    <span>Kelola Barang + Varian</span>
</h1>
<div class="text-xs text-slate-500 mb-2 mt-0.5">Stok dicatat <b>per ukuran/warna</b>. Total = jumlah semua varian. Semua tercatat di mutasi.</div>

<div class="grid grid-cols-2 gap-2 lg:grid-cols-4 text-center mb-3">
<div class="bg-white rounded-3xl shadow-card p-3 border border-slate-100"><div class="font-extrabold text-xl">{{ $ringkas['sku'] }}</div><div class="text-[10px] text-slate-500">SKU AKTIF</div></div>
<div class="bg-white rounded-3xl shadow-card p-3 border border-slate-100"><div class="font-extrabold text-xl">{{ $ringkas['pcs'] }}</div><div class="text-[10px] text-slate-500">TOTAL PCS</div></div>
<div class="bg-white rounded-3xl shadow-card p-3 border border-slate-100"><div class="font-extrabold text-base mt-1">Rp {{ number_format($ringkas['nilai']) }}</div><div class="text-[10px] text-slate-500">NILAI STOK</div></div>
<div class="bg-white rounded-3xl shadow-card p-3 border border-slate-100"><div class="font-extrabold text-xl text-red-500">{{ $ringkas['kritis'] + $ringkas['habis'] }}</div><div class="text-[10px] text-slate-500">KRITIS/HABIS</div></div>
</div>

<form method="GET" action="/admin/produk" class="flex gap-2 mb-2 max-w-2xl">
<div class="relative flex-1">
    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </span>
    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari nama atau SKU…" class="w-full border rounded-2xl pl-10 pr-3 py-2 text-sm">
</div>
<input type="hidden" name="filter" value="{{ $filter }}">
<input type="hidden" name="view" value="{{ $view ?? 'tabel' }}">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
<select name="sort" onchange="this.form.submit()" class="border rounded-2xl px-3 py-2 text-sm font-bold bg-white text-slate-800">
<option value="menipis" {{ $sort==='menipis'?'selected':'' }}>Menipis dulu</option>
<option value="terlaris" {{ $sort==='terlaris'?'selected':'' }}>Terlaris</option>
<option value="nama" {{ $sort==='nama'?'selected':'' }}>Nama A-Z</option>
<option value="stok" {{ $sort==='stok'?'selected':'' }}>Stok terbanyak</option>
</select>
</form>

<div class="flex gap-1.5 text-[12px] font-bold mb-3">
@foreach(['semua'=>'Semua','kritis'=>'Kritis','habis'=>'Habis','nonaktif'=>'Off'] as $k=>$l)
<a href="/admin/produk?filter={{ $k }}&q={{ $q }}&sort={{ $sort }}&view={{ $view ?? 'tabel' }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($filter??'semua')===$k?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">{{ $l }}</a>
@endforeach
</div>

<details class="rounded-3xl p-5 text-white shadow-card mb-4" style="background:linear-gradient(135deg,#050B2E,#1D4ED8)">
<summary class="font-extrabold cursor-pointer flex items-center gap-2">
    <svg class="w-4 h-4 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
    <span>Tambah Barang Baru</span>
</summary>
<form method="POST" action="/admin/produk" enctype="multipart/form-data" class="grid grid-cols-2 gap-2 mt-3 text-sm lg:grid-cols-4">@csrf
<input name="nama" placeholder="Nama barang *" class="col-span-2 lg:col-span-4 rounded-2xl px-3 py-2.5 text-slate-900" required>
<textarea name="deskripsi" placeholder="Deskripsi barang (bahan, ukuran, warna…)" class="col-span-2 lg:col-span-4 rounded-2xl px-3 py-2.5 text-slate-900" rows="2"></textarea>
<input name="kategori" placeholder="Kategori cth: Kaos (default Fashion)" maxlength="50" class="col-span-2 rounded-2xl px-3 py-2.5 text-slate-900">
<input name="ukuran_list" placeholder="Ukuran koma: S,M,L,XL (kosongkan=All Size)" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="warna" placeholder="Warna cth: Hitam" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="stok_awal" type="number" min="0" value="10" placeholder="Stok awal PER varian" class="rounded-2xl px-3 py-2.5 text-slate-900" required>
<input name="berat_gram" type="number" min="1" value="250" placeholder="Berat (gram)" class="rounded-2xl px-3 py-2.5 text-slate-900" required>
<input name="panjang_cm" type="number" min="0" placeholder="P (cm)" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="lebar_cm" type="number" min="0" placeholder="L (cm)" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="tinggi_cm" type="number" min="0" placeholder="T (cm)" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="harga_enduser" type="number" min="0" placeholder="Harga reguler *" class="rounded-2xl px-3 py-2.5 text-slate-900" required>
<input name="harga_reseller" type="number" min="0" placeholder="Harga reseller *" class="rounded-2xl px-3 py-2.5 text-slate-900" required>
<input name="min_reseller" type="number" min="1" value="6" placeholder="Min reseller" class="rounded-2xl px-3 py-2.5 text-slate-900">
<input name="keterangan" placeholder="Keterangan stok awal" class="rounded-2xl px-3 py-2.5 text-slate-900">
<div class="col-span-2 lg:col-span-4 bg-white/10 rounded-2xl p-2.5">
<label class="text-xs flex items-center gap-1.5"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg> Foto barang (opsional, max 2MB)</label>
<input type="file" name="foto" accept="image/*" class="w-full text-xs mt-1">
<label class="text-xs flex items-center gap-1 mt-1"><input type="checkbox" name="is_active" value="1" checked> Tampilkan di katalog</label>
</div>
<button class="col-span-2 lg:col-span-4 font-extrabold py-3 rounded-2xl text-slate-900" style="background:linear-gradient(90deg,#38E1FF,#fff)">SIMPAN BARANG</button>
</form>
</details>

{{-- TAB PEMISAH: TABEL RINGKASAN VS KARTU KELOLA & EDIT --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
    <div class="inline-flex p-1 bg-slate-100 rounded-2xl max-w-md w-full shadow-inner">
        <a href="{{ request()->fullUrlWithQuery(['view' => 'tabel', 'page' => 1]) }}" 
           class="flex-1 py-2 px-3 rounded-xl text-xs font-extrabold text-center transition flex items-center justify-center gap-1.5 {{ ($view ?? 'tabel') === 'tabel' ? 'bg-white text-purple-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}"
           style="text-decoration:none">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
            <span>Tabel Ringkasan</span>
            <span class="text-[10px] px-2 py-0.5 rounded-full {{ ($view ?? 'tabel') === 'tabel' ? 'bg-purple-100 text-purple-800 font-black' : 'bg-slate-200 text-slate-600' }}">{{ $products->total() }}</span>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['view' => 'kartu', 'page' => 1]) }}" 
           class="flex-1 py-2 px-3 rounded-xl text-xs font-extrabold text-center transition flex items-center justify-center gap-1.5 {{ ($view ?? 'tabel') === 'kartu' ? 'bg-white text-purple-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}"
           style="text-decoration:none">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            <span>Kartu Kelola & Edit</span>
            <span class="text-[10px] px-2 py-0.5 rounded-full {{ ($view ?? 'tabel') === 'kartu' ? 'bg-purple-100 text-purple-800 font-black' : 'bg-slate-200 text-slate-600' }}">{{ $products->total() }}</span>
        </a>
    </div>
    <div class="text-xs text-slate-500 font-medium">
        Total: <b>{{ $products->total() }}</b> barang • Halaman <b>{{ $products->currentPage() }}</b> dari <b>{{ $products->lastPage() }}</b>
    </div>
</div>

<form method="POST" action="/admin/produk/arsip-massal" id="bulkarsip" onsubmit="return confirm('Arsipkan (nonaktifkan) barang terpilih?')">@csrf</form>

@if(($view ?? 'tabel') === 'tabel')
    {{-- BAGIAN 1: TABEL RINGKASAN --}}
    <div class="flex justify-between items-center mb-2">
        <button form="bulkarsip" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3.5 rounded-xl text-xs transition">Arsipkan yang dicentang</button>
        <span class="text-xs text-slate-400">Klik "Kelola" untuk ubah varian/harga</span>
    </div>

    <div class="bg-white rounded-3xl shadow-card overflow-x-auto mb-3">
        <table class="w-full text-sm lg:table">
            <thead>
                <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                    <th class="px-3 py-2 text-center w-8">Pilih</th>
                    <th class="px-4 py-2">Barang</th>
                    <th class="px-4 py-2">Kategori</th>
                    <th class="px-4 py-2 text-center">Stok Σ</th>
                    <th class="px-4 py-2">Sinyal</th>
                    <th class="px-4 py-2 text-right">Enduser</th>
                    <th class="px-4 py-2 text-right">Reseller</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr class="border-t border-slate-100 hover:bg-slate-50/50 transition">
                    <td class="px-3 py-2 text-center">
                        <input type="checkbox" name="ids[]" value="{{ $p->id }}" form="bulkarsip" class="w-4 h-4 rounded">
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            @if($p->foto)
                                <img src="/storage/{{ $p->foto }}" class="w-9 h-9 rounded-xl object-cover flex-none bg-slate-100" alt="">
                            @else
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-none" style="background:linear-gradient(180deg,#D8E6FF,#fff)">👕</div>
                            @endif
                            <div>
                                <div class="font-bold text-slate-900 leading-tight">{{ $p->nama }}</div>
                                <div class="text-[11px] text-slate-400">{{ $p->variants->count() }} varian</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-xs text-slate-600">{{ $p->kategori }}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $p->stok<=0?'bg-red-500 text-white':($p->stok<=10?'bg-amber-300 text-amber-900':'bg-emerald-300 text-emerald-950') }}">Σ {{ $p->stok }}</span>
                    </td>
                    <td class="px-4 py-2 text-xs">
                        @if($p->sinyal==='habis')<span class="inline-flex items-center gap-1.5 font-bold text-red-600"><span class="w-2 h-2 rounded-full bg-red-500"></span><span>Habis — restock</span></span>
                        @elseif($p->sinyal==='segera')<span class="inline-flex items-center gap-1.5 font-bold text-amber-600"><span class="w-2 h-2 rounded-full bg-amber-500"></span><span>±{{ $p->hari_habis }} hari habis</span></span>
                        @elseif($p->sinyal==='kritis')<span class="inline-flex items-center gap-1.5 font-bold text-amber-600"><span class="w-2 h-2 rounded-full bg-amber-500"></span><span>Stok kritis</span></span>
                        @elseif($p->sinyal==='diam')<span class="inline-flex items-center gap-1.5 text-slate-400"><span class="w-2 h-2 rounded-full bg-slate-300"></span><span>Tidak laku 30 hari</span></span>
                        @else<span class="inline-flex items-center gap-1.5 font-bold text-emerald-600"><span class="w-2 h-2 rounded-full bg-emerald-500"></span><span>Aman • {{ $p->terjual30 }} pcs</span></span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-right font-medium">Rp {{ number_format($p->harga_enduser) }}</td>
                    <td class="px-4 py-2 text-right font-bold text-slate-900">Rp {{ number_format($p->harga_reseller) }}</td>
                    <td class="px-4 py-2 text-center whitespace-nowrap">
                        <form method="POST" action="/admin/produk/{{ $p->id }}/toggle" class="inline">
                            @csrf
                            <button type="submit" 
                                    title="{{ $p->is_active ? 'Status aktif: Klik untuk OFF-kan barang' : 'Status nonaktif: Klik untuk ON-kan barang' }}"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-black transition-all shadow-sm active:scale-95 cursor-pointer border {{ $p->is_active ? 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800 border-emerald-300' : 'bg-slate-200 hover:bg-slate-300 text-slate-700 border-slate-300' }}">
                                <span class="w-2 h-2 rounded-full {{ $p->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                <span>{{ $p->is_active ? 'ON' : 'OFF' }}</span>
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-2 text-center whitespace-nowrap">
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'kartu', 'q' => $p->nama]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold rounded-xl transition" style="text-decoration:none" title="Buka kartu untuk kelola varian, stok, tier, foto">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                            <span>Kelola</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="p-8 text-center text-sm text-slate-400">Tidak ada barang yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Navigasi Panah Samping (Pagination) --}}
    <div class="mt-4">{{ $products->links() }}</div>

@else
    {{-- BAGIAN 2: KARTU KELOLA & EDIT --}}
    <div class="mb-3">
        <button form="bulkarsip" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-2xl text-xs transition">Arsipkan yang dicentang</button>
    </div>

    <div class="grid gap-3 lg:grid-cols-2 lg:items-start">
        @forelse($products as $p)
        <div class="bg-white rounded-3xl shadow-card p-4 text-sm border border-slate-100">
            <div class="flex gap-2 items-center">
                <input type="checkbox" name="ids[]" value="{{ $p->id }}" form="bulkarsip" class="w-5 h-5 flex-none rounded" title="Pilih untuk arsip massal">
                @if($p->foto)
                    <img src="/storage/{{ $p->foto }}" class="w-12 h-12 rounded-2xl object-cover bg-slate-100" alt="">
                @else
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-none" style="background:linear-gradient(180deg,#D8E6FF,#fff)">👕</div>
                @endif
                <div class="flex-1 min-w-0">
                    <b class="text-slate-900 truncate block">{{ $p->nama }}</b>
                    <div class="text-[11px] text-slate-500 truncate">{{ $p->deskripsi ? \Str::limit($p->deskripsi,60) : 'Belum ada deskripsi' }}</div>
                    <form method="POST" action="/admin/produk/{{ $p->id }}/duplikat" class="inline" onsubmit="return confirm('Duplikat {{ $p->nama }}? (varian+tier ikut, stok 0, nonaktif)')">
                        @csrf
                        <button class="text-[10px] text-purple-700 font-bold underline">Duplikat</button>
                    </form>
                </div>
                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $p->stok<=0?'bg-red-500 text-white':($p->stok<=10?'bg-amber-300 text-amber-900':'bg-emerald-300 text-emerald-950') }}">Σ {{ $p->stok }}</span>
                <form method="POST" action="/admin/produk/{{ $p->id }}/toggle" class="inline">
                    @csrf
                    <button type="submit" 
                            title="{{ $p->is_active ? 'Status aktif: Klik untuk OFF-kan barang' : 'Status nonaktif: Klik untuk ON-kan barang' }}"
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black transition shadow-sm active:scale-95 cursor-pointer border {{ $p->is_active ? 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800 border-emerald-300' : 'bg-slate-200 hover:bg-slate-300 text-slate-700 border-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $p->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                        <span>{{ $p->is_active ? 'ON' : 'OFF' }}</span>
                    </button>
                </form>
            </div>

            <div class="text-[11px] mt-1.5 flex items-center gap-1.5">
                @if($p->sinyal==='habis')<span class="font-bold text-red-600 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Habis — restock sekarang</span>
                @elseif($p->sinyal==='segera')<span class="font-bold text-amber-600 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> ±{{ $p->hari_habis }} hari habis — restock segera</span>
                @elseif($p->sinyal==='kritis')<span class="font-bold text-amber-600 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Stok kritis</span>
                @elseif($p->sinyal==='diam')<span class="text-slate-400 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Tidak laku 30 hari</span>
                @else<span class="text-emerald-600 font-bold flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aman • laku {{ $p->terjual30 }} pcs/30 hari</span>
                @endif
            </div>

            {{-- Kelola Varian --}}
            <div class="mt-2 rounded-2xl bg-purple-50/70 border border-purple-100 p-2.5">
                <div class="text-[11px] font-extrabold text-purple-900 mb-1.5">VARIAN ({{ $p->variants->count() }})</div>
                @foreach($p->variants as $v)
                <form method="POST" action="/admin/varian/{{ $v->id }}" class="flex items-center gap-1.5 py-1 border-b border-purple-100 last:border-0">
                    @csrf @method('PUT')
                    <span class="text-xs font-bold flex-1 text-slate-800">{{ $v->label() }} <span class="text-[10px] text-slate-400">{{ $v->sku }}</span></span>
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

            {{-- Kelola Tier Harga --}}
            <div class="mt-2 rounded-2xl bg-emerald-50/70 border border-emerald-100 p-2.5">
                <div class="text-[11px] font-extrabold text-emerald-900 mb-1.5">TIER HARGA (level + min qty)</div>
                @foreach($p->prices as $t)
                <div class="flex items-center gap-1.5 py-1 border-b border-emerald-100 last:border-0 text-xs">
                    <span class="font-bold flex-1 text-slate-800">{{ $t->level === 'reseller' ? 'Reseller' : 'Reguler' }} <span class="text-slate-400">min {{ $t->min_qty }} pcs</span></span>
                    <span class="font-extrabold text-slate-900">Rp {{ number_format($t->price) }}</span>
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

            {{-- Ubah Info, Harga & Foto --}}
            <form method="POST" action="/admin/produk/{{ $p->id }}" enctype="multipart/form-data" class="mt-2 space-y-1.5">
                @csrf @method('PUT')
                <details>
                    <summary class="text-xs font-bold text-purple-700 cursor-pointer select-none">Ubah info, harga & foto</summary>
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
        <div class="col-span-2 bg-white rounded-3xl p-8 text-center text-sm text-slate-400">Tidak ada barang yang cocok.</div>
        @endforelse
    </div>

    {{-- Navigasi Panah Samping (Pagination) --}}
    <div class="mt-4">{{ $products->links() }}</div>
@endif

@endsection
