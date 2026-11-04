@extends('layouts.app')
@section('title', 'Lupa Password')
@section('back', '/login')
@section('content')
<div class="auth-split">
  <div class="auth-side p-6 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
    <div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
    <div class="flex items-center gap-3 relative">
      <img src="/images/logo-bubee.png" class="w-14 h-14 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)">
      <div class="hexbee" style="width:40px;height:45px">
    <svg class="w-5 h-5 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8 2 5 5 5 9c0 2.2 1 4.2 2.5 5.5L7 20l5-2 5 2-.5-5.5C18 13.2 19 11.2 19 9c0-4-3-7-7-7z"/><path d="M9 10h6M8 14h8"/></svg>
</div>
    </div>
    <div class="font-extrabold text-xl mt-2 relative">Reset Password</div>
    <div class="text-xs opacity-80 relative mt-0.5">Masukkan email akun Bubee Anda untuk menerima kode OTP verifikasi.</div>
  </div>

  <div class="auth-form glass shadow-card p-6 mt-3" style="border-radius:2rem">
    @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 text-xs p-3 rounded-2xl mb-3 font-semibold space-y-1">
        @foreach($errors->all() as $error)
          <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>{{ $error }}</span>
          </div>
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

      <button type="submit" class="w-full font-extrabold py-3 rounded-2xl text-white text-xs tracking-wide shadow flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        <span>Kirim Kode OTP Reset</span>
      </button>
    </form>

    <a href="/login" class="block text-center text-xs font-bold text-blue-700 mt-4">← Kembali ke Login</a>
  </div>
</div>
@endsection

