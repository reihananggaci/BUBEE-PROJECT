@extends('layouts.web')
@section('title','Pusat Bantuan')
@section('content')
<h1 class="font-extrabold text-2xl">❓ Pusat Bantuan</h1>
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2">
<form method="GET" action="/bantuan" class="flex gap-2">
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
<div class="bg-white rounded-3xl p-8 text-center text-sm mt-2 shadow-card"><div class="mt-2">Tidak ketemu. Coba kata lain atau chat admin. 👇</div></div>
@else
<div class="grid gap-x-4 lg:grid-cols-2 lg:items-start">
@foreach($faqs as $top => $list)
<div>
<div class="font-extrabold text-sm mt-3 mb-1">{{ $top }}</div>
@foreach($list as $f)
<details class="bg-white rounded-2xl shadow-card px-4 py-3 mb-1.5 text-sm">
<summary class="font-bold cursor-pointer">{{ $f->pertanyaan }}</summary>
<p class="text-xs mt-1" style="color:#475569">{{ $f->jawaban }}</p>
</details>
@endforeach
</div>
@endforeach
</div>
@endif
</div>
<div class="lg:sticky lg:top-20 self-start bg-white rounded-3xl shadow-card p-5 text-sm">
<b>💬 Butuh bantuan cepat?</b>
<div class="text-xs text-slate-500 mt-1">Chat admin, sertakan kode order bila ada.</div>
<a href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Bubee, saya butuh bantuan') }}" target="_blank" class="block text-center mt-3 text-white font-extrabold py-3.5 rounded-3xl text-sm" style="background:linear-gradient(90deg,#059669,#00E5FF);text-decoration:none">💬 Chat Admin via WA</a>
</div>
</div>
@endsection
