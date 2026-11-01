@extends('layouts.web-admin')
@section('title','Integrasi Go-Live')
@section('content')
<h1 class="font-extrabold text-2xl">🔌 Status Integrasi Go-Live</h1>
<div class="text-xs text-slate-500 mb-3">Hijau = live, kuning = dummy. Semua tetap jalan walau kuning.</div>
<div class="grid gap-3 lg:grid-cols-2 lg:items-start">
@foreach([$midtrans, $raja, $fonnte, $captcha] as $it)
<div class="bg-white rounded-3xl shadow-card p-5 text-sm">
<div class="flex justify-between items-center">
<b>{{ $it['nama'] }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $it['live']?'bg-emerald-200 text-emerald-800':'bg-amber-200 text-amber-800' }}">{{ $it['live']?'🟢 LIVE':'🟡 DUMMY' }}</span>
</div>
<div class="text-xs text-slate-500 mt-1">{{ $it['detail'] }}</div>
@if(isset($it['webhook']))<div class="text-[11px] mt-1">Webhook: <code class="bg-slate-100 px-1 rounded">{{ $it['webhook'] }}</code><br><span class="text-slate-400">Daftarkan URL ini di dashboard Midtrans (butuh URL publik, mis. ngrok saat dev).</span></div>@endif
</div>
@endforeach
</div>
<div class="grid gap-2 mt-3 lg:grid-cols-2">
<form method="POST" action="/admin/integrasi/tes-wa">@csrf<button class="w-full bg-emerald-500 text-white font-extrabold py-3 rounded-3xl text-sm">💬 KIRIM TES WA KE ADMIN</button></form>
<a href="/admin/pengaturan" class="block text-center text-sm font-bold text-purple-700 bg-white rounded-3xl py-3 shadow-card" style="text-decoration:none">⚙️ Isi kunci di Pengaturan →</a>
</div>
@endsection
