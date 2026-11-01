@extends('layouts.web-admin')
@section('title','Admin Stok')
@section('content')
<h1 class="font-extrabold text-2xl">📦 Kelola Barang + Varian</h1>
<div class="text-xs text-slate-500 mb-2">Stok dicatat <b>per ukuran/warna</b>. Total = jumlah semua varian. Semua tercatat di mutasi.</div>
<div class="grid grid-cols-2 gap-2 lg:grid-cols-4 text-center mb-3">
<div class="bg-white rounded-3xl shadow-card p-3"><div class="font-extrabold text-xl">{{ $ringkas['sku'] }}</div><div class="text-[10px] text-slate-500">SKU AKTIF</div></div>
<div class="bg-white rounded-3xl shadow-card p-3"><div class="font-extrabold text-xl">{{ $ringkas['pcs'] }}</div><div class="text-[10px] text-slate-500">TOTAL PCS</div></div>
<div class="bg-white rounded-3xl shadow-card p-3"><div class="font-extrabold text-base mt-1">Rp {{ number_format($ringkas['nilai']) }}</div><div class="text-[10px] text-slate-500">NILAI STOK</div></div>
<div class="bg-white rounded-3xl shadow-card p-3"><div class="font-extrabold text-xl text-red-500">{{ $ringkas['kritis'] + $ringkas['habis'] }}</div><div class="text-[10px] text-slate-500">KRITIS/HABIS</div></div>
</div>
<form method="GET" action="/admin/produk" class="flex gap-2 mb-2 max-w-2xl">
<input name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari nama/SKU…" class="flex-1 border rounded-2xl px-4 py-2 text-sm">
<input type="hidden" name="filter" value="{{ $filter }}"><input type="hidden" name="sort" value="{{ $sort }}">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
<select name="sort" onchange="this.form.submit()" class="border rounded-2xl px-3 py-2 text-sm font-bold">
<option value="menipis" {{ $sort==='menipis'?'selected':'' }}>Menipis dulu</option>
<option value="terlaris" {{ $sort==='terlaris'?'selected':'' }}>Terlaris</option>
<option value="nama" {{ $sort==='nama'?'selected':'' }}>Nama A-Z</option>
<option value="stok" {{ $sort==='stok'?'selected':'' }}>Stok terbanyak</option>
</select>
</form>
<div class="flex gap-1.5 text-[12px] font-bold mb-3">
@foreach(['semua'=>'Semua','kritis'=>'Kritis','habis'=>'Habis','nonaktif'=>'Off'] as $k=>$l)
<a href="/admin/produk?filter={{ $k }}&q={{ $q }}&sort={{ $sort }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($filter??'semua')===$k?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">{{ $l }}</a>
@endforeach
</div>
<details class="rounded-3xl p-5 text-white shadow-card" style="background:linear-gradient(135deg,#050B2E,#1D4ED8)">
<summary class="font-extrabold cursor-pointer">➕ Tambah Barang Baru</summary>
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
<label class="text-xs">📷 Foto barang (opsional, max 2MB)</label>
<input type="file" name="foto" accept="image/*" class="w-full text-xs mt-1">
<label class="text-xs flex items-center gap-1 mt-1"><input type="checkbox" name="is_active" value="1" checked> Tampilkan di katalog</label>
</div>
<button class="col-span-2 lg:col-span-4 font-extrabold py-3 rounded-2xl text-slate-900" style="background:linear-gradient(90deg,#38E1FF,#fff)">SIMPAN BARANG 🚀</button>
</form>
</details>

