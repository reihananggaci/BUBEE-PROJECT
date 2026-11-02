@extends('layouts.app')
@section('title', 'Lupa Password')
@section('back', '/login')
@section('content')
<div class="auth-split">
  <div class="auth-side p-6 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
    <div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
    <div class="flex items-center gap-3 relative">
      <img src="/images/logo-bubee.png" class="w-14 h-14 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)">
      <div class="hexbee" style="width:40px;height:45px;font-size:20px">🐝</div>
    </div>
    <div class="font-extrabold text-xl mt-2 relative">Reset Password 🔒</div>
    <div class="text-xs opacity-80 relative mt-0.5">Masukkan email akun Bubee Anda untuk menerima kode OTP verifikasi.</div>
  </div>

  <div class="auth-form glass shadow-card p-6 mt-3" style="border-radius:2rem">
    @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 text-xs p-3 rounded-2xl mb-3 font-semibold">
        @foreach($errors->all() as $error)
          <div>⚠️ {{ $error }}</div>
        @endforeach
      </div>
    @endif

    @if(session('ok'))
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3 rounded-2xl mb-3 font-semibold">
        ✓ {{ session('ok') }}
      </div>
    @endif

    <form method="POST" action="/lupa-password" class="space-y-3">
      @csrf
      <div>
        <label class="text-[11px] font-bold text-slate-700 mb-1 block">Alamat Email Terdaftar:</label>
        <input name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" class="w-full rounded-2xl px-4 py-3 text-sm text-slate-900 border border-blue-100 focus:border-blue-600 focus:outline-none" required autofocus>
      </div>

      <input type="text" name="website" value="" style="display:none!important" tabindex="-1" autocomplete="off" aria-hidden="true">

      <button type="submit" class="w-full font-extrabold py-3 rounded-2xl text-white text-xs tracking-wide shadow" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">
        KIRIM KODE OTP RESET 🚀
      </button>
    </form>

    <a href="/login" class="block text-center text-xs font-bold text-blue-700 mt-4">← Kembali ke Login</a>
  </div>
</div>
@endsection
