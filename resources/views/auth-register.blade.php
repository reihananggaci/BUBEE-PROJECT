@extends('layouts.app')
@section('title','Daftar')
@section('back', '/login')
@section('content')
<div class="auth-split">
<div class="auth-side p-6 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
<div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
<div class="flex items-center gap-3 relative">
<img src="/images/logo-bubee.png" class="w-16 h-16 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)">
<div class="hexbee" style="width:44px;height:49px;font-size:22px">🐝</div>
</div>
<div class="font-extrabold text-xl mt-2 relative">Gabung Bubee ✨</div>
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
<label class="border-2 border-blue-200 rounded-2xl p-3 text-center cursor-pointer has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50"><input type="radio" name="role" value="enduser" checked class="sr-only"><div class="text-2xl">🙋</div><b>Reguler</b><div class="text-[11px] text-slate-500">Belanja satuan</div></label>
<label class="border-2 border-cyan-200 rounded-2xl p-3 text-center cursor-pointer has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-50"><input type="radio" name="role" value="reseller" class="sr-only"><div class="text-2xl">🏪</div><b>Reseller</b><div class="text-[11px] text-slate-500">Harga khusus</div></label>
</div>
<div id="data-reseller" class="space-y-2 rounded-2xl border border-cyan-200 bg-cyan-50/50 p-3" style="display:none">
<div class="text-xs font-extrabold text-cyan-800">🏪 Data pribadi reseller (dilihat admin untuk approval)</div>
<input name="toko_nama" placeholder="Nama toko *" maxlength="60" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm">
<input name="nik" inputmode="numeric" placeholder="NIK KTP 16 digit *" maxlength="16" class="w-full border border-blue-100 rounded-2xl px-4 py-2.5 text-sm">
<label class="block text-xs text-slate-500">📷 Foto KTP (opsional)<input type="file" name="ktp_foto" accept="image/*" class="w-full text-xs mt-1"></label>
</div>
<script>
(function(){var rs=document.querySelectorAll('input[name=role]'),box=document.getElementById('data-reseller');function sync(){var r=document.querySelector('input[name=role]:checked');var on=r&&r.value==='reseller';box.style.display=on?'':'none';box.querySelectorAll('[name=toko_nama],[name=nik]').forEach(function(i){i.required=!!on;});}rs.forEach(function(i){i.addEventListener('change',sync);});sync();})();
</script>
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">DAFTAR 🚀</button>
</form>
<a href="/login" class="block text-center text-sm mt-2">Sudah punya? <b class="text-blue-700">Login</b></a>
</div>
</div>
@endsection
