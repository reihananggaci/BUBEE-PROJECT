@extends('layouts.web')
@section('title', 'Buat Password Baru')
@section('content')
<div class="grid gap-6 items-center mt-4 auth-split lg:grid-cols-2">
  <div class="auth-side p-8 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(150deg,#050B2E,#1D4ED8);border-radius:2rem">
    <div class="absolute w-52 h-52 bg-cyan-400/30 blur-3xl -right-10 -top-10"></div>
    <div class="flex items-center gap-3 relative">
      <img src="/images/logo-bubee.png" class="w-16 h-16 rounded-2xl bg-white p-1" style="box-shadow:0 0 22px rgba(56,225,255,.6)" alt="Bubee">
    </div>
    <div class="font-extrabold text-2xl mt-4 relative">Verifikasi Kode & Sandi Baru</div>
    <div class="text-sm opacity-80 mt-1 relative leading-relaxed">
      Kode verifikasi 6 digit telah dikirim ke alamat <b>{{ $mask }}</b>. Masukkan kode tersebut dan buat kata sandi baru untuk akun Anda.
    </div>

    <div class="mt-6 p-4 rounded-2xl bg-white/10 border border-white/20 text-xs text-blue-100 relative space-y-1">
      <div class="font-bold text-white flex items-center gap-1.5">
        <svg class="w-4 h-4 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span>Masa Berlaku Kode</span>
      </div>
      <div>Kode berlaku selama 10 menit. Jangan berikan kode ini kepada pihak mana pun demi keamanan akun Anda.</div>
    </div>
  </div>

  <div class="auth-form bg-white shadow-card p-8" style="border-radius:2rem">
    <div class="mb-4">
      <h2 class="text-xl font-extrabold text-slate-900">Buat Password Baru</h2>
      <p class="text-xs text-slate-500 mt-0.5">Lengkapi kode OTP dan tentukan password baru</p>
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

    <form method="POST" action="/lupa-password/verifikasi" class="space-y-3">
      @csrf

      <div>
        <label class="text-xs font-bold text-slate-700 mb-1 block">Kode OTP (6 Digit):</label>
        <input name="kode" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="• • • • • •" class="w-full rounded-2xl px-4 py-3 text-base text-slate-900 border border-blue-200 text-center font-extrabold tracking-widest focus:border-blue-600 focus:outline-none bg-blue-50/30" required autofocus>
      </div>

      <div>
        <label class="text-xs font-bold text-slate-700 mb-1 block">Password Baru (Min. 8 Karakter):</label>
        <input name="password" type="password" placeholder="Password baru yang kuat" class="w-full rounded-2xl px-4 py-2.5 text-sm text-slate-900 border border-blue-100 focus:border-blue-600 focus:outline-none" required minlength="8">
      </div>

      <div>
        <label class="text-xs font-bold text-slate-700 mb-1 block">Konfirmasi Password Baru:</label>
        <input name="password_confirmation" type="password" placeholder="Ulangi password baru" class="w-full rounded-2xl px-4 py-2.5 text-sm text-slate-900 border border-blue-100 focus:border-blue-600 focus:outline-none" required minlength="8">
      </div>

      <button type="submit" class="w-full font-extrabold py-3.5 rounded-2xl text-white text-xs tracking-wide shadow-md transition hover:brightness-110 active:scale-[0.99] mt-2" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">
        Simpan Password Baru
      </button>
    </form>

    <form method="POST" action="/lupa-password/kirim-ulang" class="mt-3">
      @csrf
      <button type="submit" class="w-full font-bold py-2.5 rounded-2xl text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
        ↺ Kirim Ulang Kode OTP
      </button>
    </form>

    @php $waAdmin = \App\Models\Setting::get('wa_admin','6281234567890'); @endphp
    <div class="mt-4 pt-3 border-t border-slate-100 text-center space-y-1">
      <a href="https://wa.me/{{ $waAdmin }}?text={{ urlencode('Halo Admin Bubee, saya mengalami kendala reset password untuk akun '.$email) }}" target="_blank" class="text-xs font-bold text-emerald-700 hover:underline inline-flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        <span>Kendala Kode? Hubungi Admin WhatsApp</span>
      </a>
      <div class="text-[11px] text-slate-400">
        Ingin ganti email? <a href="/lupa-password" class="text-blue-700 font-bold hover:underline">Ganti Email</a>
      </div>
    </div>
  </div>
</div>
@endsection

