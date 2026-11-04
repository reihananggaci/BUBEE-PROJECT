@extends('layouts.web')
@section('title', 'Lupa Password')
@section('content')
<div class="grid gap-6 items-center mt-4 auth-split lg:grid-cols-2">
  <div class="auth-side p-8 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
    <div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
    <div class="flex items-center gap-3 relative">
      <img src="/images/logo-bubee.png" class="w-16 h-16 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)" alt="Bubee">
    </div>
    <div class="font-extrabold text-2xl mt-4 relative">Reset Password Akun</div>
    <div class="text-sm opacity-80 mt-1 relative leading-relaxed">
      Lupa kata sandi? Masukkan email akun Bubee Anda. Kami akan mengirimkan kode verifikasi OTP 6 digit secara instan untuk mengatur ulang kata sandi Anda.
    </div>
    <div class="mt-6 p-4 rounded-2xl bg-white/10 border border-white/20 text-xs text-blue-100 relative space-y-1">
      <div class="font-bold text-white flex items-center gap-1.5">
        <svg class="w-4 h-4 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <span>Keamanan Terjamin</span>
      </div>
      <div>Kode OTP hanya berlaku selama 10 menit dan dijaga dengan proteksi anti-bruteforce.</div>
    </div>
  </div>

  <div class="auth-form bg-white shadow-card p-8" style="border-radius:2rem">
    <div class="mb-4">
      <h2 class="text-xl font-extrabold text-slate-900">Lupa Password?</h2>
      <p class="text-xs text-slate-500 mt-0.5">Ketikkan email yang terdaftar di akun Bubee Anda</p>
    </div>

    @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 text-xs p-3 rounded-2xl mb-4 font-semibold">
        @foreach($errors->all() as $error)
          <div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>{{ $error }}</span></div>
        @endforeach
      </div>
    @endif

    @if(session('ok'))
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs p-3 rounded-2xl mb-4 font-semibold">
        ✓ {{ session('ok') }}
      </div>
    @endif

    <form method="POST" action="/lupa-password" class="space-y-3">
      @csrf
      <div>
        <label class="text-xs font-bold text-slate-700 mb-1 block">Alamat Email Terdaftar:</label>
        <input name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" class="w-full rounded-2xl px-4 py-3 text-sm text-slate-900 border border-blue-100 focus:border-blue-600 focus:outline-none" required autofocus>
      </div>

      <input type="text" name="website" value="" style="display:none!important" tabindex="-1" autocomplete="off" aria-hidden="true">

      <button type="submit" class="w-full font-extrabold py-3.5 rounded-2xl text-white text-xs tracking-wide shadow-md transition hover:brightness-110 active:scale-[0.99]" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">
        Kirim Kode OTP Reset
      </button>
    </form>

    <div class="mt-4 pt-4 border-t border-slate-100 text-center text-xs">
      <a href="/login" class="font-bold text-blue-700 hover:text-blue-900 flex items-center justify-center gap-1">
        <span>← Kembali ke Halaman Login</span>
      </a>
    </div>
  </div>
</div>
@endsection

