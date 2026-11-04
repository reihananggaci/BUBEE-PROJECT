@extends('layouts.web-admin')
@section('title','Log Aktivitas')
@section('content')
<div class="flex justify-between items-center flex-wrap gap-2">
<div>
    <h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
        <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </span>
        <span>Log Aktivitas</span>
    </h1>
    <div class="text-xs text-slate-500 mt-0.5">Siapa-apa-kapan • klik baris untuk buka objek terkait</div>
</div>
<div class="flex gap-2">
<a href="/admin/aktivitas/export?q={{ $q }}&kategori={{ $kategori }}&level={{ $level }}" class="bg-emerald-50 text-emerald-700 font-bold py-2 px-4 rounded-2xl text-xs hover:bg-emerald-100 transition inline-flex items-center gap-1.5" style="text-decoration:none">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
    <span>Export CSV</span>
</a>
<form method="POST" action="/admin/aktivitas/prune" onsubmit="return confirm('Hapus semua log >90 hari?')">@csrf<button class="bg-red-50 text-red-600 font-bold py-2 px-4 rounded-2xl text-xs hover:bg-red-100 transition">Bersihkan lama</button></form>
</div>
</div>
<div class="grid gap-2 mt-3 lg:grid-cols-3 text-center">
<div class="bg-white rounded-3xl shadow-card p-4 border border-slate-100"><div class="font-extrabold text-2xl {{ $ringkas['gagal24']>0?'text-red-500':'' }}">{{ $ringkas['gagal24'] }}</div><div class="text-[11px] text-slate-500">GAGAL LOGIN 24 JAM</div></div>
<div class="bg-white rounded-3xl shadow-card p-4 border border-slate-100"><div class="font-extrabold text-2xl {{ $ringkas['bahaya7']>0?'text-red-500':'' }}">{{ $ringkas['bahaya7'] }}</div><div class="text-[11px] text-slate-500">LEVEL BAHAYA 7 HARI</div></div>
<div class="bg-white rounded-3xl shadow-card p-4 border border-slate-100"><div class="font-extrabold text-2xl">{{ $ringkas['total'] }}</div><div class="text-[11px] text-slate-500">TOTAL LOG</div></div>
</div>
<form method="GET" action="/admin/aktivitas" class="flex gap-2 my-3 flex-wrap items-center">
<div class="relative flex-1" style="min-width:200px">
    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </span>
    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari aksi/subjek/keterangan…" class="w-full border rounded-2xl pl-10 pr-3 py-2 text-sm">
</div>
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
<a href="/admin/aktivitas?preset=hari_ini" class="text-xs font-bold px-3 py-2 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">Hari ini</a>
<a href="/admin/aktivitas?preset=tujuh_hari" class="text-xs font-bold px-3 py-2 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">7 hari</a>
<a href="/admin/aktivitas?preset=tiga_puluh_hari" class="text-xs font-bold px-3 py-2 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">30 hari</a>
</form>
<details class="bg-white rounded-3xl shadow-card p-4 mb-3 border border-slate-100">
<summary class="text-sm font-bold text-purple-700 cursor-pointer flex items-center gap-1.5"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg> Filter lanjutan (kategori, level, tanggal, export, bersih)</summary>
<form method="GET" action="/admin/aktivitas" class="flex gap-2 mt-2 flex-wrap">
<select name="kategori" class="border rounded-2xl px-3 py-2 text-sm font-bold">
@foreach(['semua'=>'Semua kategori','auth'=>'Auth','order'=>'Order','stok'=>'Stok','produk'=>'Produk','user'=>'User','sistem'=>'Sistem'] as $k=>$l)<option value="{{ $k }}" {{ ($kategori??'semua')===$k?'selected':'' }}>{{ $l }}</option>@endforeach
</select>
<select name="level" class="border rounded-2xl px-3 py-2 text-sm font-bold">
@foreach(['semua'=>'Semua level','info'=>'Info','waspada'=>'Waspada','bahaya'=>'Bahaya'] as $k=>$l)<option value="{{ $k }}" {{ ($level??'semua')===$k?'selected':'' }}>{{ $l }}</option>@endforeach
</select>
<input type="date" name="dari" value="{{ $dari }}" max="{{ now()->toDateString() }}" class="border rounded-2xl px-3 py-2 text-sm">
<input type="date" name="sampai" value="{{ $sampai }}" max="{{ now()->toDateString() }}" class="border rounded-2xl px-3 py-2 text-sm">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Filter</button>
</form>
</details>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Waktu</th><th class="px-4 py-2">Level</th><th class="px-4 py-2">Kategori</th><th class="px-4 py-2">Aksi</th><th class="px-4 py-2">Oleh</th><th class="px-4 py-2">Subjek</th><th class="px-4 py-2">Detail</th></tr></thead>
<tbody>
@foreach($logs as $l)<tr class="border-t border-slate-100">
<td class="px-4 py-2 text-xs whitespace-nowrap">{{ $l->created_at->format('d M H:i') }}@if($l->ip)<br><span class="text-slate-400">{{ $l->ip }}</span>@endif</td>
<td class="px-4 py-2"><span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $l->level==='bahaya'?'bg-red-500 text-white':($l->level==='waspada'?'bg-amber-300':'bg-slate-100 text-slate-500') }}">{{ strtoupper($l->level) }}</span></td>
<td class="px-4 py-2 text-xs">{{ $l->kategori }}</td>
<td class="px-4 py-2 font-bold">@if($l->tautan)<a href="{{ $l->tautan }}" class="text-purple-700" style="text-decoration:none">{{ $l->aksi }} →</a>@else{{ $l->aksi }}@endif</td>
<td class="px-4 py-2">{{ $l->actor->name ?? 'sistem' }}</td>
<td class="px-4 py-2 text-xs">{{ $l->subjek }}</td>
<td class="px-4 py-2 text-xs text-slate-500">{{ $l->keterangan }}</td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">
@foreach($logs as $l)
<div class="bg-white rounded-3xl shadow-card p-3 mb-1.5 text-xs" @if($l->level==='bahaya')style="border-color:#fecaca"@elseif($l->level==='waspada')style="border-color:#fde68a"@endif>
<div class="flex justify-between items-center"><b>@if($l->tautan)<a href="{{ $l->tautan }}" class="text-purple-700" style="text-decoration:none">{{ $l->aksi }} →</a>@else{{ $l->aksi }}@endif</b><span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $l->level==='bahaya'?'bg-red-500 text-white':($l->level==='waspada'?'bg-amber-300':'bg-slate-100 text-slate-500') }}">{{ strtoupper($l->level) }}</span></div>
<div class="mt-0.5">Oleh: <b>{{ $l->actor->name ?? 'sistem' }}</b> • {{ $l->kategori }} • {{ $l->created_at->format('d M H:i') }}</div>
@if($l->subjek)<div>{{ $l->subjek }}</div>@endif
@if($l->keterangan)<div class="text-slate-500">{{ $l->keterangan }}</div>@endif
</div>
@endforeach
</div>
@if($logs->isEmpty())<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3">Tidak ada log cocok filter.</div>@endif
<div class="mt-2">{{ $logs->links() }}</div>
@endsection
