@extends('layouts.admin')
@section('title','Log Aktivitas')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">📜 Log Aktivitas</h1>
<div class="text-xs text-slate-500 mb-2">Siapa-apa-kapan: login, order, stok, user. Merah = bahaya.</div>
<div class="grid grid-cols-3 gap-1.5 text-center mb-2">
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold {{ $ringkas['gagal24']>0?'text-red-500':'' }}">{{ $ringkas['gagal24'] }}</div><div class="text-[9px] text-slate-500">GAGAL LOGIN 24J</div></div>
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold {{ $ringkas['bahaya7']>0?'text-red-500':'' }}">{{ $ringkas['bahaya7'] }}</div><div class="text-[9px] text-slate-500">BAHAYA 7 HARI</div></div>
<div class="glass rounded-2xl shadow-card p-2"><div class="font-extrabold">{{ $ringkas['total'] }}</div><div class="text-[9px] text-slate-500">TOTAL LOG</div></div>
</div>
<form method="GET" action="/admin/aktivitas" class="space-y-1.5 mb-2">
<div class="flex gap-1.5">
<input name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari…" class="flex-1 border rounded-2xl px-3 py-2 text-xs">
<button class="bg-purple-900 text-white font-bold px-3 rounded-2xl text-xs">Cari</button>
</div>
<div class="flex gap-1.5 text-[11px] font-bold">
<a href="/admin/aktivitas?preset=hari_ini" class="flex-1 text-center px-2 py-1.5 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">Hari ini</a>
<a href="/admin/aktivitas?preset=tujuh_hari" class="flex-1 text-center px-2 py-1.5 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">7 hari</a>
<a href="/admin/aktivitas?preset=tiga_puluh_hari" class="flex-1 text-center px-2 py-1.5 rounded-full bg-slate-100 text-slate-600" style="text-decoration:none">30 hari</a>
</div>
<details class="glass rounded-2xl shadow-card p-2.5">
<summary class="text-xs font-bold text-purple-700 cursor-pointer">🎚️ Filter lanjutan</summary>
<div class="flex gap-1.5 mt-1.5">
<select name="kategori" class="flex-1 border rounded-2xl px-2 py-2 text-xs font-bold">
@foreach(['semua'=>'Semua kategori','auth'=>'🔑 Auth','order'=>'🧾 Order','stok'=>'📦 Stok','produk'=>'📦 Produk','user'=>'👥 User','sistem'=>'⚙️ Sistem'] as $k=>$l)<option value="{{ $k }}" {{ ($kategori??'semua')===$k?'selected':'' }}>{{ $l }}</option>@endforeach
</select>
<select name="level" class="flex-1 border rounded-2xl px-2 py-2 text-xs font-bold">
@foreach(['semua'=>'Semua level','info'=>'🟢 Info','waspada'=>'🟡 Waspada','bahaya'=>'🔴 Bahaya'] as $k=>$l)<option value="{{ $k }}" {{ ($level??'semua')===$k?'selected':'' }}>{{ $l }}</option>@endforeach
</select>
</div>
<div class="flex gap-1.5 mt-1.5">
<input type="date" name="dari" value="{{ $dari }}" max="{{ now()->toDateString() }}" class="flex-1 border rounded-2xl px-2 py-2 text-xs">
<input type="date" name="sampai" value="{{ $sampai }}" max="{{ now()->toDateString() }}" class="flex-1 border rounded-2xl px-2 py-2 text-xs">
</div>
</details>
</form>
<div class="flex gap-1.5 mb-2">
<a href="/admin/aktivitas/export?q={{ $q }}&kategori={{ $kategori }}&level={{ $level }}" class="flex-1 text-center bg-emerald-50 text-emerald-700 font-bold py-2 rounded-2xl text-xs">📊 Export CSV</a>
<form method="POST" action="/admin/aktivitas/prune" class="flex-1" onsubmit="return confirm('Hapus semua log >90 hari?')">@csrf<button class="w-full bg-red-50 text-red-600 font-bold py-2 rounded-2xl text-xs">🧹 Bersihkan lama</button></form>
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
