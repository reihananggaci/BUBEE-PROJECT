@extends('layouts.admin')
@section('title','Voucher')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg>
    </span>
    <span>Kelola Voucher</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Kode dipakai pembeli di checkout. Kedaluwarsa & kuota habis otomatis ditolak sistem.</div>

<div class="glass rounded-[1.75rem] shadow-card p-4">
<div class="font-extrabold text-sm flex items-center gap-1.5 text-slate-900">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
    <span>Voucher Baru</span>
</div>
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
<button class="col-span-2 text-white font-extrabold py-2.5 rounded-2xl shadow-sm" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">Buat Voucher</button>
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
<button class="w-full mt-1.5 text-white font-bold py-2 rounded-2xl text-xs flex items-center justify-center gap-1.5 shadow-sm" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
    <span>Simpan Perubahan</span>
</button>
</form>
<form method="POST" action="/admin/voucher/{{ $v->id }}" onsubmit="return confirm('Hapus {{ $v->kode }}?')">@csrf @method('DELETE')<button class="w-full mt-1 bg-red-50 text-red-600 font-bold py-2 rounded-2xl text-[11px] flex items-center justify-center gap-1">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
    <span>Hapus / Nonaktifkan</span>
</button></form>
@endforeach
@endsection
