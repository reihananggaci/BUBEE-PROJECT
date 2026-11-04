@extends('layouts.app')
@section('title','Daftar')
@section('back', '/login')
@section('content')
<div class="auth-split">
<div class="auth-side p-6 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
<div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
<div class="flex items-center gap-3 relative">
<img src="/images/logo-bubee.png" class="w-16 h-16 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)">
<div class="hexbee" style="width:44px;height:49px">
    <svg class="w-6 h-6 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8 2 5 5 5 9c0 2.2 1 4.2 2.5 5.5L7 20l5-2 5 2-.5-5.5C18 13.2 19 11.2 19 9c0-4-3-7-7-7z"/><path d="M9 10h6M8 14h8"/></svg>
</div>
</div>
<div class="font-extrabold text-xl mt-2 relative">Gabung Bubee</div>
<div class="text-xs opacity-70 relative">Reguler langsung aktif • Reseller butuh approval admin 1x, lalu harga grosir otomatis.</div>
</div>
<div class="auth-form glass shadow-card p-6" style="border-radius:2rem">
<h1 class="font-extrabold text-lg">Buat Akun</h1>
<form method="POST" action="/register" enctype="multipart/form-data" class="space-y-2 mt-3">@csrf
<input name="name" placeholder="Nama lengkap" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm" required>
<input name="email" type="email" placeholder="Email" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm" required>
<input name="password" type="password" placeholder="Password min 8" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm" required>
<input name="hp" placeholder="HP/WA cth 0812… *" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm" required>
<textarea name="alamat" placeholder="Alamat lengkap" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm"></textarea>
<input type="text" name="website" value="" style="display:none!important" tabindex="-1" autocomplete="off" aria-hidden="true">
<div class="grid grid-cols-2 gap-2 text-sm">
<label class="border-2 border-blue-200 rounded-2xl p-3 text-center cursor-pointer has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 flex flex-col items-center justify-center gap-1"><input type="radio" name="role" value="enduser" checked class="sr-only"><div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div><b>Reguler</b><div class="text-[11px] text-slate-500">Belanja satuan</div></label>
<label class="border-2 border-cyan-200 rounded-2xl p-3 text-center cursor-pointer has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-50 flex flex-col items-center justify-center gap-1"><input type="radio" name="role" value="reseller" class="sr-only"><div class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div><b>Reseller</b><div class="text-[11px] text-slate-500">Harga khusus</div></label>
</div>
<div id="data-reseller" class="space-y-2 rounded-2xl border border-cyan-200 bg-cyan-50/50 p-3" style="display:none">
<div class="text-xs font-extrabold text-cyan-800 flex items-center gap-1.5">
    <svg class="w-4 h-4 text-cyan-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    <span>Data pribadi reseller (dilihat admin untuk approval)</span>
</div>
<input name="toko_nama" placeholder="Nama toko *" maxlength="60" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm">
<input name="nik" inputmode="numeric" placeholder="NIK KTP 16 digit *" maxlength="16" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm">
<label class="block text-xs text-slate-500"><span class="inline-flex items-center gap-1 text-slate-700 font-bold"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>Foto KTP (opsional)</span><input type="file" name="ktp_foto" accept="image/*" class="w-full text-xs mt-1"></label>
</div>
<script>
(function(){var rs=document.querySelectorAll('input[name=role]'),box=document.getElementById('data-reseller');function sync(){var r=document.querySelector('input[name=role]:checked');var on=r&&r.value==='reseller';box.style.display=on?'':'none';box.querySelectorAll('[name=toko_nama],[name=nik]').forEach(function(i){i.required=!!on;});}rs.forEach(function(i){i.addEventListener('change',sync);});sync();})();
</script>
<button class="w-full text-white font-extrabold py-3 rounded-2xl flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
    <span>Daftar Sekarang</span>
</button>
</form>
<a href="/login" class="block text-center text-sm mt-2">Sudah punya? <b class="text-blue-700">Login</b></a>
</div>
</div>
@endsection
