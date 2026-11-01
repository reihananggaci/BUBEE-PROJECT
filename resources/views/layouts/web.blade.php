<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bubee Web • @yield('title','Belanja')</title>
<link rel="manifest" href="/manifest.webmanifest">
<meta name="theme-color" content="#0B2A6B">
<link rel="icon" href="/images/logo-bubee.png">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{bubee:{50:'#EEF4FF',100:'#D8E6FF',600:'#1D4ED8',800:'#0B2A6B',900:'#0A357A'}},boxShadow:{card:'0 8px 24px rgba(15,23,42,.08)'}}}}</script>
<style>
:root{--bubee-900:#0A357A;--bubee-600:#1D4ED8;--line:#e8edf5}
*{-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{background:radial-gradient(1200px 400px at 50% -120px,#dbe7ff 0%,#eef2f7 55%,#eef2f7 100%);font-family:'Plus Jakarta Sans',sans-serif;margin:0;min-height:100vh;color:#0f172a}
::selection{background:#1D4ED8;color:#fff}
a,button{transition:filter .15s ease,transform .15s ease,background .15s ease}
a:active,button:active{filter:brightness(.96)}
:focus-visible{outline:2px solid #1D4ED8;outline-offset:2px;border-radius:8px}
/* Navbar */
.webnav a{font-size:13px;font-weight:800;color:#dbe7ff;text-decoration:none;padding:9px 16px;border-radius:9999px;white-space:nowrap}
.webnav a:hover{background:rgba(255,255,255,.16);color:#fff}
.webnav a.active{background:#fff;color:#0B2A6B;box-shadow:0 2px 10px rgba(0,0,0,.25)}
.whdr{box-shadow:0 4px 20px rgba(10,53,122,.35)}
/* Kartu & tabel */
main .shadow-card{box-shadow:0 8px 24px rgba(15,23,42,.08)}
main table{border-collapse:separate;border-spacing:0;width:100%}
main table thead th{position:sticky;top:0;background:#f4f7fd;z-index:1}
main table tbody tr{transition:background .12s ease}
main table tbody tr:hover{background:#f6f9ff}
main table tbody tr:last-child td:first-child{border-bottom-left-radius:1.25rem}
main table tbody tr:last-child td:last-child{border-bottom-right-radius:1.25rem}
/* Form */
main input:not([type=checkbox]):not([type=radio]):not([type=file]),main select,main textarea{transition:border-color .15s ease,box-shadow .15s ease;background:#fff}
main input:not([type=checkbox]):not([type=radio]):not([type=file]):focus,main select:focus,main textarea:focus{border-color:#1D4ED8 !important;box-shadow:0 0 0 3px rgba(29,78,216,.15);outline:none}
main input[type=file]{cursor:pointer}
/* Details / summary */
main details>summary{transition:color .15s ease}
main details>summary:hover{color:#1D4ED8}
/* Footer */
.wfoot a:hover{color:#0B2A6B}
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
</style>
</head>
<body>
<header class="whdr sticky top-0 z-40" style="background:linear-gradient(120deg,#0A357A,#1D4ED8 60%,#0ea5e9);border-bottom:1px solid rgba(255,255,255,.25)">
<div class="max-w-7xl mx-auto flex items-center gap-3 px-5 py-3">
<a href="/" class="flex items-center gap-2" style="text-decoration:none"><img src="/images/logo-bubee.png" alt="Bubee" style="width:40px;height:40px;border-radius:12px;background:#fff;padding:2px"><span style="font-weight:800;letter-spacing:.16em;color:#fff">BUBEE</span></a>
@php $dseg=request()->segment(1); @endphp
<nav class="webnav hidden md:flex items-center gap-1 mx-auto" aria-label="Navigasi web">
<a href="/" class="{{ $dseg===''?'active':'' }}">Beranda</a>
<a href="/keranjang" class="{{ $dseg==='keranjang'?'active':'' }}">Keranjang</a>
<a href="/pesanan" class="{{ $dseg==='pesanan'?'active':'' }}">Pesanan</a>
@if(auth()->check() && auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved')
<a href="/reseller" class="{{ $dseg==='reseller'?'active':'' }}">Reseller</a>
@endif
@if(auth()->check() && auth()->user()->role==='admin')
<a href="/admin" class="{{ $dseg==='admin'?'active':'' }}">Admin</a>
@else
<a href="{{ auth()->check() ? '/akun' : '/login' }}" class="{{ in_array($dseg,['akun','login','register'])?'active':'' }}">Akun</a>
@endif
<a href="/bantuan" class="{{ $dseg==='bantuan'?'active':'' }}">Bantuan</a>
</nav>
<span class="md:hidden" style="flex:1"></span>
@if(auth()->check())
<a href="/akun" class="text-white text-xs font-bold hidden md:block" style="text-decoration:none">{{ explode(' ',auth()->user()->name)[0] }} ▾</a>
<form method="POST" action="/logout" style="margin:0" class="hidden md:block">@csrf<button style="background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);color:#fff;border-radius:10px;padding:7px 12px;font-size:11px;font-weight:800;cursor:pointer">Keluar</button></form>
@else
<a href="/login" class="hidden md:block" style="background:#fff;color:#0B2A6B;border-radius:10px;padding:8px 16px;font-size:12px;font-weight:800;text-decoration:none">Masuk</a>
@endif
</div>
</header>
<main class="max-w-7xl mx-auto px-5 py-6">
@if(session('ok'))<div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;font-size:13px;padding:10px 12px;border-radius:12px;margin-bottom:12px">✓ {{ session('ok') }}</div>@endif
@if($errors->any())<div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:13px;padding:10px 12px;border-radius:12px;margin-bottom:12px">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>@endif
@yield('content')
</main>
<footer class="wfoot max-w-7xl mx-auto px-5 pt-6 pb-10 text-center text-xs text-slate-400">
<div class="flex items-center justify-center gap-2 font-extrabold tracking-widest text-slate-500"><img src="/images/logo-bubee.png" alt="" style="width:20px;height:20px;border-radius:6px">BUBEE</div>
<div class="mt-1">Stok real-time • JNE J&T SPX • <a href="/bantuan" style="color:#1D4ED8;font-weight:800;text-decoration:none">Bantuan</a> • <a href="/tampilan/hp" style="margin-left:4px;color:#1D4ED8;font-weight:800;text-decoration:none">📱 Versi HP</a></div>
</footer>
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
