@extends('layouts.app')
@section('title','Verifikasi OTP')
@section('back', '/register')
@section('content')
<div class="auth-split">
<div class="auth-side p-6 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
<div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
<div class="font-extrabold text-xl mt-2 relative flex items-center gap-2">
    <svg class="w-6 h-6 text-cyan-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
    <span>Cek Email Kamu</span>
</div>
<div class="text-xs opacity-70 relative">Kode 6 digit dikirim ke <b>{{ $mask }}</b>. Berlaku 10 menit.</div>
</div>
<div class="auth-form glass shadow-card p-6" style="border-radius:2rem">
<form method="POST" action="/verifikasi-otp" class="space-y-2">@csrf
<input name="kode" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="• • • • • •" class="w-full rounded-2xl px-4 py-3 text-sm text-slate-900 border border-blue-100 text-center font-extrabold tracking-widest" required>
<button class="w-full font-extrabold py-3 rounded-2xl text-white flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
    <span>Verifikasi OTP</span>
</button>
</form>
<form method="POST" action="/verifikasi-otp/kirim-ulang" class="mt-2">@csrf
<button class="w-full font-bold py-2.5 rounded-2xl text-sm bg-slate-100 text-slate-700 flex items-center justify-center gap-1.5">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
    <span>Kirim Ulang Kode</span>
</button>
</form>
@php $waAdmin = \App\Models\Setting::get('wa_admin','6281234567890'); @endphp
<a href="https://wa.me/{{ $waAdmin }}?text={{ urlencode('Halo Bubee, kode OTP saya tidak masuk ('.$email.')') }}" target="_blank" class="flex items-center justify-center gap-1.5 text-center text-sm mt-3 font-bold text-emerald-700">
    <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Kode tidak masuk? Chat Admin</span>
</a>
<div class="text-center text-xs text-slate-400 mt-2">Sesi habis? <a href="/login" class="font-bold text-blue-700">Login ulang</a> — kode baru otomatis dikirim.</div>
</div>
</div>
@endsection
