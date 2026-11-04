@extends('layouts.web-admin')
@section('title','Integrasi Go-Live')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    </span>
    <span>Status Integrasi Go-Live</span>
</h1>
<div class="text-xs text-slate-500 mb-3 mt-0.5">Hijau = live, kuning = dummy. Semua tetap jalan walau kuning.</div>
<div class="grid gap-3 lg:grid-cols-2 lg:items-start">
@foreach([$midtrans, $raja, $fonnte, $captcha] as $it)
<div class="bg-white rounded-3xl shadow-card p-5 text-sm border border-slate-100">
<div class="flex justify-between items-center">
<b>{{ $it['nama'] }}</b>
<span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full {{ $it['live']?'bg-emerald-100 text-emerald-800 border border-emerald-300':'bg-amber-100 text-amber-800 border border-amber-300' }}">{{ $it['live']?'LIVE':'DUMMY' }}</span>
</div>
<div class="text-xs text-slate-500 mt-1">{{ $it['detail'] }}</div>
@if(isset($it['webhook']))<div class="text-[11px] mt-1">Webhook: <code class="bg-slate-100 px-1 rounded">{{ $it['webhook'] }}</code><br><span class="text-slate-400">Daftarkan URL ini di dashboard Midtrans (butuh URL publik, mis. ngrok saat dev).</span></div>@endif
</div>
@endforeach
</div>
<div class="grid gap-2 mt-3 lg:grid-cols-2">
<form method="POST" action="/admin/integrasi/tes-wa">@csrf<button class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold py-3 rounded-3xl text-sm transition shadow-sm">KIRIM TES WA KE ADMIN</button></form>
<a href="/admin/pengaturan" class="block text-center text-sm font-bold text-purple-700 bg-white rounded-3xl py-3 shadow-card border border-slate-100 hover:bg-purple-50 transition" style="text-decoration:none">Isi kunci di Pengaturan →</a>
</div>
@endsection
