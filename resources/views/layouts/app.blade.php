<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Bubee • @yield('title','Belanja')</title>
<link rel="manifest" href="/manifest.webmanifest">
<meta name="theme-color" content="#0B2A6B">
<link rel="icon" href="/images/logo-bubee.png">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{bubee:{50:'#EEF4FF',100:'#D8E6FF',600:'#1D4ED8',800:'#0B2A6B',900:'#0A357A'}},boxShadow:{card:'0 8px 24px rgba(15,23,42,.08)'}}}}</script>
<style>
:root{--hex:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='49' viewBox='0 0 28 49'%3E%3Cg fill='%23ffffff' fill-opacity='0.12' fill-rule='evenodd'%3E%3Cpath d='M13.99 9.25l13 7.5v15l-13 7.5L1 31.75v-15l12.99-7.5zM3 17.9v12.7l10.99 6.34 11-6.35V17.9l-11-6.34L3 17.9zM0 15l12.98-7.5V0h-2v6.35L0 12.69v2.3zm0 18.5L12.98 41v8h-2v-6.85L0 35.81v-2.3zM15 0v7.5L27.99 15H28v-2.31h-.01L17 6.35V0h-2zm0 49v-8l12.99-7.5H28v2.31h-.01L17 42.15V49h-2z'/%3E%3C/g%3E%3C/svg%3E")}
.hexbee{width:54px;height:60px;display:inline-flex;align-items:center;justify-content:center;font-size:27px;background:linear-gradient(160deg,#fcd34d,#f59e0b 60%,#b45309);clip-path:polygon(50% 0,100% 25%,100% 75%,50% 100%,0 75%,0 25%);filter:drop-shadow(0 4px 8px rgba(180,83,9,.45));flex:none}
body{background:#eef2f7;font-family:'Plus Jakarta Sans',sans-serif;min-height:100dvh;margin:0}
.app-shell{max-width:480px;margin:0 auto;min-height:100dvh;background:#fff;padding-bottom:78px;box-shadow:0 0 40px rgba(15,23,42,.08)}
/* Kartu auth: susun vertikal (mobile murni) */
.auth-split{display:grid;gap:0}
/* Header ramping: gradasi tipis + 1 aksen cahaya, tanpa menu */
.hdr{position:sticky;top:0;z-index:40;background:var(--hex),linear-gradient(120deg,#0A357A 0%,#1D4ED8 60%,#0ea5e9 100%);overflow:hidden}
.hdr::before{content:'';position:absolute;width:220px;height:220px;right:-70px;top:-110px;border-radius:9999px;background:radial-gradient(circle,rgba(255,255,255,.22),transparent 70%)}
.hdr::after{content:'';position:absolute;left:0;right:0;bottom:0;height:1px;background:rgba(255,255,255,.25)}
.hdr-row{position:relative;display:flex;align-items:center;gap:10px;padding:11px 14px}
.logo{width:38px;height:38px;border-radius:12px;object-fit:cover;background:#fff;padding:2px;box-shadow:0 2px 10px rgba(0,0,0,.25)}
.brand{font-weight:800;letter-spacing:.16em;font-size:16px;color:#fff;line-height:1}
.brand small{display:block;font-weight:600;letter-spacing:.08em;font-size:9px;color:rgba(255,255,255,.75);margin-top:3px}
.role{font-size:9px;font-weight:800;padding:4px 8px;border-radius:9999px;background:rgba(255,255,255,.2);color:#fff;letter-spacing:.05em}
.abtn{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:12px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);color:#fff;cursor:pointer;text-decoration:none}
.abtn svg{width:19px;height:19px}
.abtn.solid{background:#fff;color:#0B2A6B;border-color:#fff;font-size:11px;font-weight:800;width:auto;padding:0 14px;height:36px}
/* Bottom nav estetik: putih ramping, ikon SVG garis */
.bottom-nav{position:fixed;bottom:0;left:50%;transform:translateX(-50%);width:100%;max-width:480px;background:rgba(255,255,255,.96);backdrop-filter:blur(12px);border-top:1px solid #e5e9f0;display:flex;z-index:50;padding:6px 4px calc(6px + env(safe-area-inset-bottom))}
.bnav{flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:4px 2px;color:#94a3b8;font-size:9px;font-weight:700;text-decoration:none}
.bnav .pic{display:flex;align-items:center;justify-content:center;width:44px;height:28px;border-radius:9999px}
.bnav svg{width:22px;height:22px}
.bnav.active{color:#1D4ED8}
.bnav.active .pic{background:#dbe7ff}
.glass{background:#fff;border:1px solid #eef2f7}
.toast{margin:10px 12px 0;font-size:13px;padding:10px 12px;border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.toast.err{background:#fef2f2;border-color:#fecaca;color:#991b1b}
.progress-line{height:6px;border-radius:9999px;background:#e5e9f0;overflow:hidden}
.progress-line>div{height:100%;background:#1d4ed8;border-radius:9999px}
.card-hover{transition:.15s}.card-hover:active{transform:scale(.98)}
/* Badge stok menipis berdenyut */
@keyframes chipPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.08)}}
.chip-live{animation:chipPulse 1.6s ease-in-out infinite}
/* Tombol harga + animasi masuk */
.p-title{min-height:2.6em;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.price-slot{min-height:54px;display:flex;flex-direction:column;justify-content:center}
.price-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;min-height:42px;box-sizing:border-box;padding:10px 12px;font-size:12px;font-weight:800;color:#fff;background:linear-gradient(90deg,#0B2A6B,#1D4ED8);border-radius:14px;text-decoration:none;margin:4px 0 8px}
.lihat-btn{display:flex;align-items:center;justify-content:center;width:100%;min-height:42px;box-sizing:border-box}
.price-btn svg{width:14px;height:14px;flex:none}
.price-btn:hover{filter:brightness(1.08)}
.price-pill{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:800;color:#0B2A6B;background:#D8E6FF;padding:4px 10px;border-radius:9999px;text-decoration:none}
.price-pill svg{width:12px;height:12px;flex:none}
/* Input sandi + tombol mata */
.pw-wrap{position:relative}
.pw-wrap>input[type=password],.pw-wrap>input[type=text]{padding-right:44px}
.pw-eye{position:absolute;right:4px;top:50%;transform:translateY(-50%);border:none;background:transparent;cursor:pointer;padding:8px;color:#94a3b8;display:flex;align-items:center}
.pw-eye:hover{color:#0B2A6B}
.pw-eye svg{width:20px;height:20px}
@keyframes bubeeIn{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
.anim-in{opacity:0;animation:bubeeIn .5s ease forwards;animation-delay:var(--d,0ms)}
@media (prefers-reduced-motion:reduce){.anim-in{opacity:1;animation:none}}
/* Bantuan: tombol melayang + popup + panel */
.help-fab{position:fixed;right:max(14px,calc(50% - 226px));bottom:86px;z-index:60;width:52px;height:52px;border-radius:9999px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#fff;background:linear-gradient(135deg,#0B2A6B,#0ea5e9);box-shadow:0 8px 24px rgba(11,42,107,.4)}
.help-fab svg{width:26px;height:26px}
.help-veil{position:fixed;inset:0;z-index:70;background:rgba(5,11,46,.55);display:flex;align-items:flex-end;justify-content:center}
.help-card{width:100%;max-width:480px;background:#fff;border-radius:24px 24px 0 0;padding:20px 16px calc(20px + env(safe-area-inset-bottom));max-height:82dvh;overflow-y:auto}
.help-welcome{width:calc(100% - 32px);max-width:448px;background:#fff;border-radius:24px;padding:22px 18px;box-shadow:0 20px 60px rgba(0,0,0,.35)}
.help-qa{font-size:13px;padding:10px 0;border-bottom:1px solid #eef2f7}
.help-qa summary{font-weight:700;cursor:pointer;list-style:none}
.help-qa summary::-webkit-details-marker{display:none}
.help-qa p{margin:6px 0 0;color:#475569;font-size:12px}
</style>
</head>
<body>
<div class="app-shell">
<header class="hdr">
<div class="hdr-row">
@hasSection('back')<a href="@yield('back')" class="abtn" title="Kembali" aria-label="Kembali"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 5.5 8 12l6.5 6.5"/></svg></a>@endif
<a href="/"><img src="/images/logo-bubee.png" class="logo" alt="Bubee"></a>
<div style="flex:1;min-width:0"><div class="brand">BUBEE<small>STOK REAL-TIME • JNE J&T SPX</small></div></div>
@if(auth()->check())
@php $u=auth()->user(); @endphp
<span class="role">{{ $u->role==='reseller' ? 'RESELLER • '.strtoupper($u->status_reseller) : strtoupper(nama_role($u->role)) }}</span>
<a href="/akun/pengaturan" class="abtn" title="Pengaturan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.9 2.9l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.2a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.9-2.9l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.2a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.9-2.9l.1.1a1.7 1.7 0 0 0 1.9.3h.1a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.2a1.7 1.7 0 0 0 1 1.5h.1a1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.9 2.9l-.1.1a1.7 1.7 0 0 0-.3 1.9v.1a1.7 1.7 0 0 0 1.5 1h.2a2 2 0 1 1 0 4h-.2a1.7 1.7 0 0 0-1.4 1z"/></svg></a>
<a href="/akun" class="abtn" title="Akun saya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.8 4.2-5.5 7.5-5.5s6.1 1.7 7.5 5.5"/></svg></a>
<form method="POST" action="/logout" style="margin:0">@csrf<button class="abtn" title="Keluar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4H6a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h8"/><path d="M10 12h11"/><path d="M18 8.5 21.5 12 18 15.5"/></svg></button></form>
@else
<a href="/login" class="abtn solid">Masuk</a>
@endif
</div>
</header>
@if(session('ok'))<div class="toast">✓ {{ session('ok') }}</div>@endif
@if($errors->any())<div class="toast err">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>@endif
<main style="padding:12px">@yield('content')</main>
<nav class="bottom-nav" aria-label="Navigasi bawah">
@php $seg=request()->segment(1); @endphp
<a href="/" class="bnav {{ $seg===''?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 10.5 12 3.5l8.5 7"/><path d="M5.5 9v11h4.5v-6h4v6h4.5V9"/></svg></span>Home</a>
<a href="/keranjang" class="bnav {{ $seg==='keranjang'?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9.5" cy="19.5" r="1.4"/><circle cx="17" cy="19.5" r="1.4"/><path d="M3 4.5h2l2.3 11h9.9l2-7.5H7"/></svg></span>Cart</a>
<a href="/pesanan" class="bnav {{ $seg==='pesanan'?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg></span>Order</a>
@if(auth()->check() && auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved')
<a href="/reseller" class="bnav {{ $seg==='reseller'?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg></span>Reseller</a>
@endif
@if(auth()->check() && auth()->user()->role==='admin')
<a href="/admin" class="bnav {{ $seg==='admin'?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 2.8v5.4c0 4.6-3 7.6-7 9.3-4-1.7-7-4.7-7-9.3V5.8L12 3z"/><path d="M9.3 11.8l2 2 3.6-4"/></svg></span>Admin</a>
@else
<a href="{{ auth()->check() ? '/akun' : '/login' }}" class="bnav {{ in_array($seg,['akun','login','register'])?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.8 4.2-5.5 7.5-5.5s6.1 1.7 7.5 5.5"/></svg></span>Akun</a>
@endif
</nav>
<div class="text-center py-2 flex items-center justify-center gap-1.5" style="font-size:10px;color:#94a3b8">
    <img src="/images/logo-bubee.png" alt="" class="w-3.5 h-3.5 rounded object-contain inline">
    <span>Bubee • </span>
    <a href="/tampilan/{{ tampilan_aktif()==='web'?'hp':'web' }}" class="inline-flex items-center gap-1 font-extrabold text-blue-700" style="text-decoration:none">
        @if(tampilan_aktif()==='web')
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
            <span>Versi HP</span>
        @else
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
            <span>Versi Web</span>
        @endif
    </a>
</div>
</div>
<script>setTimeout(()=>{document.querySelectorAll('.toast').forEach(t=>t.style.display='none')},4500);</script>
@if(request()->is('/'))
{{-- Tombol bantuan melayang (khusus beranda) --}}
<button class="help-fab" id="helpFab" title="Bantuan" aria-label="Bantuan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.3a2.6 2.6 0 0 1 5 .9c0 1.7-2.4 2-2.4 3.6"/><circle cx="12" cy="17.2" r=".5" fill="currentColor"/></svg></button>
{{-- Panel bantuan --}}
<div class="help-veil" id="helpPanel" style="display:none">
<div class="help-card">
<div class="flex justify-between items-center">
    <b class="flex items-center gap-1.5 text-slate-900">
        <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.3a2.6 2.6 0 0 1 5 .9c0 1.7-2.4 2-2.4 3.6"/><circle cx="12" cy="17.2" r=".5" fill="currentColor"/></svg>
        <span>Butuh bantuan?</span>
    </b>
    <button id="helpClose" style="border:none;background:#f1f5f9;border-radius:10px;width:32px;height:32px;font-weight:800">✕</button>
</div>
<div class="text-xs mt-2" style="color:#64748b">Jawaban cepat + tombol admin di bawah.</div>
<div id="helpList" class="mt-1"><div class="text-xs" style="color:#94a3b8">Memuat…</div></div>
<a href="/bantuan" class="block text-center mt-2 text-sm font-bold" style="color:#1D4ED8">Lihat semua →</a>
@php $waHelp = \App\Models\Setting::get('wa_admin','6281234567890'); @endphp
<a href="https://wa.me/{{ $waHelp }}?text={{ urlencode('Halo Bubee, saya butuh bantuan') }}" target="_blank" class="flex items-center justify-center gap-2 mt-2 text-white font-extrabold py-3 rounded-2xl text-sm" style="background:linear-gradient(90deg,#059669,#00E5FF);text-decoration:none">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Chat Admin via WA</span>
</a>
</div>
</div>
{{-- Popup selamat datang (sekali saja) --}}
<div class="help-veil" id="helpWelcome" style="display:none;align-items:center">
<div class="help-welcome text-center">
<div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mx-auto shadow-sm">
    <img src="/images/logo-bubee.png" alt="" class="w-8 h-8 rounded-lg object-contain">
</div>
<div class="font-extrabold text-lg mt-2">Halo, selamat datang di Bubee!</div>
<div class="text-xs mt-1" style="color:#64748b">Belanja tanpa tanya stok. Mulai dari sini:</div>
<div class="grid grid-cols-3 gap-2 mt-3 text-[11px] font-bold">
<a href="/bantuan?topik=Order" class="flex flex-col items-center justify-center gap-1.5 rounded-2xl p-3" style="background:#EEF4FF;color:#0B2A6B;text-decoration:none">
    <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
    <span>Cara order</span>
</a>
<a href="/pesanan" class="flex flex-col items-center justify-center gap-1.5 rounded-2xl p-3" style="background:#EEF4FF;color:#0B2A6B;text-decoration:none">
    <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg>
    <span>Lacak pesanan</span>
</a>
<a href="https://wa.me/{{ \App\Models\Setting::get('wa_admin','6281234567890') }}?text={{ urlencode('Halo Bubee, saya butuh bantuan') }}" target="_blank" class="flex flex-col items-center justify-center gap-1.5 rounded-2xl p-3" style="background:#EEF4FF;color:#0B2A6B;text-decoration:none">
    <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Chat admin</span>
</a>
</div>
<label class="flex items-center justify-center gap-2 text-[11px] mt-3" style="color:#64748b"><input type="checkbox" id="helpNoMore" checked> Jangan tampilkan lagi</label>
<button id="helpWelcomeOk" class="w-full text-white font-extrabold py-3 rounded-2xl mt-2 text-sm" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">Mulai Belanja</button>
</div>
</div>
<script>
(function(){
const fab=document.getElementById('helpFab'),panel=document.getElementById('helpPanel');
fab.addEventListener('click',async()=>{
panel.style.display='flex';
const box=document.getElementById('helpList');
if(box.dataset.done)return;box.dataset.done='1';
try{const r=await fetch('/faq');const list=await r.json();
box.innerHTML=list.length?list.map(f=>`<details class="help-qa"><summary>${f.pertanyaan}</summary><p>${f.jawaban}</p></details>`).join(''):'<div class="text-xs">Belum ada FAQ.</div>';}
catch(e){box.innerHTML='<div class="text-xs">Gagal memuat. Buka <a href="/bantuan">halaman bantuan</a>.</div>';}
});
document.getElementById('helpClose').addEventListener('click',()=>{panel.style.display='none';});
panel.addEventListener('click',e=>{if(e.target===panel)panel.style.display='none';});
const wel=document.getElementById('helpWelcome');
if(wel&&!localStorage.getItem('bubee_help_seen')){
wel.style.display='flex';
document.getElementById('helpWelcomeOk').addEventListener('click',()=>{
if(document.getElementById('helpNoMore').checked)localStorage.setItem('bubee_help_seen','1');
wel.style.display='none';});
}
})();
</script>
@endif
<script>
/* Toggle mata pada semua input sandi (login, daftar, pengaturan) */
function togglePw(btn){
var wrap=btn.closest('.pw-wrap');
var inp=wrap?wrap.querySelector('input'):null;
if(!inp)return;
var show=inp.type==='password';
inp.type=show?'text':'password';
btn.setAttribute('aria-label',show?'Sembunyikan sandi':'Tampilkan sandi');
btn.title=show?'Sembunyikan sandi':'Tampilkan sandi';
var e=btn.querySelector('.ic-eye'),o=btn.querySelector('.ic-off');
if(e&&o){e.style.display=show?'none':'';o.style.display=show?'':'none';}
}
(function(){
var OPEN='<svg class="ic-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/></svg>';
var SHUT='<svg class="ic-off" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3l18 18"/><path d="M10.6 5.9A9.7 9.7 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17.4 17.4 0 0 1-3.1 3.8M6.6 6.6A16.8 16.8 0 0 0 2.5 12S6 18.5 12 18.5c1.1 0 2.1-.2 3-.5"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>';
document.querySelectorAll('input[type="password"]').forEach(function(inp){
if(inp.closest('.pw-wrap'))return;
var w=document.createElement('div');w.className='pw-wrap';
inp.before(w);w.appendChild(inp);
var b=document.createElement('button');
b.type='button';b.className='pw-eye';b.setAttribute('aria-label','Tampilkan sandi');b.title='Tampilkan sandi';
b.innerHTML=OPEN+SHUT;b.addEventListener('click',function(){togglePw(b);});
w.appendChild(b);
});
})();
</script>
</body>
</html>
