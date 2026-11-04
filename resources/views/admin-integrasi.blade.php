@extends('layouts.admin')
@section('title','Integrasi Go-Live')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v4"/><path d="M15 3h4a2 2 0 0 1 2 2v4"/><path d="M9 21H5a2 2 0 0 1-2-2v-4"/><path d="M15 21h4a2 2 0 0 0 2-2v-4"/><circle cx="12" cy="12" r="3"/></svg>
    </span>
    <span>Status Integrasi Go-Live</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Hijau = live, kuning = dummy. Semua tetap jalan walau kuning.</div>
@foreach([$midtrans, $raja, $fonnte, $captcha] as $it)
<div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between items-center">
<b>{{ $it['nama'] }}</b>
<span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold px-2.5 py-1 rounded-full {{ $it['live']?'bg-emerald-100 text-emerald-800 border border-emerald-200':'bg-amber-100 text-amber-800 border border-amber-200' }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $it['live']?'bg-emerald-500':'bg-amber-500' }}"></span>
    <span>{{ $it['live']?'LIVE':'DUMMY' }}</span>
</span>
</div>
<div class="text-xs text-slate-500 mt-1">{{ $it['detail'] }}</div>
@if(isset($it['webhook']))<div class="text-[11px] mt-1">Webhook: <code class="bg-slate-100 px-1 rounded">{{ $it['webhook'] }}</code><br><span class="text-slate-400">Daftarkan URL ini di dashboard Midtrans (butuh URL publik, mis. ngrok saat dev).</span></div>@endif
</div>
@endforeach
<form method="POST" action="/admin/integrasi/tes-wa">@csrf<button class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold py-3 rounded-3xl text-sm flex items-center justify-center gap-2 shadow-sm transition">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Kirim Tes WhatsApp ke Admin</span>
</button></form>
<a href="/admin/pengaturan" class="block text-center mt-2 text-sm font-bold text-purple-700">Isi kunci di Pengaturan →</a>
@endsection
