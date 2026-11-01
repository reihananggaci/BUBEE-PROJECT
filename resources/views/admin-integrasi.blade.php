@extends('layouts.admin')
@section('title','Integrasi Go-Live')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">🔌 Status Integrasi Go-Live</h1>
<div class="text-xs text-slate-500 mb-2">Hijau = live, kuning = dummy. Semua tetap jalan walau kuning.</div>
@foreach([$midtrans, $raja, $fonnte, $captcha] as $it)
<div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between items-center">
<b>{{ $it['nama'] }}</b>
<span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $it['live']?'bg-emerald-200 text-emerald-800':'bg-amber-200 text-amber-800' }}">{{ $it['live']?'🟢 LIVE':'🟡 DUMMY' }}</span>
</div>
<div class="text-xs text-slate-500 mt-1">{{ $it['detail'] }}</div>
@if(isset($it['webhook']))<div class="text-[11px] mt-1">Webhook: <code class="bg-slate-100 px-1 rounded">{{ $it['webhook'] }}</code><br><span class="text-slate-400">Daftarkan URL ini di dashboard Midtrans (butuh URL publik, mis. ngrok saat dev).</span></div>@endif
</div>
@endforeach
<form method="POST" action="/admin/integrasi/tes-wa">@csrf<button class="w-full bg-emerald-500 text-white font-extrabold py-3 rounded-3xl text-sm">💬 KIRIM TES WA KE ADMIN</button></form>
<a href="/admin/pengaturan" class="block text-center mt-2 text-sm font-bold text-purple-700">⚙️ Isi kunci di Pengaturan →</a>
@endsection
