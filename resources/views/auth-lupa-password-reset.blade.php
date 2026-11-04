@extends('layouts.app')
@section('title', 'Buat Password Baru')
@section('back', '/lupa-password')
@section('content')
<div class="auth-split">
  <div class="auth-side p-6 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
    <div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
    <div class="flex items-center gap-3 relative">
      <img src="/images/logo-bubee.png" class="w-14 h-14 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)">
      <div class="hexbee" style="width:40px;height:45px">
    <svg class="w-5 h-5 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
</div>
    </div>
    <div class="font-extrabold text-xl mt-2 relative">Verifikasi & Sandi Baru</div>
    <div class="text-xs opacity-80 relative mt-0.5">Kode 6 digit dikirim ke <b>{{ $mask }}</b> (berlaku 10 menit).</div>
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

      <button type="submit" class="w-full font-extrabold py-3 rounded-2xl text-white text-xs tracking-wide shadow flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        <span>Simpan Password Baru</span>
      </button>
    </form>

    <form method="POST" action="/lupa-password/kirim-ulang" class="mt-2.5">
      @csrf
      <button type="submit" class="w-full font-bold py-2 rounded-2xl text-xs bg-slate-100 text-slate-700 flex items-center justify-center gap-1.5">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
        <span>Kirim Ulang Kode OTP</span>
      </button>
    </form>

    @php $waAdmin = \App\Models\Setting::get('wa_admin','6281234567890'); @endphp
    <div class="mt-3 text-center">
      <a href="https://wa.me/{{ $waAdmin }}?text={{ urlencode('Halo Admin Bubee, saya mengalami kendala reset password untuk akun '.$email) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        <span>Hubungi Admin via WhatsApp</span>
      </a>
    </div>
  </div>
</div>
@endsection