<div class="font-extrabold text-sm mt-4 mb-2">📋 Semua Barang ({{ $products->count() }})</div>
{{-- Ringkasan tabel desktop --}}
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto mb-3">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Barang</th><th class="px-4 py-2">Kategori</th><th class="px-4 py-2 text-center">Stok Σ</th><th class="px-4 py-2">Sinyal</th><th class="px-4 py-2 text-right">Enduser</th><th class="px-4 py-2 text-right">Reseller</th><th class="px-4 py-2 text-center">Status</th></tr></thead>
<tbody>
@foreach($products as $p)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold">{{ $p->nama }} <span class="font-normal text-slate-400">({{ $p->variants->count() }} varian)</span></td>
<td class="px-4 py-2 text-xs">{{ $p->kategori }}</td>
<td class="px-4 py-2 text-center"><span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p->stok<=0?'bg-red-500 text-white':($p->stok<=10?'bg-amber-300':'bg-emerald-300') }}">Σ {{ $p->stok }}</span></td>
<td class="px-4 py-2 text-xs">@if($p->sinyal==='habis')<b class="text-red-600">🔴 Habis</b>@elseif($p->sinyal==='segera')<b class="text-amber-600">🟡 ±{{ $p->hari_habis }} hari</b>@elseif($p->sinyal==='kritis')<b class="text-amber-600">🟡 Kritis</b>@elseif($p->sinyal==='diam')<span class="text-slate-400">⚪ Diam 30 hari</span>@else<span class="text-emerald-600 font-bold">🟢 {{ $p->terjual30 }}/bln</span>@endif</td>
<td class="px-4 py-2 text-right">Rp {{ number_format($p->harga_enduser) }}</td>
<td class="px-4 py-2 text-right">Rp {{ number_format($p->harga_reseller) }}</td>
<td class="px-4 py-2 text-center">@if(!$p->is_active)<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-400 text-white">OFF</span>@else<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">ON</span>@endif</td>
</tr>@endforeach
</tbody>
</table>
</div>

