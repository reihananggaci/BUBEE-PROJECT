@extends('layouts.web')
@section('title','Daftar')
@section('content')
<div class="grid gap-6 items-center mt-4 auth-split lg:grid-cols-2">
<div class="auth-side p-8 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
<div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
<div class="flex items-center gap-3 relative">
<img src="/images/logo-bubee.png" class="w-16 h-16 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)" alt="Bubee">
</div>
<div class="font-extrabold text-2xl mt-3 relative">Gabung Bubee</div>
<div class="text-sm opacity-70 relative">Reguler langsung aktif • Reseller butuh approval admin 1x, lalu harga grosir otomatis.</div>
</div>
<div class="auth-form bg-white shadow-card p-8" style="border-radius:2rem">
<h1 class="font-extrabold text-lg text-slate-900">Buat Akun</h1>
<form method="POST" action="/register" enctype="multipart/form-data" class="space-y-2 mt-3">@csrf
<input name="name" placeholder="Nama lengkap" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm" required>
<input name="email" type="email" placeholder="Email" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm" required>
<input name="password" type="password" placeholder="Password min 8" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm" required>
<input name="hp" placeholder="HP/WA cth 0812… *" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm" required>
<textarea name="alamat" placeholder="Alamat lengkap" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm"></textarea>
<input type="text" name="website" value="" style="display:none!important" tabindex="-1" autocomplete="off" aria-hidden="true">
<div class="grid grid-cols-2 gap-2 text-sm">
<label class="border-2 border-blue-200 rounded-2xl p-3 text-center cursor-pointer has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 transition flex flex-col items-center justify-center gap-1">
    <input type="radio" name="role" value="enduser" checked class="sr-only">
    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-0.5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
    </div>
    <b>Reguler</b>
    <div class="text-[11px] text-slate-500">Belanja satuan</div>
</label>
<label class="border-2 border-cyan-200 rounded-2xl p-3 text-center cursor-pointer has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-50 transition flex flex-col items-center justify-center gap-1">
    <input type="radio" name="role" value="reseller" class="sr-only">
    <div class="w-8 h-8 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center mb-0.5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
    </div>
    <b>Reseller</b>
    <div class="text-[11px] text-slate-500">Harga khusus</div>
</label>
</div>
<div id="data-reseller" class="space-y-2 rounded-2xl border border-cyan-200 bg-cyan-50/50 p-3" style="display:none">
<div class="text-xs font-extrabold text-cyan-800 flex items-center gap-1.5">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/></svg>
    <span>Data pribadi reseller (dilihat admin untuk approval)</span>
</div>
<input name="toko_nama" placeholder="Nama toko *" maxlength="60" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm">
<input name="nik" inputmode="numeric" placeholder="NIK KTP 16 digit *" maxlength="16" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm">
<label class="block text-xs text-slate-500">
    <span class="flex items-center gap-1 font-bold text-slate-700 mb-1">
        <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
        <span>Foto KTP (opsional)</span>
    </span>
    <input type="file" name="ktp_foto" accept="image/*" class="w-full text-xs">
</label>
</div>
<script>
(function(){var rs=document.querySelectorAll('input[name=role]'),box=document.getElementById('data-reseller');function sync(){var r=document.querySelector('input[name=role]:checked');var on=r&&r.value==='reseller';box.style.display=on?'':'none';box.querySelectorAll('[name=toko_nama],[name=nik]').forEach(function(i){i.required=!!on;});}rs.forEach(function(i){i.addEventListener('change',sync);});sync();})();
</script>
<button class="w-full text-white font-extrabold py-3 rounded-2xl shadow-sm hover:opacity-95 transition" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Daftar Sekarang</button>
</form>
<a href="/login" class="block text-center text-sm mt-2">Sudah punya? <b class="text-blue-700">Login</b></a>
</div>
</div>
@endsection
