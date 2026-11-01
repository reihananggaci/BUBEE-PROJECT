@extends('layouts.web-admin')
@section('title','Notifikasi WA')
@section('content')
<h1 class="font-extrabold text-2xl">💬 Log Notifikasi WA</h1>
@if($live)
<div class="rounded-2xl p-3 mt-2 text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-700">🟢 Mode LIVE (Fonnte tersambung) — pesan benar-benar terkirim.</div>
@else
<div class="rounded-2xl p-3 mt-2 text-xs bg-amber-50 border border-amber-200 text-amber-800">🟡 Mode DUMMY — pesan hanya dicatat & pratinjau di sini, tidak ada pulsa/API keluar. Isi token Fonnte di <a href="/admin/pengaturan" class="font-bold underline">Pengaturan</a> untuk live.</div>
@endif
<form method="GET" action="/admin/notifikasi" class="flex gap-2 my-3 max-w-md">
<input name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari pesan/nomor…" class="flex-1 border rounded-2xl px-4 py-2 text-sm">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
</form>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Waktu</th><th class="px-4 py-2">Tipe</th><th class="px-4 py-2">Tujuan</th><th class="px-4 py-2">Pesan</th><th class="px-4 py-2">Status</th><th class="px-4 py-2"></th></tr></thead>
<tbody>
@foreach($logs as $l)<tr class="border-t border-slate-100">
<td class="px-4 py-2 text-xs whitespace-nowrap">{{ $l->created_at->format('d M H:i') }}</td>
<td class="px-4 py-2 font-bold whitespace-nowrap">{{ $l->tipe }} @if($l->order)<br><span class="font-normal text-[11px] text-slate-400">{{ $l->order->kode }}</span>@endif</td>
<td class="px-4 py-2 text-xs">{{ $l->tujuan }}</td>
<td class="px-4 py-2 text-xs max-w-md"><div class="whitespace-pre-wrap">{{ \Str::limit($l->pesan,200) }}</div>@if($l->response)<div class="text-[10px] text-slate-400 mt-1">Respon: {{ \Str::limit($l->response,150) }}</div>@endif</td>
<td class="px-4 py-2"><span class="font-extrabold px-2 py-0.5 rounded-full text-[11px] {{ $l->status==='terkirim'?'bg-emerald-200':($l->status==='gagal'?'bg-red-200':'bg-amber-200') }}">{{ strtoupper($l->status) }}</span></td>
<td class="px-4 py-2"><form method="POST" action="/admin/notifikasi/{{ $l->id }}/resend">@csrf<button class="text-[11px] font-bold text-purple-700">↻ Kirim ulang</button></form></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">
@foreach($logs as $l)
<div class="bg-white rounded-3xl shadow-card p-3 mb-1.5 text-xs">
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
</div>
@if($logs->isEmpty())<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3">Belum ada notifikasi. Buat order untuk memicu. 🐝</div>@endif
<div class="mt-2">{{ $logs->links() }}</div>
@endsection
