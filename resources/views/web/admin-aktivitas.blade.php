@extends('layouts.web-admin')
@section('title','Log Aktivitas')
@section('content')
<div class="flex justify-between items-center flex-wrap gap-2">
<div><h1 class="font-extrabold text-2xl">📜 Log Aktivitas</h1><div class="text-xs text-slate-500">Siapa-apa-kapan • klik baris untuk buka objek terkait</div></div>
<div class="flex gap-2">
<a href="/admin/aktivitas/export?q={{ $q }}&kategori={{ $kategori }}&level={{ $level }}" class="bg-emerald-50 text-emerald-700 font-bold py-2 px-4 rounded-2xl text-xs" style="text-decoration:none">📊 Export CSV</a>
<form method="POST" action="/admin/aktivitas/prune" onsubmit="return confirm('Hapus semua log >90 hari?')">@csrf<button class="bg-red-50 text-red-600 font-bold py-2 px-4 rounded-2xl text-xs">🧹 Bersihkan lama</button></form>
</div>
</div>
<div class="grid gap-2 mt-3 lg:grid-cols-3 text-center">
<div class="bg-white rounded-3xl shadow-card p-4"><div class="font-extrabold text-2xl {{ $ringkas['gagal24']>0?'text-red-500':'' }}">{{ $ringkas['gagal24'] }}</div><div class="text-[11px] text-slate-500">GAGAL LOGIN 24 JAM</div></div>
<div class="bg-white rounded-3xl shadow-card p-4"><div class="font-extrabold text-2xl {{ $ringkas['bahaya7']>0?'text-red-500':'' }}">{{ $ringkas['bahaya7'] }}</div><div class="text-[11px] text-slate-500">LEVEL BAHAYA 7 HARI</div></div>
<div class="bg-white rounded-3xl shadow-card p-4"><div class="font-extrabold text-2xl">{{ $ringkas['total'] }}</div><div class="text-[11px] text-slate-500">TOTAL LOG</div></div>
</div>
<form method="GET" action="/admin/aktivitas" class="flex gap-2 my-3 flex-wrap items-center">
<input name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari aksi/subjek/keterangan…" class="border rounded-2xl px-4 py-2 text-sm" style="min-width:200px;flex:2">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
<a href="/admin/aktivitas?preset=hari_ini" class="text-xs font-bold px-3 py-2 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">Hari ini</a>
<a href="/admin/aktivitas?preset=tujuh_hari" class="text-xs font-bold px-3 py-2 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">7 hari</a>
<a href="/admin/aktivitas?preset=tiga_puluh_hari" class="text-xs font-bold px-3 py-2 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">30 hari</a>
</form>
<details class="bg-white rounded-3xl shadow-card p-4 mb-3">
<summary class="text-sm font-bold text-purple-700 cursor-pointer">🎚️ Filter lanjutan (kategori, level, tanggal, export, bersih)</summary>
<form method="GET" action="/admin/aktivitas" class="flex gap-2 mt-2 flex-wrap">
<select name="kategori" class="border rounded-2xl px-3 py-2 text-sm font-bold">
@foreach(['semua'=>'Semua kategori','auth'=>'🔑 Auth','order'=>'🧾 Order','stok'=>'📦 Stok','produk'=>'📦 Produk','user'=>'👥 User','sistem'=>'⚙️ Sistem'] as $k=>$l)<option value="{{ $k }}" {{ ($kategori??'semua')===$k?'selected':'' }}>{{ $l }}</option>@endforeach
</select>
<select name="level" class="border rounded-2xl px-3 py-2 text-sm font-bold">
@foreach(['semua'=>'Semua level','info'=>'🟢 Info','waspada'=>'🟡 Waspada','bahaya'=>'🔴 Bahaya'] as $k=>$l)<option value="{{ $k }}" {{ ($level??'semua')===$k?'selected':'' }}>{{ $l }}</option>@endforeach
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
