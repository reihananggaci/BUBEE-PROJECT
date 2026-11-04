<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Bubee Web • @yield('title','Dashboard')</title>
<link rel="manifest" href="/manifest.webmanifest">
<meta name="theme-color" content="#4c1d95">
<link rel="icon" href="/images/logo-bubee.png">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{boxShadow:{card:'0 8px 24px rgba(15,23,42,.08)'}}}}</script>
<style>
:root{--line:#e8edf5}
*{-webkit-tap-highlight-color:transparent}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;margin:0;min-height:100vh;background:radial-gradient(1000px 380px at 20% -120px,#e4dcff 0%,#f1eefb 55%,#f1eefb 100%);color:#0f172a}
::selection{background:#7c3aed;color:#fff}
a,button{transition:filter .15s ease,transform .15s ease,background .15s ease}
a:active,button:active{filter:brightness(.96)}
:focus-visible{outline:2px solid #7c3aed;outline-offset:2px;border-radius:8px}
/* Sidebar */
.webside{scrollbar-width:thin;scrollbar-color:#4c3a8c transparent}
.webside::-webkit-scrollbar{width:8px}
.webside::-webkit-scrollbar-thumb{background:#4c3a8c;border-radius:9999px}
.webside a{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:12px;font-size:13px;font-weight:700;color:#c4b5fd;text-decoration:none;margin-bottom:2px}
.webside a svg{width:18px;height:18px;flex-shrink:0;transition:transform .15s ease}
.webside a:hover{background:rgba(255,255,255,.08);color:#fff;transform:translateX(2px)}
.webside a:hover svg{transform:scale(1.08)}
.webside a.active{background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;box-shadow:0 4px 14px rgba(124,58,237,.45)}
.wtopbar{box-shadow:0 4px 20px rgba(46,16,101,.35)}
/* Kartu & tabel */
main .shadow-card,main .shadow{box-shadow:0 8px 24px rgba(15,23,42,.08)}
main table{border-collapse:separate;border-spacing:0;width:100%}
main table thead th{position:sticky;top:0;background:#f5f1ff;z-index:1}
main table tbody tr{transition:background .12s ease}
main table tbody tr:hover{background:#faf7ff}
main table tbody tr:last-child td:first-child{border-bottom-left-radius:1.25rem}
main table tbody tr:last-child td:last-child{border-bottom-right-radius:1.25rem}
/* Form */
main input:not([type=checkbox]):not([type=radio]):not([type=file]),main select,main textarea{transition:border-color .15s ease,box-shadow .15s ease;background:#fff}
main input:not([type=checkbox]):not([type=radio]):not([type=file]):focus,main select:focus,main textarea:focus{border-color:#7c3aed !important;box-shadow:0 0 0 3px rgba(124,58,237,.15);outline:none}
main input[type=file]{cursor:pointer}
main details>summary{transition:color .15s ease}
main details>summary:hover{color:#7c3aed}
</style>
</head>
<body>
<div class="flex" style="min-height:100vh">
<aside class="webside hidden lg:block w-60 flex-none sticky top-0 h-screen overflow-y-auto p-3" style="background:#0f0d2e">
<div class="flex items-center gap-2 px-2 py-3">
<img src="/images/logo-bubee.png" alt="Bubee" style="width:36px;height:36px;border-radius:10px;background:#fff;padding:2px">
<span style="font-weight:800;letter-spacing:.14em;color:#fff;font-size:14px">BUBEE ADMIN</span>
</div>
@php $seg2=request()->segment(2)??''; @endphp
<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:10px 12px 4px">RINGKASAN</div>
<a href="/admin" class="{{ $seg2==''?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1.5"/><rect width="7" height="5" x="14" y="3" rx="1.5"/><rect width="7" height="9" x="14" y="12" rx="1.5"/><rect width="7" height="5" x="3" y="16" rx="1.5"/></svg>
    <span>Dashboard</span>
</a>
<a href="/admin/laporan" class="{{ $seg2=='laporan'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 20v-7"/><path d="M11 20V5"/><path d="M17 20v-10"/><path d="M3 20h18"/></svg>
    <span>Laporan</span>
</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:12px 12px 4px">PENJUALAN</div>
<a href="/admin/verifikasi" class="{{ $seg2=='verifikasi'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 2.8v5.4c0 4.6-3 7.6-7 9.3-4-1.7-7-4.7-7-9.3V5.8L12 3z"/><path d="M9.3 11.8l2 2 3.6-4"/></svg>
    <span>Verifikasi</span>
</a>
<a href="/admin/order" class="{{ $seg2=='order'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12V21l-3-2-3 2-3-2-3 2v-17.5z"/><path d="M9 8.5h6M9 12h6"/></svg>
    <span>Order</span>
</a>
<a href="/admin/retur" class="{{ $seg2=='retur'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V5.5A1.5 1.5 0 0 1 5.5 4H9"/><path d="M4 5.5A9 9 0 1 1 3 13"/><path d="M3 13h5"/></svg>
    <span>Retur</span>
</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:12px 12px 4px">GUDANG & STOK</div>
<a href="/admin/produk" class="{{ $seg2=='produk'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 3.5 7.5v9L12 21l8.5-4.5v-9L12 3z"/><path d="M3.5 7.5 12 12l8.5-4.5"/><path d="M12 12v9"/></svg>
    <span>Stok & Barang</span>
</a>
<a href="/admin/penerimaan" class="{{ $seg2=='penerimaan'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    <span>Barang Masuk</span>
</a>
<a href="/admin/opname" class="{{ $seg2=='opname'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect width="8" height="4" x="8" y="3" rx="1"/><path d="m9 14 2 2 4-4"/></svg>
    <span>Stock Opname</span>
</a>
<a href="/admin/mutasi" class="{{ $seg2=='mutasi'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h13"/><path d="M14 3.5 17.5 7 14 10.5"/><path d="M20 17H7"/><path d="M10 13.5 6.5 17 10 20.5"/></svg>
    <span>Mutasi</span>
</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:12px 12px 4px">PENGGUNA</div>
<a href="/admin/reseller" class="{{ $seg2=='reseller'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
    <span>Reseller</span>
</a>
<a href="/admin/users" class="{{ $seg2=='users'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8.5" r="3"/><path d="M2.8 19.5c1.2-3 3.4-4.5 6.2-4.5s5 1.5 6.2 4.5"/><circle cx="16.8" cy="9.5" r="2.4"/><path d="M16 15c2.3.2 4 1.5 5 4"/></svg>
    <span>Users</span>
</a>
<a href="/admin/ulasan" class="{{ $seg2=='ulasan'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    <span>Ulasan</span>
</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:12px 12px 4px">SISTEM & PENGATURAN</div>
<a href="/admin/voucher" class="{{ $seg2=='voucher'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a2.5 2.5 0 0 0 0 5v2a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-2a2.5 2.5 0 0 0 0-5V8z"/><path d="M13.5 7v10" stroke-dasharray="2.5 2"/></svg>
    <span>Voucher</span>
</a>
<a href="/admin/notifikasi" class="{{ $seg2=='notifikasi'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
    <span>Notifikasi</span>
</a>
<a href="/admin/integrasi" class="{{ $seg2=='integrasi'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v4"/><path d="M15 3h4a2 2 0 0 1 2 2v4"/><path d="M9 21H5a2 2 0 0 1-2-2v-4"/><path d="M15 21h4a2 2 0 0 0 2-2v-4"/><circle cx="12" cy="12" r="3"/></svg>
    <span>Integrasi</span>
</a>
<a href="/admin/faq" class="{{ $seg2=='faq'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.3a2.6 2.6 0 0 1 5 .9c0 1.7-2.4 2-2.4 3.6"/><circle cx="12" cy="17.2" r=".5" fill="currentColor"/></svg>
    <span>FAQ</span>
</a>
<a href="/admin/pengaturan" class="{{ $seg2=='pengaturan'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h9"/><circle cx="16.5" cy="7" r="2"/><path d="M20 17H9"/><circle cx="6" cy="17" r="2"/></svg>
    <span>Setting</span>
</a>
<a href="/admin/aktivitas" class="{{ $seg2=='aktivitas'?'active':'' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l2.5 2.5"/><circle cx="12" cy="12" r="9"/></svg>
    <span>Aktivitas</span>
</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:10px 12px 4px">LAINNYA</div>
<a href="/">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
    <span>Lihat Toko</span>
</a>
<a href="/akun">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.8 4.2-5.5 7.5-5.5s6.1 1.7 7.5 5.5"/></svg>
    <span>Akun Saya</span>
</a>
<a href="/tampilan/hp">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
    <span>Versi HP</span>
</a>
</aside>
<div class="flex-1 min-w-0">
<header class="wtopbar sticky top-0 z-40 px-5 py-3 flex items-center gap-3" style="background:linear-gradient(120deg,#2e1065,#7c3aed 60%,#c026d3);border-bottom:1px solid rgba(255,255,255,.2)">
<span class="lg:hidden" style="font-weight:800;color:#fff">☰</span>
<span class="inline-flex items-center gap-1.5 font-extrabold text-white text-xs tracking-wider uppercase">
    <svg class="w-4 h-4 text-amber-300" viewBox="0 0 24 24" fill="currentColor"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14v2H5v-2z"/></svg>
    <span>MODE ADMIN</span>
</span>
<span style="flex:1"></span>
@php $notifGagal = \App\Models\NotificationLog::where('status','!=','terkirim')->where('created_at','>=',now()->subDays(7))->count(); @endphp
<a href="/admin/notifikasi" title="Notifikasi" class="inline-flex items-center justify-center relative hover:bg-white/20 transition active:scale-95" style="background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);color:#fff;border-radius:10px;padding:7px 11px;text-decoration:none">
    <svg class="w-4.5 h-4.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
    @if($notifGagal>0)<span style="position:absolute;top:-7px;right:-7px;background:#ef4444;color:#fff;font-size:10px;font-weight:800;min-width:18px;height:18px;line-height:18px;text-align:center;border-radius:9999px;padding:0 4px">{{ $notifGagal>99?'99+':$notifGagal }}</span>@endif
</a>
@if(auth()->check())<span style="color:#fff;font-size:12px;font-weight:700">{{ auth()->user()->name }}</span>@endif
<form method="POST" action="/logout" style="margin:0">@csrf<button style="background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);color:#fff;border-radius:10px;padding:7px 12px;font-size:11px;font-weight:800;cursor:pointer">Keluar</button></form>
</header>
<main class="max-w-6xl mx-auto px-5 py-6">
@if(session('ok'))<div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;font-size:13px;padding:10px 12px;border-radius:12px;margin-bottom:12px">✓ {{ session('ok') }}</div>@endif
@if($errors->any())<div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:13px;padding:10px 12px;border-radius:12px;margin-bottom:12px">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>@endif
@yield('content')
</main>
</div>
</div>
</body>
</html>
