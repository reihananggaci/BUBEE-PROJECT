@extends('layouts.web-admin')
@section('title','Voucher')
@section('content')
<h1 class="font-extrabold text-2xl">🎟️ Kelola Voucher</h1>
<div class="text-xs text-slate-500 mb-3">Kode dipakai pembeli di checkout. Kedaluwarsa & kuota habis otomatis ditolak sistem.</div>
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<div class="bg-white rounded-3xl shadow-card p-5">
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
<div class="lg:col-span-2">
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Kode</th><th class="px-4 py-2">Aturan</th><th class="px-4 py-2">Pakai</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Ubah</th></tr></thead>
<tbody>
@foreach($vouchers as $v)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold">{{ $v->kode }}<br><span class="font-normal text-[11px] text-slate-400">{{ $v->nama }}</span></td>
<td class="px-4 py-2 text-xs">{{ $v->label() }} • khusus {{ $v->khusus === 'enduser' ? 'reguler' : $v->khusus }}@if($v->berlaku_sampai)<br>s/d {{ $v->berlaku_sampai->format('d M Y') }}@endif</td>
<td class="px-4 py-2 text-xs whitespace-nowrap">{{ $v->terpakai }}/{{ $v->kuota }}</td>
<td class="px-4 py-2"><span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $v->is_active?'bg-emerald-200':'bg-slate-200' }}">{{ $v->is_active?'AKTIF':'OFF' }}</span></td>
<td class="px-4 py-2"><form method="POST" action="/admin/voucher/{{ $v->id }}" class="flex gap-1 items-center">@csrf @method('PUT')
<input name="nilai" type="number" value="{{ $v->nilai }}" class="w-20 border rounded-xl px-2 py-1 text-xs">
<input name="kuota" type="number" value="{{ $v->kuota }}" class="w-20 border rounded-xl px-2 py-1 text-xs">
<input type="hidden" name="maks_diskon" value="{{ $v->maks_diskon }}"><input type="hidden" name="min_belanja" value="{{ $v->min_belanja }}"><input type="hidden" name="nama" value="{{ $v->nama }}">
<button class="text-white font-bold py-1 px-3 rounded-xl text-xs" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">💾</button>
</form>
<form method="POST" action="/admin/voucher/{{ $v->id }}" onsubmit="return confirm('Hapus {{ $v->kode }}?')" class="mt-1">@csrf @method('DELETE')<button class="text-[11px] text-red-500 font-bold">🗑️ Hapus</button></form></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">
@foreach($vouchers as $v)
<form method="POST" action="/admin/voucher/{{ $v->id }}" class="bg-white rounded-3xl shadow-card p-4 mt-2 text-sm">@csrf @method('PUT')
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
</div>
</div>
</div>
@endsection