<form method="POST" action="/admin/produk/arsip-massal" id="bulkarsip" onsubmit="return confirm('Arsipkan (nonaktifkan) barang terpilih?')">@csrf
<button class="w-full mb-2 bg-slate-100 text-slate-600 font-bold py-2.5 rounded-2xl text-xs">📦 Arsipkan yang dicentang</button>
</form>
<div class="grid gap-3 lg:grid-cols-2 lg:items-start">
@foreach($products as $p)
<div class="bg-white rounded-3xl shadow-card p-4 text-sm">
<div class="flex gap-2 items-center">
<input type="checkbox" name="ids[]" value="{{ $p->id }}" form="bulkarsip" class="w-5 h-5 flex-none" title="Pilih untuk arsip massal">
@if($p->foto)<img src="/storage/{{ $p->foto }}" class="w-12 h-12 rounded-2xl object-cover" alt=""> @else <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl" style="background:linear-gradient(180deg,#D8E6FF,#fff)">👕</div>@endif
<div class="flex-1"><b>{{ $p->nama }}</b><div class="text-[11px] text-slate-500">{{ $p->deskripsi ? \Str::limit($p->deskripsi,60) : 'Belum ada deskripsi' }}</div>
<form method="POST" action="/admin/produk/{{ $p->id }}/duplikat" class="inline" onsubmit="return confirm('Duplikat {{ $p->nama }}? (varian+tier ikut, stok 0, nonaktif)')">@csrf<button class="text-[10px] text-purple-700 font-bold underline">⧉ Duplikat</button></form>
</div>
<span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $p->stok<=0?'bg-red-500 text-white':($p->stok<=10?'bg-amber-300':'bg-emerald-300') }}">Σ {{ $p->stok }}</span>
@if(!$p->is_active)<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-400 text-white">OFF</span>@endif
</div>
<div class="text-[11px] mt-1.5">
@if($p->sinyal==='habis')<span class="font-bold text-red-600">🔴 Habis — restock sekarang</span>
@elseif($p->sinyal==='segera')<span class="font-bold text-amber-600">🟡 ±{{ $p->hari_habis }} hari habis — restock segera</span>
@elseif($p->sinyal==='kritis')<span class="font-bold text-amber-600">🟡 Stok kritis</span>
@elseif($p->sinyal==='diam')<span class="text-slate-400">⚪ Tidak laku 30 hari</span>
@else<span class="text-emerald-600 font-bold">🟢 Aman • laku {{ $p->terjual30 }} pcs/30 hari</span>@endif
</div>
<div class="mt-2 rounded-2xl bg-purple-50/70 border border-purple-100 p-2.5">
<div class="text-[11px] font-extrabold text-purple-900 mb-1.5">📐 VARIAN ({{ $p->variants->count() }})</div>
@foreach($p->variants as $v)
<form method="POST" action="/admin/varian/{{ $v->id }}" class="flex items-center gap-1.5 py-1 border-b border-purple-100 last:border-0">@csrf @method('PUT')
<span class="text-xs font-bold flex-1">{{ $v->label() }} <span class="text-[10px] text-slate-400">{{ $v->sku }}</span></span>
<input name="stok" type="number" min="0" value="{{ $v->stok }}" class="w-16 border rounded-xl px-2 py-1 text-xs font-bold text-center">
<button class="text-[11px] bg-purple-900 text-white font-bold px-2.5 py-1 rounded-xl">OK</button>
</form>
@endforeach
<form method="POST" action="/admin/produk/{{ $p->id }}/varian" class="flex gap-1 mt-1.5">@csrf
<input name="ukuran" placeholder="Ukuran" maxlength="20" class="flex-1 border rounded-xl px-2 py-1.5 text-xs" required>
<input name="warna" placeholder="Warna" maxlength="30" class="flex-1 border rounded-xl px-2 py-1.5 text-xs">
<input name="stok" type="number" min="0" value="0" class="w-16 border rounded-xl px-2 py-1.5 text-xs text-center">
<button class="text-[11px] bg-emerald-500 text-white font-bold px-2.5 rounded-xl">+ Varian</button>
</form>
@foreach($p->variants as $v)
<form method="POST" action="/admin/varian/{{ $v->id }}" onsubmit="return confirm('Hapus varian {{ $v->label() }}? (gagal jika sudah pernah terjual)')">@csrf @method('DELETE')<button class="text-[10px] text-red-400 underline">hapus {{ $v->label() }}</button></form>
@endforeach
</div>
<div class="mt-2 rounded-2xl bg-emerald-50/70 border border-emerald-100 p-2.5">
<div class="text-[11px] font-extrabold text-emerald-900 mb-1.5">💰 TIER HARGA (level + min qty)</div>
@foreach($p->prices as $t)
<div class="flex items-center gap-1.5 py-1 border-b border-emerald-100 last:border-0 text-xs">
<span class="font-bold flex-1">{{ $t->level === 'reseller' ? '🏪 Reseller' : '🙋 Reguler' }} <span class="text-slate-400">min {{ $t->min_qty }} pcs</span></span>
<span class="font-extrabold">Rp {{ number_format($t->price) }}</span>
<form method="POST" action="/admin/tier/{{ $t->id }}" onsubmit="return confirm('Hapus tier ini?')">@csrf @method('DELETE')<button class="text-red-400 font-bold">×</button></form>
</div>
@endforeach
<form method="POST" action="/admin/produk/{{ $p->id }}/tier" class="flex gap-1 mt-1.5">@csrf
<select name="level" class="border rounded-xl px-2 py-1.5 text-xs font-bold"><option value="reseller">Reseller</option><option value="user">Reguler</option></select>
<input name="min_qty" type="number" min="1" value="12" placeholder="Min" class="w-16 border rounded-xl px-2 py-1.5 text-xs text-center" required>
<input name="price" type="number" min="0" placeholder="Rp" class="flex-1 border rounded-xl px-2 py-1.5 text-xs" required>
<button class="text-[11px] bg-emerald-500 text-white font-bold px-2.5 rounded-xl">+ Tier</button>
</form>
</div>
<form method="POST" action="/admin/produk/{{ $p->id }}" enctype="multipart/form-data" class="mt-2 space-y-1.5">@csrf @method('PUT')
<details>
<summary class="text-xs font-bold text-purple-700 cursor-pointer">✏️ Ubah info, harga & foto</summary>
<div class="space-y-1.5 mt-1.5">
<input name="nama" value="{{ $p->nama }}" class="w-full border rounded-xl px-2 py-1.5 font-bold">
<div class="flex gap-1.5"><textarea name="deskripsi" rows="2" placeholder="Deskripsi barang…" class="flex-[2] border rounded-xl px-2 py-1.5 text-xs">{{ $p->deskripsi }}</textarea><input name="kategori" value="{{ $p->kategori }}" placeholder="Kategori" maxlength="50" class="flex-1 border rounded-xl px-2 py-1.5 text-xs"></div>
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
<form method="POST" action="/admin/produk/{{ $p->id }}" onsubmit="return confirm('Hapus/nonaktifkan {{ $p->nama }}?')">@csrf @method('DELETE')<button class="w-full mt-1 text-red-400 text-[11px] underline">Hapus / nonaktifkan barang</button></form>
</div>
@endforeach
</div>
@endsection
