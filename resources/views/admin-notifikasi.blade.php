@extends('layouts.admin')
@section('title','Notifikasi WA')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">💬 Log Notifikasi WA</h1>
@if($live)
<div class="rounded-2xl p-3 mt-2 text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-700">🟢 Mode LIVE (Fonnte tersambung) — pesan benar-benar terkirim.</div>
@else
<div class="rounded-2xl p-3 mt-2 text-xs bg-amber-50 border border-amber-200 text-amber-800">🟡 Mode DUMMY — pesan hanya dicatat & pratinjau di sini, tidak ada pulsa/API keluar. Isi token Fonnte di <a href="/admin/pengaturan" class="font-bold underline">Pengaturan</a> untuk live.</div>
@endif
<form method="GET" action="/admin/notifikasi" class="flex gap-2 my-2">
<input name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari pesan/nomor…" class="flex-1 border rounded-2xl px-4 py-2 text-sm">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
</form>
@foreach($logs as $l)
<div class="glass rounded-3xl shadow-card p-3 mb-1.5 text-xs">
<div class="flex justify-between items-center">
<b>{{ $l->tipe }} @if($l->order)→ {{ $l->order->kode }}@endif</b>
<span class="font-extrabold px-2 py-0.5 rounded-full {{ $l->status==='terkirim'?'bg-emerald-200':($l->status==='gagal'?'bg-red-200':'bg-amber-200') }}">{{ strtoupper($l->status) }}</span>
</div>
<div class="text-slate-500">→ {{ $l->tujuan }} • {{ $l->created_at->format('d M H:i') }}</div>
<div class="bg-slate-50 rounded-xl p-2 mt-1 whitespace-pre-wrap">{{ $l->pesan }}</div>
@if($l->response)<div class="text-[10px] text-slate-400 mt-1">Respon: {{ \Str::limit($l->response,150) }}</div>@endif
<form method="POST" action="/admin/notifikasi/{{ $l->id }}/resend" class="mt-1">@csrf<button class="text-[11px] font-bold text-purple-700">↻ Kirim ulang</button></form>
</div>
@endforeach
@if($logs->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Belum ada notifikasi. Buat order untuk memicu. 🐝</div>@endif
<div class="mt-2">{{ $logs->links() }}</div>
@endsection
