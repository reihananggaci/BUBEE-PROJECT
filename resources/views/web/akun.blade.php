@extends('layouts.web')
@section('title','Akun Saya')
@section('content')
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2 rounded-3xl p-6 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(135deg,#050B2E,#1D4ED8,#00B8D4)">
<div class="flex items-center gap-4 relative">
<div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-extrabold text-blue-900 bg-white flex-none" style="box-shadow:0 0 0 3px rgba(255,255,255,.35)">{{ strtoupper(substr($user->name,0,1)) }}</div>
<div class="flex-1 min-w-0">
<div class="font-extrabold text-xl leading-tight">{{ $user->name }}</div>
<div class="text-xs opacity-70">{{ $user->email }}@if($user->toko_nama) • 🏪 {{ $user->toko_nama }}@endif</div>
<div class="mt-1 flex gap-1.5 flex-wrap">
<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-cyan-300 text-slate-900">{{ strtoupper(nama_role($user->role)) }}@if($user->role==='reseller') • {{ strtoupper($user->status_reseller) }}@endif</span>
@if($tier)<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-amber-300 text-slate-900">{{ strtoupper($tier) }}</span>@endif
</div>
</div>
<a href="/akun/pengaturan" class="text-[11px] font-extrabold px-3 py-2 rounded-xl bg-white/20 relative" style="text-decoration:none;color:#fff">⚙️ Atur</a>
</div>
@if($user->role==='reseller' && $user->status_reseller==='approved')
<div class="relative mt-3 text-xs bg-emerald-500/30 border border-emerald-400/50 rounded-2xl p-3">🎉 <b>Akun Reseller Aktif!</b> Nikmati harga grosir otomatis untuk setiap pesanan.</div>
@elseif($user->role==='reseller' && $user->status_reseller==='pending')
<div class="relative mt-3 text-xs bg-amber-400/30 border border-amber-300/50 rounded-2xl p-3">⏳ <b>Pengajuan Reseller Sedang Ditinjau Admin</b>. Data tokomu sedang divalidasi. Cek berkala ya.</div>
@elseif($user->role==='reseller' && $user->status_reseller==='rejected')
<div class="relative mt-3 text-xs bg-rose-500/30 border border-rose-400/50 rounded-2xl p-3">❌ <b>Pengajuan Reseller Belum Disetujui</b>. Silakan periksa kembali kelengkapan NIK, nama toko, atau foto KTP di bawah untuk mengajukan ulang.</div>
@endif
<div class="grid grid-cols-3 gap-2 mt-4 text-center relative">
<div class="rounded-2xl p-3 bg-black/25"><div class="font-extrabold text-xl">{{ $jmlOrder }}</div><div class="text-[10px] opacity-70">ORDER</div></div>
<div class="rounded-2xl p-3 bg-black/25"><div class="font-extrabold text-xl">{{ $jmlPcs }}</div><div class="text-[10px] opacity-70">PCS</div></div>
<div class="rounded-2xl p-3 bg-black/25"><div class="font-extrabold text-base mt-0.5">Rp {{ number_format($hemat) }}</div><div class="text-[10px] opacity-70">HEMAT</div></div>
</div>
</div>
@if($user->role==='enduser' || ($user->role==='reseller' && $user->status_reseller==='rejected'))
<div class="rounded-3xl p-6 mt-4 bg-white shadow-card text-sm lg:col-span-2">
<div class="font-extrabold text-lg">{{ $user->status_reseller==='rejected' ? '🔄 Ajukan Ulang / Perbaiki Data Reseller' : '🏪 Upgrade ke Reseller — hemat harga grosir' }}</div>
<div class="text-xs text-slate-500">{{ $user->status_reseller==='rejected' ? 'Perbaiki data toko atau NIK Anda agar admin dapat menyetujui akun reseller.' : 'Semua akun mulai sebagai Reguler. Isi data toko → approval admin 1x → harga grosir otomatis.' }}</div>
<form method="POST" action="/akun/upgrade-reseller" enctype="multipart/form-data" class="mt-2 grid gap-1.5 lg:grid-cols-2">@csrf
<input name="toko_nama" value="{{ $user->toko_nama }}" placeholder="Nama toko *" maxlength="60" class="w-full border rounded-2xl px-3 py-2.5 text-sm" required>
<input name="nik" inputmode="numeric" value="{{ $user->nik }}" placeholder="NIK KTP 16 digit *" maxlength="16" class="w-full border rounded-2xl px-3 py-2.5 text-sm" required>
<textarea name="alamat" placeholder="Alamat lengkap *" class="w-full border rounded-2xl px-3 py-2.5 text-sm lg:col-span-2" required>{{ $user->alamat }}</textarea>
<label class="block text-xs text-slate-500 lg:col-span-2">📷 Foto KTP (opsional)<input type="file" name="ktp_foto" accept="image/*" class="w-full text-xs mt-1"></label>
<button class="w-full text-white font-extrabold py-3 rounded-2xl lg:col-span-2" style="background:linear-gradient(90deg,#0e7490,#06b6d4)">{{ $user->status_reseller==='rejected' ? 'KIRIM PENGAJUAN ULANG 🔄' : 'AJUKAN UPGRADE 🏪' }}</button>
</form>
</div>
@endif
<div class="bg-white rounded-3xl shadow-card text-sm font-bold divide-y divide-slate-100 overflow-hidden self-start lg:sticky lg:top-20">
<a href="/pesanan" class="flex items-center gap-3 px-4 py-3" style="text-decoration:none;color:#0f172a">📦 Pesanan Saya <span class="ml-auto text-slate-300">›</span></a>
<a href="/keranjang" class="flex items-center gap-3 px-4 py-3" style="text-decoration:none;color:#0f172a">🛒 Keranjang <span class="ml-auto text-slate-300">›</span></a>
<a href="/retur" class="flex items-center gap-3 px-4 py-3" style="text-decoration:none;color:#0f172a">🔄 Retur Saya <span class="ml-auto text-slate-300">›</span></a>
<a href="/akun/pengaturan" class="flex items-center gap-3 px-4 py-3" style="text-decoration:none;color:#0f172a">⚙️ Pengaturan <span class="ml-auto text-slate-300">›</span></a>
@if($user->role==='reseller' && $user->status_reseller==='approved')
<a href="/reseller" class="flex items-center gap-3 px-4 py-3 text-cyan-800" style="text-decoration:none">🏪 Reseller Center <span class="ml-auto text-slate-300">›</span></a>
@endif
@if($user->role==='admin')
<a href="/admin" class="flex items-center gap-3 px-4 py-3 text-purple-700" style="text-decoration:none">🤖 Dashboard Admin <span class="ml-auto text-slate-300">›</span></a>
<a href="/admin/pengaturan" class="flex items-center gap-3 px-4 py-3 text-purple-700" style="text-decoration:none">🏬 Pengaturan Toko <span class="ml-auto text-slate-300">›</span></a>
@endif
<form method="POST" action="/logout" class="p-3">@csrf<button class="w-full bg-red-500/90 text-white font-extrabold py-3 rounded-3xl">KELUAR</button></form>
</div>
</div>
@endsection
