@extends('layouts.app')
@section('title','Pusat Bantuan')
@section('back', auth()->check() ? '/akun' : '/')
@section('content')
<h1 class="font-extrabold text-lg">❓ Pusat Bantuan</h1>
<form method="GET" action="/bantuan" class="mt-2 flex gap-2">
<input type="hidden" name="topik" value="{{ $topik }}">
<input name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari jawaban…" class="flex-1 border rounded-2xl px-4 py-2.5 text-sm outline-none">
<button class="bg-blue-900 text-white font-extrabold px-4 rounded-2xl text-sm">Cari</button>
</form>
<div class="mt-2 flex gap-1.5 text-[11px] font-bold overflow-x-auto pb-1">
<a href="/bantuan?q={{ $q }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ $topik==='semua'?'bg-blue-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">Semua</a>
@foreach(\App\Models\Faq::topiks() as $t)
<a href="/bantuan?topik={{ $t }}&q={{ $q }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ $topik===$t?'bg-blue-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">{{ $t }}</a>
@endforeach
</div>
@if($faqs->isEmpty())
<div class="glass rounded-3xl p-8 text-center text-sm mt-2"><div class="hexbee mx-auto">🐝</div><div class="mt-2">Tidak ketemu. Coba kata lain atau chat admin. 👇</div></div>
@else
@foreach($faqs as $top => $list)
<div class="font-extrabold text-sm mt-3 mb-1">{{ $top }}</div>
@foreach($list as $f)
<details class="glass rounded-2xl shadow-card px-4 py-3 mb-1.5 text-sm">
<summary class="font-bold cursor-pointer">{{ $f->pertanyaan }}</summary>
<p class="text-xs mt-1" style="color:#475569">{{ $f->jawaban }}</p>
</details>
@endforeach
@endforeach
@endif
<a href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Bubee, saya butuh bantuan') }}" target="_blank" class="block text-center mt-3 text-white font-extrabold py-3.5 rounded-3xl text-sm" style="background:linear-gradient(90deg,#059669,#00E5FF);text-decoration:none">💬 Chat Admin via WA</a>
@endsection
