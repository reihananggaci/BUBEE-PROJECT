@extends('layouts.app')
@section('title','Akun Saya')
@section('content')
{{-- HERO PROFIL --}}
<div class="rounded-[2rem] p-5 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(135deg,#050B2E,#1D4ED8,#00B8D4)">
<div class="flex items-center gap-3 relative">
<div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-extrabold text-blue-900 bg-white flex-none" style="box-shadow:0 0 0 3px rgba(255,255,255,.35)">{{ strtoupper(substr($user->name,0,1)) }}</div>
<div class="flex-1 min-w-0">
<div class="font-extrabold text-lg leading-tight">{{ $user->name }}</div>
<div class="text-xs opacity-70 flex items-center gap-1.5 flex-wrap"><span>{{ $user->email }}</span>@if($user->toko_nama)<span>•</span><span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>{{ $user->toko_nama }}</span>@endif</div>
<div class="mt-1 flex gap-1.5 flex-wrap">
<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-cyan-300 text-slate-900">{{ strtoupper(nama_role($user->role)) }}@if($user->role==='reseller') • {{ strtoupper($user->status_reseller) }}@endif</span>
@if($tier)<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-amber-300 text-slate-900">{{ strtoupper($tier) }}</span>@endif
</div>
</div>
<a href="/akun/pengaturan" class="text-[11px] font-extrabold px-3 py-2 rounded-xl bg-white/20 relative flex items-center gap-1.5" style="text-decoration:none">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    <span>Atur</span>
</a>
</div>
@if($user->role==='reseller' && $user->status_reseller==='approved')
<div class="relative mt-3 text-xs bg-emerald-500/30 border border-emerald-400/50 rounded-2xl p-3 flex items-center gap-2">
    <svg class="w-4 h-4 text-emerald-200 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
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
<div class="grid grid-cols-3 gap-2 mt-3 text-center relative">
<div class="rounded-2xl p-2.5 bg-black/25"><div class="font-extrabold text-lg">{{ $jmlOrder }}</div><div class="text-[10px] opacity-70">ORDER</div></div>
<div class="rounded-2xl p-2.5 bg-black/25"><div class="font-extrabold text-lg">{{ $jmlPcs }}</div><div class="text-[10px] opacity-70">PCS</div></div>
<div class="rounded-2xl p-2.5 bg-black/25"><div class="font-extrabold text-[15px] mt-0.5">Rp {{ number_format($hemat) }}</div><div class="text-[10px] opacity-70">HEMAT</div></div>
</div>
</div>

{{-- UPGRADE ATAU AJUKAN ULANG RESELLER --}}
@if($user->role==='enduser' || ($user->role==='reseller' && $user->status_reseller==='rejected'))
<div class="rounded-[2rem] p-5 mt-3 text-slate-900 bg-white shadow-card text-sm">
<div class="font-extrabold flex items-center gap-1.5">
    <svg class="w-4 h-4 text-cyan-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    <span>{{ $user->status_reseller==='rejected' ? 'Ajukan Ulang / Perbaiki Data Reseller' : 'Mau harga grosir? Upgrade ke Reseller' }}</span>
</div>
<div class="text-xs text-slate-500 mt-0.5">{{ $user->status_reseller==='rejected' ? 'Perbaiki data toko atau NIK Anda agar admin dapat menyetujui akun reseller.' : 'Semua akun mulai sebagai Reguler. Isi data toko → approval admin 1x → harga grosir otomatis.' }}</div>
<form method="POST" action="/akun/upgrade-reseller" enctype="multipart/form-data" class="mt-2 space-y-1.5">@csrf
<input name="toko_nama" value="{{ $user->toko_nama }}" placeholder="Nama toko *" maxlength="60" class="w-full border rounded-2xl px-3 py-2.5 text-sm" required>
<input name="nik" inputmode="numeric" value="{{ $user->nik }}" placeholder="NIK KTP 16 digit *" maxlength="16" class="w-full border rounded-2xl px-3 py-2.5 text-sm" required>
<textarea name="alamat" placeholder="Alamat lengkap *" class="w-full border rounded-2xl px-3 py-2.5 text-sm" required>{{ $user->alamat }}</textarea>
<label class="block text-xs text-slate-500"><span class="inline-flex items-center gap-1 text-slate-700 font-bold"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>Foto KTP (opsional)</span><input type="file" name="ktp_foto" accept="image/*" class="w-full text-xs mt-1"></label>
<button class="w-full text-white font-extrabold py-3 rounded-2xl flex items-center justify-center gap-1.5" style="background:linear-gradient(90deg,#0e7490,#06b6d4)">
    <span>{{ $user->status_reseller==='rejected' ? 'Kirim Pengajuan Ulang' : 'Ajukan Upgrade Reseller' }}</span>
</button>
</form>
</div>
@endif

{{-- MENU --}}
<div class="glass rounded-3xl shadow-card mt-3 text-sm font-bold divide-y divide-slate-100 overflow-hidden">
<a href="/pesanan" class="flex items-center gap-3 px-4 py-3">
    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
    <span>Pesanan Saya</span> <span class="ml-auto text-slate-300">›</span>
</a>
<a href="/keranjang" class="flex items-center gap-3 px-4 py-3">
    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
    <span>Keranjang</span> <span class="ml-auto text-slate-300">›</span>
</a>
<a href="/retur" class="flex items-center gap-3 px-4 py-3">
    <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    <span>Retur Saya</span> <span class="ml-auto text-slate-300">›</span>
</a>
<a href="/akun/pengaturan" class="flex items-center gap-3 px-4 py-3">
    <svg class="w-4 h-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    <span>Pengaturan</span> <span class="ml-auto text-slate-300">›</span>
</a>
@if($user->role==='reseller' && $user->status_reseller==='approved')
<a href="/reseller" class="flex items-center gap-3 px-4 py-3 text-cyan-800">
    <svg class="w-4 h-4 text-cyan-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    <span>Reseller Center</span> <span class="ml-auto text-slate-300">›</span>
</a>
@endif
@if($user->role==='admin')
<a href="/admin" class="flex items-center gap-3 px-4 py-3 text-purple-700">
    <svg class="w-4 h-4 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1.5"/><rect width="7" height="5" x="14" y="3" rx="1.5"/><rect width="7" height="9" x="14" y="12" rx="1.5"/><rect width="7" height="5" x="3" y="16" rx="1.5"/></svg>
    <span>Dashboard Admin</span> <span class="ml-auto text-slate-300">›</span>
</a>
<a href="/admin/pengaturan" class="flex items-center gap-3 px-4 py-3 text-purple-700">
    <svg class="w-4 h-4 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    <span>Pengaturan Toko</span> <span class="ml-auto text-slate-300">›</span>
</a>
@endif
</div>
<form method="POST" action="/logout" class="mt-3">@csrf<button class="w-full bg-red-500/90 text-white font-extrabold py-3 rounded-3xl shadow">KELUAR</button></form>
@endsection
