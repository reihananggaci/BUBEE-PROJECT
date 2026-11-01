@extends('layouts.web')
@section('title','Verifikasi OTP')
@section('content')
<div class="grid gap-6 items-center mt-4 auth-split lg:grid-cols-2">
<div class="auth-side p-8 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
<div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
<div class="font-extrabold text-2xl mt-3 relative">📩 Cek Email Kamu</div>
<div class="text-sm opacity-70 relative">Kode 6 digit dikirim ke <b>{{ $mask }}</b>. Berlaku 10 menit, maks 5x salah.</div>
</div>
<div class="auth-form bg-white shadow-card p-8" style="border-radius:2rem">
<form method="POST" action="/verifikasi-otp" class="space-y-2">@csrf
<input name="kode" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="• • • • • •" class="w-full rounded-2xl px-4 py-3 text-sm text-slate-900 border border-blue-100 text-center font-extrabold tracking-widest" required>
<button class="w-full font-extrabold py-3 rounded-2xl text-white" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">VERIFIKASI ✅</button>
</form>
<form method="POST" action="/verifikasi-otp/kirim-ulang" class="mt-2">@csrf
<button class="w-full font-bold py-2.5 rounded-2xl text-sm bg-slate-100 text-slate-700">↻ Kirim Ulang Kode</button>
</form>
@php $waAdmin = \App\Models\Setting::get('wa_admin','6281234567890'); @endphp
<a href="https://wa.me/{{ $waAdmin }}?text={{ urlencode('Halo Bubee, kode OTP saya tidak masuk ('.$email.')') }}" target="_blank" class="block text-center text-sm mt-3 font-bold text-emerald-700">💬 Kode tidak masuk? Chat Admin</a>
<div class="text-center text-xs text-slate-400 mt-2">Sesi habis? <a href="/login" class="font-bold text-blue-700">Login ulang</a> — kode baru otomatis dikirim.</div>
</div>
</div>
@endsection
