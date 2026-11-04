@extends('layouts.web')
@section('title','Login')
@section('content')
<div class="grid gap-6 items-center mt-4 auth-split lg:grid-cols-2">
<div class="auth-side p-8 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
<div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
<div class="flex items-center gap-3 relative">
<img src="/images/logo-bubee.png" class="w-16 h-16 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)" alt="Bubee">
</div>
<div class="font-extrabold text-2xl mt-3 relative">Masuk ke Bubee</div>
<div class="text-sm opacity-70 relative">1 akun untuk 3 mode: Admin • Reseller • Reguler</div>
<div class="mt-4 relative">
<div class="text-xs text-cyan-200 font-bold mb-2 flex items-center gap-1.5">
    <svg class="w-3.5 h-3.5 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.31L4.69 18.5a2 2 0 0 0 1.62 3h11.38a2 2 0 0 0 1.62-3L14 9.31V2"/><line x1="8.5" y1="2" x2="15.5" y2="2"/><line x1="14" y1="9.3" x2="10" y2="9.3"/></svg>
    <span>1-Klik Masuk Akun Demo:</span>
</div>
<div class="grid grid-cols-3 gap-2 text-xs">
<button type="button" onclick="isiAkun('admin@bubee.id','password', true)" class="bg-white/15 hover:bg-white/25 active:scale-95 transition rounded-2xl p-3 text-center border border-white/20 cursor-pointer text-white flex flex-col items-center justify-center gap-1">
    <svg class="w-5 h-5 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14v2H5v-2z"/></svg>
    <span class="font-bold text-xs">Admin</span>
</button>
<button type="button" onclick="isiAkun('reseller@bubee.id','password', true)" class="bg-white/15 hover:bg-white/25 active:scale-95 transition rounded-2xl p-3 text-center border border-white/20 cursor-pointer text-white flex flex-col items-center justify-center gap-1">
    <svg class="w-5 h-5 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
    <span class="font-bold text-xs">Reseller</span>
</button>
<button type="button" onclick="isiAkun('user@bubee.id','password', true)" class="bg-white/15 hover:bg-white/25 active:scale-95 transition rounded-2xl p-3 text-center border border-white/20 cursor-pointer text-white flex flex-col items-center justify-center gap-1">
    <svg class="w-5 h-5 text-blue-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
    <span class="font-bold text-xs">Reguler</span>
</button>
</div>
</div>
</div>
<div class="auth-form bg-white shadow-card p-8" style="border-radius:2rem">
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
<button type="submit" class="w-full font-extrabold py-3 rounded-2xl text-white transition active:scale-98 shadow-sm" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Masuk</button>
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
                btn.innerText = 'Memproses Masuk...';
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
