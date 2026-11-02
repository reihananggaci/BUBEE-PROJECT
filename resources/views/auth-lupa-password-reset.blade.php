@extends('layouts.app')
@section('title', 'Buat Password Baru')
@section('back', '/lupa-password')
@section('content')
<div class="auth-split">
  <div class="auth-side p-6 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
    <div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
    <div class="flex items-center gap-3 relative">
      <img src="/images/logo-bubee.png" class="w-14 h-14 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)">
      <div class="hexbee" style="width:40px;height:45px;font-size:20px">🔐</div>
    </div>
    <div class="font-extrabold text-xl mt-2 relative">Verifikasi & Sandi Baru</div>
    <div class="text-xs opacity-80 relative mt-0.5">Kode 6 digit dikirim ke <b>{{ $mask }}</b> (berlaku 10 menit).</div>
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

    <form method="POST" action="/lupa-password/verifikasi" class="space-y-3">
      @csrf

      <div>
        <label class="text-[11px] font-bold text-slate-700 mb-1 block">Kode OTP (6 Digit):</label>
        <input name="kode" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="• • • • • •" class="w-full rounded-2xl px-4 py-3 text-base text-slate-900 border border-blue-200 text-center font-extrabold tracking-widest focus:border-blue-600 focus:outline-none bg-blue-50/30" required autofocus>
      </div>

      <div>
        <label class="text-[11px] font-bold text-slate-700 mb-1 block">Password Baru (Min. 8 Karakter):</label>
        <input name="password" type="password" placeholder="Password baru" class="w-full rounded-2xl px-4 py-2.5 text-sm text-slate-900 border border-blue-100 focus:border-blue-600 focus:outline-none" required minlength="8">
      </div>

      <div>
        <label class="text-[11px] font-bold text-slate-700 mb-1 block">Konfirmasi Password Baru:</label>
        <input name="password_confirmation" type="password" placeholder="Ulangi password baru" class="w-full rounded-2xl px-4 py-2.5 text-sm text-slate-900 border border-blue-100 focus:border-blue-600 focus:outline-none" required minlength="8">
      </div>

      <button type="submit" class="w-full font-extrabold py-3 rounded-2xl text-white text-xs tracking-wide shadow" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">
        SIMPAN PASSWORD BARU ✅
      </button>
    </form>

    <form method="POST" action="/lupa-password/kirim-ulang" class="mt-2.5">
      @csrf
      <button type="submit" class="w-full font-bold py-2 rounded-2xl text-xs bg-slate-100 text-slate-700">
        ↺ Kirim Ulang Kode OTP
      </button>
    </form>

    @php $waAdmin = \App\Models\Setting::get('wa_admin','6281234567890'); @endphp
    <div class="mt-3 text-center">
      <a href="https://wa.me/{{ $waAdmin }}?text={{ urlencode('Halo Admin Bubee, saya mengalami kendala reset password untuk akun '.$email) }}" target="_blank" class="text-xs font-bold text-emerald-700">
        💬 Hubungi Admin via WA
      </a>
    </div>
  </div>
</div>
@endsection

