<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Admin Bubee • @yield('title','Dashboard')</title>
<meta name="theme-color" content="#4c1d95">
<link rel="manifest" href="/manifest.webmanifest">
<link rel="icon" href="/images/logo-bubee.png">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{boxShadow:{card:'0 8px 24px rgba(15,23,42,.08)'}}}}</script>
<style>
:root{--hex:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='49' viewBox='0 0 28 49'%3E%3Cg fill='%23ffffff' fill-opacity='0.12' fill-rule='evenodd'%3E%3Cpath d='M13.99 9.25l13 7.5v15l-13 7.5L1 31.75v-15l12.99-7.5zM3 17.9v12.7l10.99 6.34 11-6.35V17.9l-11-6.34L3 17.9zM0 15l12.98-7.5V0h-2v6.35L0 12.69v2.3zm0 18.5L12.98 41v8h-2v-6.85L0 35.81v-2.3zM15 0v7.5L27.99 15H28v-2.31h-.01L17 6.35V0h-2zm0 49v-8l12.99-7.5H28v2.31h-.01L17 42.15V49h-2z'/%3E%3C/g%3E%3C/svg%3E")}
.hexbee{width:54px;height:60px;display:inline-flex;align-items:center;justify-content:center;font-size:27px;background:linear-gradient(160deg,#fcd34d,#f59e0b 60%,#b45309);clip-path:polygon(50% 0,100% 25%,100% 75%,50% 100%,0 75%,0 25%);filter:drop-shadow(0 4px 8px rgba(180,83,9,.45));flex:none}
body{background:#f1eefb;font-family:'Plus Jakarta Sans',sans-serif;min-height:100dvh;margin:0}
.app-shell{max-width:480px;margin:0 auto;min-height:100dvh;background:#fff;padding-bottom:78px;overflow-x:clip;box-shadow:0 0 40px rgba(15,23,42,.08)}
/* Header admin ramping: gradasi ungu tipis + 1 aksen cahaya */
.hdr{position:sticky;top:0;z-index:40;background:var(--hex),linear-gradient(120deg,#2e1065 0%,#7c3aed 60%,#c026d3 100%);overflow:visible}
.hdr::before{content:'';position:absolute;width:220px;height:220px;right:-70px;top:-110px;border-radius:9999px;background:radial-gradient(circle,rgba(255,255,255,.22),transparent 70%)}
.hdr-row{position:relative;display:flex;align-items:center;gap:10px;padding:11px 14px}
.logo{width:38px;height:38px;border-radius:12px;object-fit:cover;background:#fff;padding:2px;box-shadow:0 2px 10px rgba(0,0,0,.25)}
.brand{font-weight:800;letter-spacing:.16em;font-size:16px;color:#fff;line-height:1}
.brand small{display:block;font-weight:600;letter-spacing:.08em;font-size:9px;color:rgba(255,255,255,.75);margin-top:3px}
.abtn{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:12px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);color:#fff;cursor:pointer;text-decoration:none}
.abtn svg{width:19px;height:19px}
/* Menu hamburger: tersembunyi, dibuka按需 */
.hmenu{position:relative}
.hmenu summary{list-style:none}
.hmenu summary::-webkit-details-marker{display:none}
.hdrop{position:absolute;right:0;top:44px;width:230px;background:#fff;border:1px solid #ede9fe;border-radius:16px;box-shadow:0 16px 40px rgba(46,16,101,.25);padding:6px;z-index:60;max-height:calc(100vh - 90px);overflow-y:auto}
.hdrop-label{font-size:10px;font-weight:800;letter-spacing:.08em;color:#8b5cf6;padding:8px 10px 4px;text-transform:uppercase}
.hdrop-sep{height:1px;background:#f1f5f9;margin:4px 6px}
.hdrop a{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:11px;font-size:12px;font-weight:700;color:#334155;text-decoration:none}
.hdrop a svg{width:18px;height:18px;color:#7c3aed}
.hdrop a.active{background:#f3e8ff;color:#6d28d9}
.hdrop a:active{background:#f3e8ff}
/* Bottom nav estetik */
.bottom-nav{position:fixed;bottom:0;left:50%;transform:translateX(-50%);width:100%;max-width:480px;background:rgba(255,255,255,.96);backdrop-filter:blur(12px);border-top:1px solid #e5e9f0;display:flex;z-index:50;padding:6px 4px calc(6px + env(safe-area-inset-bottom))}
.bnav{flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;padding:4px 2px;color:#94a3b8;font-size:9px;font-weight:700;text-decoration:none}
.bnav .pic{display:flex;align-items:center;justify-content:center;width:44px;height:28px;border-radius:9999px}
.bnav svg{width:22px;height:22px}
.bnav.active{color:#7c3aed}
.bnav.active .pic{background:#ede9fe}
.glass{background:#fff;border:1px solid #eef2f7}
.toast{margin:10px 12px 0;font-size:13px;padding:10px 12px;border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.progress-line{height:6px;border-radius:9999px;background:#ede9fe;overflow:hidden}
.progress-line>div{height:100%;background:#7c3aed;border-radius:9999px}
</style>
</head>
<body>
<div class="app-shell">
<header class="hdr">
<div class="hdr-row">
@hasSection('back')<a href="@yield('back')" class="abtn" title="Kembali" aria-label="Kembali"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 5.5 8 12l6.5 6.5"/></svg></a>@endif
<a href="/admin"><img src="/images/logo-bubee.png" class="logo" alt="Bubee"></a>
<div style="flex:1;min-width:0"><div class="brand">BUBEE ADMIN<small>COMMAND CENTER</small></div></div>
<a href="/" class="abtn" title="Lihat toko"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg></a>
<a href="/akun/pengaturan" class="abtn" title="Pengaturan akun"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M19.1 4.9 17 7M7 17l-2.1 2.1"/></svg></a>
@php $notifGagalM = \App\Models\NotificationLog::where('status','!=','terkirim')->where('created_at','>=',now()->subDays(7))->count(); @endphp
<a href="/admin/notifikasi" class="abtn" title="Notifikasi" style="position:relative"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>@if($notifGagalM>0)<span style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:#fff;font-size:10px;font-weight:800;min-width:18px;height:18px;line-height:18px;text-align:center;border-radius:9999px;padding:0 4px">{{ $notifGagalM>99?'99+':$notifGagalM }}</span>@endif</a>
<details class="hmenu">
<summary class="abtn" title="Menu admin"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></svg></summary>
@php $seg2=request()->segment(2)??''; @endphp
<div class="hdrop">
<div class="hdrop-label">Ringkasan</div>
<a href="/admin" class="{{ $seg2==''?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 10.5 12 3.5l8.5 7"/><path d="M5.5 9v11h4.5v-6h4v6h4.5V9"/></svg>Dashboard</a>
<a href="/admin/laporan" class="{{ $seg2=='laporan'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M5 20v-7"/><path d="M11 20V5"/><path d="M17 20v-10"/><path d="M3 20h18"/></svg>Laporan</a>

<div class="hdrop-sep"></div>
<div class="hdrop-label">Penjualan</div>
<a href="/admin/verifikasi" class="{{ $seg2=='verifikasi'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 2.8v5.4c0 4.6-3 7.6-7 9.3-4-1.7-7-4.7-7-9.3V5.8L12 3z"/><path d="M9.3 11.8l2 2 3.6-4"/></svg>Verifikasi Bayar</a>
<a href="/admin/order" class="{{ $seg2=='order'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12V21l-3-2-3 2-3-2-3 2v-17.5z"/><path d="M9 8.5h6M9 12h6"/></svg>Pesanan</a>
<a href="/admin/retur" class="{{ $seg2=='retur'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V5.5A1.5 1.5 0 0 1 5.5 4H9"/><path d="M4 5.5A9 9 0 1 1 3 13"/><path d="M3 13h5"/></svg>Retur</a>

<div class="hdrop-sep"></div>
<div class="hdrop-label">Gudang & Stok</div>
<a href="/admin/produk" class="{{ $seg2=='produk'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg>Stok & Barang</a>
<a href="/admin/penerimaan" class="{{ $seg2=='penerimaan'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Barang Masuk</a>
<a href="/admin/opname" class="{{ $seg2=='opname'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3 8-8"/><path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"/></svg>Stock Opname</a>
<a href="/admin/mutasi" class="{{ $seg2=='mutasi'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h13"/><path d="M14 3.5 17.5 7 14 10.5"/><path d="M20 17H7"/><path d="M10 13.5 6.5 17 10 20.5"/></svg>Mutasi Stok</a>

<div class="hdrop-sep"></div>
<div class="hdrop-label">Pengguna</div>
<a href="/admin/reseller" class="{{ $seg2=='reseller'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>Reseller</a>
<a href="/admin/users" class="{{ $seg2=='users'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8.5" r="3"/><path d="M2.8 19.5c1.2-3 3.4-4.5 6.2-4.5s5 1.5 6.2 4.5"/><circle cx="16.8" cy="9.5" r="2.4"/><path d="M16 15c2.3.2 4 1.5 5 4"/></svg>Data User</a>
<a href="/admin/ulasan" class="{{ $seg2=='ulasan'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5l2.6 5.4 5.9.8-4.3 4.1 1 5.8-5.2-2.8-5.2 2.8 1-5.8L2.5 9.7l5.9-.8L12 3.5z"/></svg>Ulasan</a>

<div class="hdrop-sep"></div>
<div class="hdrop-label">Sistem & Pengaturan</div>
<a href="/admin/voucher" class="{{ $seg2=='voucher'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a2.5 2.5 0 0 0 0 5v2a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-2a2.5 2.5 0 0 0 0-5V8z"/><path d="M13.5 7v10" stroke-dasharray="2.5 2"/></svg>Voucher</a>
<a href="/admin/notifikasi" class="{{ $seg2=='notifikasi'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8 8 0 0 1-8 8H5l-2 2V12a8 8 0 0 1 8-8h2a8 8 0 0 1 8 8z"/><circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="13" cy="12" r="1" fill="currentColor"/><circle cx="17" cy="12" r="1" fill="currentColor"/></svg>Notifikasi</a>
<a href="/admin/integrasi" class="{{ $seg2=='integrasi'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v4"/><path d="M15 3h4a2 2 0 0 1 2 2v4"/><path d="M9 21H5a2 2 0 0 1-2-2v-4"/><path d="M15 21h4a2 2 0 0 0 2-2v-4"/><circle cx="12" cy="12" r="3"/></svg>Integrasi API</a>
<a href="/admin/faq" class="{{ $seg2=='faq'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.3a2.6 2.6 0 0 1 5 .9c0 1.7-2.4 2-2.4 3.6"/><circle cx="12" cy="17.2" r=".5" fill="currentColor"/></svg>FAQ</a>
<a href="/admin/pengaturan" class="{{ $seg2=='pengaturan'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h9"/><circle cx="16.5" cy="7" r="2"/><path d="M20 17H9"/><circle cx="6" cy="17" r="2"/></svg>Setting</a>
<a href="/admin/aktivitas" class="{{ $seg2=='aktivitas'?'active':'' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l2.5 2.5"/><circle cx="12" cy="12" r="9"/></svg>Log Aktivitas</a>
</div>
</details>
<form method="POST" action="/logout" style="margin:0">@csrf<button class="abtn" title="Keluar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4H6a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h8"/><path d="M10 12h11"/><path d="M18 8.5 21.5 12 18 15.5"/></svg></button></form>
</div>
</header>
@if(session('ok'))<div class="toast">✓ {{ session('ok') }}</div>@endif
@if($errors->any())<div class="toast" style="background:#fef2f2;border-color:#fecaca;color:#991b1b">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>@endif
<main style="padding:12px">@yield('content')</main>
<nav class="bottom-nav" aria-label="Navigasi bawah admin">
@php $seg=request()->segment(2)??'dash'; @endphp
<a href="/admin" class="bnav {{ $seg=='dash'||$seg==''?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 10.5 12 3.5l8.5 7"/><path d="M5.5 9v11h4.5v-6h4v6h4.5V9"/></svg></span>Home</a>
<a href="/admin/verifikasi" class="bnav {{ $seg=='verifikasi'?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 2.8v5.4c0 4.6-3 7.6-7 9.3-4-1.7-7-4.7-7-9.3V5.8L12 3z"/><path d="M9.3 11.8l2 2 3.6-4"/></svg></span>Verif</a>
<a href="/admin/order" class="bnav {{ $seg=='order'?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12V21l-3-2-3 2-3-2-3 2v-17.5z"/><path d="M9 8.5h6M9 12h6"/></svg></span>Order</a>
<a href="/admin/produk" class="bnav {{ $seg=='produk'?'active':'' }}"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg></span>Stok</a>
<a href="/akun" class="bnav"><span class="pic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.8 4.2-5.5 7.5-5.5s6.1 1.7 7.5 5.5"/></svg></span>Akun</a>
</nav>
</div>
<script>setTimeout(()=>{document.querySelectorAll('.toast').forEach(t=>t.style.display='none')},4500);document.addEventListener('click',e=>{document.querySelectorAll('.hmenu[open]').forEach(d=>{if(!d.contains(e.target))d.removeAttribute('open')})});</script>
</body>
</html>
