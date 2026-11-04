@extends('layouts.admin')
@section('title','Log Aktivitas')
@section('back', '/admin')
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l2.5 2.5"/><circle cx="12" cy="12" r="9"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Log Aktivitas</h1>
</div>
<div class="text-xs text-slate-500 mb-2">Siapa-apa-kapan: login, order, stok, user. Merah = bahaya.</div>
<div class="grid grid-cols-3 gap-1.5 text-center mb-2">
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold {{ $ringkas['gagal24']>0?'text-red-500':'' }}">{{ $ringkas['gagal24'] }}</div><div class="text-[9px] text-slate-500">GAGAL LOGIN 24J</div></div>
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold {{ $ringkas['bahaya7']>0?'text-red-500':'' }}">{{ $ringkas['bahaya7'] }}</div><div class="text-[9px] text-slate-500">BAHAYA 7 HARI</div></div>
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold">{{ $ringkas['total'] }}</div><div class="text-[9px] text-slate-500">TOTAL LOG</div></div>
</div>
<form method="GET" action="/admin/aktivitas" class="space-y-1.5 mb-2">
<div class="flex gap-1.5">
<input name="q" value="{{ $q ?? '' }}" placeholder="Cari log…" class="flex-1 border rounded-2xl px-3 py-2 text-xs">
<button class="bg-purple-900 text-white font-bold px-3 rounded-2xl text-xs">Cari</button>
</div>
<div class="flex gap-1.5 text-[11px] font-bold">
<a href="/admin/aktivitas?preset=hari_ini" class="flex-1 text-center px-2 py-1.5 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">Hari ini</a>
<a href="/admin/aktivitas?preset=tujuh_hari" class="flex-1 text-center px-2 py-1.5 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">7 hari</a>
<a href="/admin/aktivitas?preset=tiga_puluh_hari" class="flex-1 text-center px-2 py-1.5 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">30 hari</a>
</div>
<details class="glass rounded-2xl shadow-card p-2.5">
<summary class="text-xs font-bold text-purple-700 cursor-pointer inline-flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>Filter lanjutan</summary>
<div class="flex gap-1.5 mt-1.5">
<select name="kategori" class="flex-1 border rounded-2xl px-2 py-2 text-xs font-bold">
@foreach(['semua'=>'Semua kategori','auth'=>'Auth & Login','order'=>'Order & Transaksi','stok'=>'Stok Masuk/Keluar','produk'=>'Katalog Produk','user'=>'Akun Pengguna','sistem'=>'Sistem Internal'] as $k=>$l)<option value="{{ $k }}" {{ ($kategori??'semua')===$k?'selected':'' }}>{{ $l }}</option>@endforeach
</select>
<select name="level" class="flex-1 border rounded-2xl px-2 py-2 text-xs font-bold">
@foreach(['semua'=>'Semua level','info'=>'Info (Normal)','waspada'=>'Waspada (Peringatan)','bahaya'=>'Bahaya (Kritis)'] as $k=>$l)<option value="{{ $k }}" {{ ($level??'semua')===$k?'selected':'' }}>{{ $l }}</option>@endforeach
</select>
</div>
<div class="flex gap-1.5 mt-1.5">
<input type="date" name="dari" value="{{ $dari }}" max="{{ now()->toDateString() }}" class="flex-1 border rounded-2xl px-2 py-2 text-xs">
<input type="date" name="sampai" value="{{ $sampai }}" max="{{ now()->toDateString() }}" class="flex-1 border rounded-2xl px-2 py-2 text-xs">
</div>
</details>
</form>
<div class="flex gap-1.5 mb-2">
<a href="/admin/aktivitas/export?q={{ $q }}&kategori={{ $kategori }}&level={{ $level }}" class="flex-1 text-center bg-emerald-50 text-emerald-700 font-bold py-2 rounded-2xl text-xs flex items-center justify-center gap-1.5">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    <span>Export CSV</span>
</a>
<form method="POST" action="/admin/aktivitas/prune" class="flex-1" onsubmit="return confirm('Hapus semua log >90 hari?')">@csrf<button class="w-full bg-red-50 text-red-600 font-bold py-2 rounded-2xl text-xs flex items-center justify-center gap-1.5">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
    <span>Bersihkan lama</span>
</button></form>
</div>
@foreach($logs as $l)
<div class="glass rounded-3xl shadow-card p-3 mb-1.5 text-xs" @if($l->level==='bahaya')style="border-color:#fecaca"@elseif($l->level==='waspada')style="border-color:#fde68a"@endif>
<div class="flex justify-between items-center"><b>{{ $l->aksi }}</b><span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $l->level==='bahaya'?'bg-red-500 text-white':($l->level==='waspada'?'bg-amber-300':'bg-slate-100 text-slate-500') }}">{{ strtoupper($l->level) }}</span></div>
<div class="mt-0.5">Oleh: <b>{{ $l->actor->name ?? 'sistem' }}</b> • {{ $l->kategori }} • {{ $l->created_at->format('d M H:i') }}@if($l->ip) • {{ $l->ip }}@endif</div>
@if($l->subjek)<div>{{ $l->subjek }}</div>@endif
@if($l->keterangan)<div class="text-slate-500">{{ $l->keterangan }}</div>@endif
@if($l->tautan)<div class="mt-1"><a href="{{ $l->tautan }}" class="font-bold text-purple-700">Buka terkait →</a></div>@endif
</div>
@endforeach
@if($logs->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Tidak ada log cocok filter.</div>@endif
<div class="mt-2">{{ $logs->links() }}</div>
@endsection
