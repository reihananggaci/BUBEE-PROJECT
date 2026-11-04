@extends('layouts.web')
@section('title','Akun Saya')
@section('content')
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2 rounded-3xl p-6 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(135deg,#050B2E,#1D4ED8,#00B8D4)">
<div class="flex items-center gap-4 relative">
<div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-extrabold text-blue-900 bg-white flex-none" style="box-shadow:0 0 0 3px rgba(255,255,255,.35)">{{ strtoupper(substr($user->name,0,1)) }}</div>
<div class="flex-1 min-w-0">
<div class="font-extrabold text-xl leading-tight">{{ $user->name }}</div>
<div class="text-xs opacity-80 flex items-center gap-1.5 flex-wrap mt-0.5">
    <span>{{ $user->email }}</span>
    @if($user->toko_nama)
    <span>•</span>
    <span class="inline-flex items-center gap-1">
        <svg class="w-3.5 h-3.5 inline text-cyan-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
        <span>{{ $user->toko_nama }}</span>
    </span>
    @endif
</div>
<div class="mt-1 flex gap-1.5 flex-wrap">
<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-cyan-300 text-slate-900">{{ strtoupper(nama_role($user->role)) }}@if($user->role==='reseller') • {{ strtoupper($user->status_reseller) }}@endif</span>
@if($tier)<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-amber-300 text-slate-900">{{ strtoupper($tier) }}</span>@endif
</div>
</div>
<a href="/akun/pengaturan" class="inline-flex items-center gap-1.5 text-[11px] font-extrabold px-3 py-2 rounded-xl bg-white/20 hover:bg-white/30 transition relative text-white" style="text-decoration:none">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
    <span>Atur</span>
</a>
</div>
@if($user->role==='reseller' && $user->status_reseller==='approved')
<div class="relative mt-3 text-xs bg-emerald-500/30 border border-emerald-400/50 rounded-2xl p-3 flex items-center gap-2">
    <svg class="w-4 h-4 text-emerald-200 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/></svg>
    <span><b>Akun Reseller Aktif!</b> Nikmati harga grosir otomatis untuk setiap pesanan.</span>
</div>
@elseif($user->role==='reseller' && $user->status_reseller==='pending')
<div class="relative mt-3 text-xs bg-amber-400/30 border border-amber-300/50 rounded-2xl p-3 flex items-center gap-2">
    <svg class="w-4 h-4 text-amber-200 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <span><b>Pengajuan Reseller Sedang Ditinjau Admin</b>. Data tokomu sedang divalidasi. Cek berkala ya.</span>
</div>
@elseif($user->role==='reseller' && $user->status_reseller==='rejected')
<div class="relative mt-3 text-xs bg-rose-500/30 border border-rose-400/50 rounded-2xl p-3 flex items-center gap-2">
    <svg class="w-4 h-4 text-rose-200 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    <span><b>Pengajuan Reseller Belum Disetujui</b>. Silakan periksa kembali kelengkapan NIK, nama toko, atau foto KTP di bawah untuk mengajukan ulang.</span>
</div>
@endif
<div class="grid grid-cols-3 gap-2 mt-4 text-center relative">
<div class="rounded-2xl p-3 bg-black/25"><div class="font-extrabold text-xl">{{ $jmlOrder }}</div><div class="text-[10px] opacity-70">ORDER</div></div>
<div class="rounded-2xl p-3 bg-black/25"><div class="font-extrabold text-xl">{{ $jmlPcs }}</div><div class="text-[10px] opacity-70">PCS</div></div>
<div class="rounded-2xl p-3 bg-black/25"><div class="font-extrabold text-base mt-0.5">Rp {{ number_format($hemat) }}</div><div class="text-[10px] opacity-70">HEMAT</div></div>
</div>
</div>
@if($user->role==='enduser' || ($user->role==='reseller' && $user->status_reseller==='rejected'))
<div class="rounded-3xl p-6 mt-4 bg-white shadow-card text-sm lg:col-span-2 border border-slate-100">
<div class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <svg class="w-5 h-5 text-cyan-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
    <span>{{ $user->status_reseller==='rejected' ? 'Ajukan Ulang / Perbaiki Data Reseller' : 'Upgrade ke Reseller — Hemat Harga Grosir' }}</span>
</div>
<div class="text-xs text-slate-500 mt-0.5">{{ $user->status_reseller==='rejected' ? 'Perbaiki data toko atau NIK Anda agar admin dapat menyetujui akun reseller.' : 'Semua akun mulai sebagai Reguler. Isi data toko → approval admin 1x → harga grosir otomatis.' }}</div>
<form method="POST" action="/akun/upgrade-reseller" enctype="multipart/form-data" class="mt-3 grid gap-2 lg:grid-cols-2">@csrf
<input name="toko_nama" value="{{ $user->toko_nama }}" placeholder="Nama toko *" maxlength="60" class="w-full border border-slate-200 rounded-2xl px-3 py-2.5 text-sm" required>
<input name="nik" inputmode="numeric" value="{{ $user->nik }}" placeholder="NIK KTP 16 digit *" maxlength="16" class="w-full border border-slate-200 rounded-2xl px-3 py-2.5 text-sm" required>
<textarea name="alamat" placeholder="Alamat lengkap *" class="w-full border border-slate-200 rounded-2xl px-3 py-2.5 text-sm lg:col-span-2" required>{{ $user->alamat }}</textarea>
<label class="block text-xs text-slate-500 lg:col-span-2">
    <span class="flex items-center gap-1 font-bold text-slate-700 mb-1">
        <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
        <span>Foto KTP (opsional)</span>
    </span>
    <input type="file" name="ktp_foto" accept="image/*" class="w-full text-xs">
</label>
<button class="w-full text-white font-extrabold py-3 rounded-2xl lg:col-span-2 transition shadow-sm hover:opacity-95" style="background:linear-gradient(90deg,#0e7490,#06b6d4)">{{ $user->status_reseller==='rejected' ? 'Kirim Pengajuan Ulang' : 'Ajukan Upgrade Reseller' }}</button>
</form>
</div>
@endif
<div class="bg-white rounded-3xl shadow-card text-sm font-bold divide-y divide-slate-100 overflow-hidden self-start lg:sticky lg:top-20 border border-slate-100">
<a href="/pesanan" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition" style="text-decoration:none;color:#0f172a">
    <svg class="w-4 h-4 text-purple-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg>
    <span>Pesanan Saya</span>
    <span class="ml-auto text-slate-300">›</span>
</a>
<a href="/keranjang" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition" style="text-decoration:none;color:#0f172a">
    <svg class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
    <span>Keranjang</span>
    <span class="ml-auto text-slate-300">›</span>
</a>
<a href="/retur" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition" style="text-decoration:none;color:#0f172a">
    <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V5.5A1.5 1.5 0 0 1 5.5 4H9"/><path d="M4 5.5A9 9 0 1 1 3 13"/><path d="M3 13h5"/></svg>
    <span>Retur Saya</span>
    <span class="ml-auto text-slate-300">›</span>
</a>
<a href="/akun/pengaturan" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition" style="text-decoration:none;color:#0f172a">
    <svg class="w-4 h-4 text-slate-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
    <span>Pengaturan</span>
    <span class="ml-auto text-slate-300">›</span>
</a>
@if($user->role==='reseller' && $user->status_reseller==='approved')
<a href="/reseller" class="flex items-center gap-3 px-4 py-3 text-cyan-800 hover:bg-slate-50 transition" style="text-decoration:none">
    <svg class="w-4 h-4 text-cyan-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
    <span>Reseller Center</span>
    <span class="ml-auto text-slate-300">›</span>
</a>
@endif
@if($user->role==='admin')
<a href="/admin" class="flex items-center gap-3 px-4 py-3 text-purple-700 hover:bg-slate-50 transition" style="text-decoration:none">
    <svg class="w-4 h-4 text-purple-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1.5"/><rect width="7" height="5" x="14" y="3" rx="1.5"/><rect width="7" height="9" x="14" y="12" rx="1.5"/><rect width="7" height="5" x="3" y="16" rx="1.5"/></svg>
    <span>Dashboard Admin</span>
    <span class="ml-auto text-slate-300">›</span>
</a>
<a href="/admin/pengaturan" class="flex items-center gap-3 px-4 py-3 text-purple-700 hover:bg-slate-50 transition" style="text-decoration:none">
    <svg class="w-4 h-4 text-purple-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h9"/><circle cx="16.5" cy="7" r="2"/><path d="M20 17H9"/><circle cx="6" cy="17" r="2"/></svg>
    <span>Pengaturan Toko</span>
    <span class="ml-auto text-slate-300">›</span>
</a>
@endif
<form method="POST" action="/logout" class="p-3">@csrf<button class="w-full bg-rose-500 hover:bg-rose-600 text-white font-extrabold py-3 rounded-2xl transition shadow-sm">Keluar</button></form>
</div>
</div>
@endsection
