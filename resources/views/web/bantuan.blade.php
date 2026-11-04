@extends('layouts.web')
@section('title','Pusat Bantuan')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.3a2.6 2.6 0 0 1 5 .9c0 1.7-2.4 2-2.4 3.6"/><circle cx="12" cy="17.2" r=".5" fill="currentColor"/></svg>
    </span>
    <span>Pusat Bantuan</span>
</h1>
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2">
<form method="GET" action="/bantuan" class="flex gap-2">
<input type="hidden" name="topik" value="{{ $topik }}">
<div class="relative flex-1">
    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </span>
    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari jawaban atau topik bantuan…" class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-2xl text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 bg-white">
</div>
<button class="bg-blue-900 hover:bg-blue-800 text-white font-extrabold px-5 rounded-2xl text-sm transition">Cari</button>
</form>
<div class="mt-2 flex gap-1.5 text-[11px] font-bold overflow-x-auto pb-1">
<a href="/bantuan?q={{ $q }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ $topik==='semua'?'bg-blue-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">Semua</a>
@foreach(\App\Models\Faq::topiks() as $t)
<a href="/bantuan?topik={{ $t }}&q={{ $q }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ $topik===$t?'bg-blue-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">{{ $t }}</a>
@endforeach
</div>
@if($faqs->isEmpty())
<div class="bg-white rounded-3xl p-8 text-center text-sm mt-2 shadow-card"><div class="mt-2 text-slate-600">Tidak ketemu. Coba kata lain atau chat admin melalui tombol bantuan di samping.</div></div>
@else
<div class="grid gap-x-4 lg:grid-cols-2 lg:items-start">
@foreach($faqs as $top => $list)
<div>
<div class="font-extrabold text-sm mt-3 mb-1 text-slate-900">{{ $top }}</div>
@foreach($list as $f)
<details class="bg-white rounded-2xl shadow-card px-4 py-3 mb-1.5 text-sm">
<summary class="font-bold cursor-pointer text-slate-800">{{ $f->pertanyaan }}</summary>
<p class="text-xs mt-1" style="color:#475569">{{ $f->jawaban }}</p>
</details>
@endforeach
</div>
@endforeach
</div>
@endif
</div>
<div class="lg:sticky lg:top-20 self-start bg-white rounded-3xl shadow-card p-5 text-sm">
<b class="flex items-center gap-1.5 text-slate-900">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Butuh bantuan cepat?</span>
</b>
<div class="text-xs text-slate-500 mt-1">Chat admin, sertakan kode order bila ada.</div>
<a href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Bubee, saya butuh bantuan') }}" target="_blank" class="flex items-center justify-center gap-2 mt-3 text-white font-extrabold py-3.5 rounded-3xl text-sm shadow-sm transition hover:opacity-95" style="background:linear-gradient(90deg,#059669,#00E5FF);text-decoration:none">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Chat Admin via WA</span>
</a>
</div>
</div>
@endsection
