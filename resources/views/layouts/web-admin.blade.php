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
.webside a:hover{background:rgba(255,255,255,.08);color:#fff;transform:translateX(2px)}
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
<a href="/admin" class="{{ $seg2==''?'active':'' }}">🏠 Dashboard</a>
<a href="/admin/laporan" class="{{ $seg2=='laporan'?'active':'' }}">📊 Laporan</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:12px 12px 4px">PENJUALAN</div>
<a href="/admin/verifikasi" class="{{ $seg2=='verifikasi'?'active':'' }}">✅ Verifikasi</a>
<a href="/admin/order" class="{{ $seg2=='order'?'active':'' }}">🧾 Order</a>
<a href="/admin/retur" class="{{ $seg2=='retur'?'active':'' }}">↩️ Retur</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:12px 12px 4px">GUDANG & STOK</div>
<a href="/admin/produk" class="{{ $seg2=='produk'?'active':'' }}">📦 Stok & Barang</a>
<a href="/admin/penerimaan" class="{{ $seg2=='penerimaan'?'active':'' }}">🚚 Barang Masuk</a>
<a href="/admin/opname" class="{{ $seg2=='opname'?'active':'' }}">📋 Stock Opname</a>
<a href="/admin/mutasi" class="{{ $seg2=='mutasi'?'active':'' }}">📜 Mutasi</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:12px 12px 4px">PENGGUNA</div>
<a href="/admin/reseller" class="{{ $seg2=='reseller'?'active':'' }}">🏪 Reseller</a>
<a href="/admin/users" class="{{ $seg2=='users'?'active':'' }}">👥 Users</a>
<a href="/admin/ulasan" class="{{ $seg2=='ulasan'?'active':'' }}">⭐ Ulasan</a>

<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:12px 12px 4px">SISTEM & PENGATURAN</div>
<a href="/admin/voucher" class="{{ $seg2=='voucher'?'active':'' }}">🎟️ Voucher</a>
<a href="/admin/notifikasi" class="{{ $seg2=='notifikasi'?'active':'' }}">💬 Notifikasi</a>
<a href="/admin/integrasi" class="{{ $seg2=='integrasi'?'active':'' }}">🔌 Integrasi</a>
<a href="/admin/faq" class="{{ $seg2=='faq'?'active':'' }}">❓ FAQ</a>
<a href="/admin/pengaturan" class="{{ $seg2=='pengaturan'?'active':'' }}">⚙️ Setting</a>
<a href="/admin/aktivitas" class="{{ $seg2=='aktivitas'?'active':'' }}">🕘 Aktivitas</a>
<div style="font-size:10px;font-weight:800;letter-spacing:.12em;color:#8b7cc7;padding:10px 12px 4px">LAINNYA</div>
<a href="/">🏪 Lihat Toko</a>
<a href="/akun">👤 Akun Saya</a>
<a href="/tampilan/hp">📱 Versi HP</a>
</aside>
<div class="flex-1 min-w-0">
<header class="wtopbar sticky top-0 z-40 px-5 py-3 flex items-center gap-3" style="background:linear-gradient(120deg,#2e1065,#7c3aed 60%,#c026d3);border-bottom:1px solid rgba(255,255,255,.2)">
<span class="lg:hidden" style="font-weight:800;color:#fff">☰</span>
<span style="font-weight:800;color:#fff">👑 MODE ADMIN</span>
<span style="flex:1"></span>
@php $notifGagal = \App\Models\NotificationLog::where('status','!=','terkirim')->where('created_at','>=',now()->subDays(7))->count(); @endphp
<a href="/admin/notifikasi" title="Notifikasi" style="position:relative;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.25);color:#fff;border-radius:10px;padding:7px 11px;font-size:14px;font-weight:800;text-decoration:none">🔔@if($notifGagal>0)<span style="position:absolute;top:-7px;right:-7px;background:#ef4444;color:#fff;font-size:10px;font-weight:800;min-width:18px;height:18px;line-height:18px;text-align:center;border-radius:9999px;padding:0 4px">{{ $notifGagal>99?'99+':$notifGagal }}</span>@endif</a>
@if(auth()->check())<span style="color:#fff;font-size:12px">{{ auth()->user()->name }}</span>@endif
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
