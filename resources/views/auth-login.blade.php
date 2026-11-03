@extends('layouts.app')
@section('title','Login')
@section('back', '/')
@section('content')
<div class="auth-split">
<div class="auth-side p-6 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
<div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
<div class="flex items-center gap-3 relative">
<img src="/images/logo-bubee.png" class="w-16 h-16 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)">
<div class="hexbee" style="width:44px;height:49px;font-size:22px">🐝</div>
</div>
<div class="font-extrabold text-xl mt-2 relative">Masuk ke Bubee 🤖</div>
<div class="text-xs opacity-70 relative">1 APK untuk 3 mode: Admin • Reseller • Reguler</div>
<div class="mt-3 relative">
<div class="text-[10px] text-cyan-200 font-bold mb-1.5 flex items-center gap-1">🧪 1-Klik Masuk Akun Demo:</div>
<div class="grid grid-cols-3 gap-1.5 text-[10px]">
<button type="button" onclick="isiAkun('admin@bubee.id','password', true)" class="bg-white/15 hover:bg-white/25 active:scale-95 transition rounded-xl p-2 text-center border border-white/20 cursor-pointer text-white">👑<br><b>Admin</b></button>
<button type="button" onclick="isiAkun('reseller@bubee.id','password', true)" class="bg-white/15 hover:bg-white/25 active:scale-95 transition rounded-xl p-2 text-center border border-white/20 cursor-pointer text-white">🏪<br><b>Reseller</b></button>
<button type="button" onclick="isiAkun('user@bubee.id','password', true)" class="bg-white/15 hover:bg-white/25 active:scale-95 transition rounded-xl p-2 text-center border border-white/20 cursor-pointer text-white">🙋<br><b>Reguler</b></button>
</div>
</div>
</div>
<div class="auth-form glass shadow-card p-6" style="border-radius:2rem">
<form method="POST" action="/login" class="space-y-2">@csrf
<input name="email" type="email" value="{{ old('email') }}" placeholder="Email" class="w-full rounded-2xl px-4 py-3 text-sm text-slate-900 border border-blue-100" required autocomplete="email">
<input name="password" type="password" placeholder="Password" class="w-full rounded-2xl px-4 py-3 text-sm text-slate-900 border border-blue-100" required autocomplete="current-password">
<div class="flex justify-end pr-1">
<a href="/lupa-password" class="text-xs text-blue-600 hover:text-blue-800 font-bold hover:underline">Lupa password?</a>
</div>
<input type="text" name="website" value="" style="display:none!important" tabindex="-1" autocomplete="off" aria-hidden="true">
@php
$tsAktif = \App\Services\Turnstile::aktif();
$tsSite = \App\Services\Turnstile::siteKey();
@endphp
@if($tsAktif && $tsSite)
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<div class="cf-turnstile" data-sitekey="{{ $tsSite }}" data-theme="light"></div>
@endif
<button type="submit" class="w-full font-extrabold py-3 rounded-2xl text-white transition active:scale-98" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">LOGIN ⚡</button>
</form>
<a href="/register" class="block text-center text-sm mt-3">Belum punya akun? <b class="text-blue-700">Daftar →</b></a>
</div>
</div>
<script>
function isiAkun(email, pass, submitNow = true){
    const form = document.querySelector('form[action="/login"]');
    if(!form) return;
    const inpEmail = form.querySelector('input[name="email"]');
    const inpPass = form.querySelector('input[name="password"]');
    if(inpEmail && inpPass){
        inpEmail.value = email;
        inpPass.value = pass;
        inpEmail.classList.add('ring-2', 'ring-blue-500');
        if(submitNow){
            const btn = form.querySelector('button[type="submit"]');
            if(btn){
                btn.innerText = 'Memproses Masuk... ⚡';
                btn.style.opacity = '0.85';
            }
            setTimeout(() => { form.submit(); }, 120);
        } else {
            setTimeout(() => inpEmail.classList.remove('ring-2', 'ring-blue-500'), 600);
        }
    }
}
</script>
@endsection
