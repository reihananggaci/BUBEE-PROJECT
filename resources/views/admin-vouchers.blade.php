@extends('layouts.admin')
@section('title','Voucher')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">🎟️ Kelola Voucher</h1>
<div class="text-xs text-slate-500 mb-2">Kode dipakai pembeli di checkout. Kedaluwarsa & kuota habis otomatis ditolak sistem.</div>

<div class="glass rounded-[1.75rem] shadow-card p-4">
<div class="font-extrabold text-sm">➕ Voucher Baru</div>
<form method="POST" action="/admin/voucher" class="grid grid-cols-2 gap-1.5 mt-2 text-sm">@csrf
<input name="kode" placeholder="KODE cth: HEMAT10 *" maxlength="20" class="rounded-2xl border px-3 py-2" required>
<input name="nama" placeholder="Nama promo" maxlength="100" class="rounded-2xl border px-3 py-2">
<select name="tipe" class="rounded-2xl border px-3 py-2"><option value="persen">Persen (%)</option><option value="nominal">Nominal (Rp)</option></select>
<input name="nilai" type="number" min="1" placeholder="Nilai *" class="rounded-2xl border px-3 py-2" required>
<input name="maks_diskon" type="number" min="0" placeholder="Maks diskon Rp (0=bebas)" class="rounded-2xl border px-3 py-2">
<input name="min_belanja" type="number" min="0" placeholder="Min belanja Rp" class="rounded-2xl border px-3 py-2">
<input name="kuota" type="number" min="1" value="100" placeholder="Kuota" class="rounded-2xl border px-3 py-2" required>
<select name="khusus" class="rounded-2xl border px-3 py-2"><option value="semua">Semua akun</option><option value="reseller">Reseller saja</option><option value="enduser">Reguler saja</option></select>
<input name="berlaku_sampai" type="date" class="rounded-2xl border px-3 py-2 col-span-2">
<label class="text-xs col-span-2 flex items-center gap-1"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
<button class="col-span-2 text-white font-extrabold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">BUAT VOUCHER 🎟️</button>
</form>
</div>

@foreach($vouchers as $v)
<form method="POST" action="/admin/voucher/{{ $v->id }}" class="glass rounded-3xl shadow-card p-4 mt-2 text-sm">@csrf @method('PUT')
<div class="flex justify-between items-center">
<b class="text-base">{{ $v->kode }}</b>
<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $v->is_active?'bg-emerald-200':'bg-slate-200' }}">{{ $v->is_active?'AKTIF':'OFF' }}</span>
</div>
<div class="text-xs text-slate-500">{{ $v->nama }} • {{ $v->label() }} • khusus {{ $v->khusus === 'enduser' ? 'reguler' : $v->khusus }} • dipakai {{ $v->terpakai }}/{{ $v->kuota }}@if($v->berlaku_sampai) • s/d {{ $v->berlaku_sampai->format('d M Y') }}@endif</div>
<div class="grid grid-cols-3 gap-1.5 mt-2">
<label class="text-[11px]">Nilai<input name="nilai" type="number" value="{{ $v->nilai }}" class="w-full border rounded-xl px-2 py-1.5"></label>
<label class="text-[11px]">Maks Rp<input name="maks_diskon" type="number" value="{{ $v->maks_diskon }}" class="w-full border rounded-xl px-2 py-1.5"></label>
<label class="text-[11px]">Min Rp<input name="min_belanja" type="number" value="{{ $v->min_belanja }}" class="w-full border rounded-xl px-2 py-1.5"></label>
<label class="text-[11px]">Kuota<input name="kuota" type="number" value="{{ $v->kuota }}" class="w-full border rounded-xl px-2 py-1.5"></label>
<label class="text-[11px]">Aktif<select name="is_active" class="w-full border rounded-xl px-2 py-1.5"><option value="1" {{ $v->is_active?'selected':'' }}>Ya</option><option value="0" {{ !$v->is_active?'selected':'' }}>Tidak</option></select></label>
<label class="text-[11px]">S/d<input name="berlaku_sampai" type="date" value="{{ $v->berlaku_sampai?->format('Y-m-d') }}" class="w-full border rounded-xl px-2 py-1.5"></label>
</div>
<input name="nama" value="{{ $v->nama }}" placeholder="Nama promo" class="w-full border rounded-xl px-2 py-1.5 text-xs mt-1.5">
<button class="w-full mt-1.5 text-white font-bold py-2 rounded-2xl text-xs" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">💾 Simpan</button>
</form>
<form method="POST" action="/admin/voucher/{{ $v->id }}" onsubmit="return confirm('Hapus {{ $v->kode }}?')">@csrf @method('DELETE')<button class="w-full mt-1 bg-red-50 text-red-600 font-bold py-2 rounded-2xl text-[11px]">🗑️ Hapus / Nonaktifkan</button></form>
@endforeach
@endsection
