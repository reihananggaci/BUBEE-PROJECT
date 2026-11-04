@extends('layouts.web-admin')
@section('title','Voucher')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 11v2M13 17v2"/></svg>
    </span>
    <span>Kelola Voucher</span>
</h1>
<div class="text-xs text-slate-500 mb-3 mt-0.5">Kode dipakai pembeli di checkout. Kedaluwarsa & kuota habis otomatis ditolak sistem.</div>
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<div class="bg-white rounded-3xl shadow-card p-5 border border-slate-100">
<div class="font-extrabold text-sm flex items-center gap-1.5"><svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg> Voucher Baru</div>
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
<button class="col-span-2 text-white font-extrabold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">BUAT VOUCHER</button>
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
<button class="text-white font-bold py-1 px-3 rounded-xl text-xs" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">Simpan</button>
</form>
<form method="POST" action="/admin/voucher/{{ $v->id }}" onsubmit="return confirm('Hapus {{ $v->kode }}?')" class="mt-1">@csrf @method('DELETE')<button class="text-[11px] text-red-500 font-bold">Hapus</button></form></td>
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
<button class="w-full mt-1.5 text-white font-bold py-2 rounded-2xl text-xs flex items-center justify-center gap-1.5 shadow-sm hover:opacity-95 transition" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
    <span>Simpan Perubahan</span>
</button>
</form>
<form method="POST" action="/admin/voucher/{{ $v->id }}" onsubmit="return confirm('Hapus {{ $v->kode }}?')">@csrf @method('DELETE')<button class="w-full mt-1 bg-red-50 text-red-600 hover:bg-red-100 font-bold py-2 rounded-2xl text-[11px] flex items-center justify-center gap-1 transition">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
    <span>Hapus / Nonaktifkan</span>
</button></form>
@endforeach
</div>
</div>
</div>
@endsection
