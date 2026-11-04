@extends('layouts.admin')
@section('title','Notifikasi WA')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    </span>
    <span>Log Notifikasi WhatsApp</span>
</h1>
@if($live)
<div class="rounded-2xl p-3 mt-2 text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span><span>Mode LIVE (Fonnte tersambung) — pesan benar-benar terkirim.</span></div>
@else
<div class="rounded-2xl p-3 mt-2 text-xs bg-amber-50 border border-amber-200 text-amber-800 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span><span>Mode DUMMY — pesan hanya dicatat & pratinjau di sini, tidak ada pulsa/API keluar. Isi token Fonnte di <a href="/admin/pengaturan" class="font-bold underline">Pengaturan</a> untuk live.</span></div>
@endif
<form method="GET" action="/admin/notifikasi" class="flex gap-2 my-2">
<input name="q" value="{{ $q ?? '' }}" placeholder="Cari pesan/nomor…" class="flex-1 border rounded-2xl px-4 py-2 text-sm bg-white">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm shadow-sm">Cari</button>
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
@if($logs->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm text-slate-500">Belum ada notifikasi. Buat order untuk memicu.</div>@endif
<div class="mt-2">{{ $logs->links() }}</div>
@endsection
