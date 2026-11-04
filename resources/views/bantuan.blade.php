@extends('layouts.app')
@section('title','Pusat Bantuan')
@section('back', auth()->check() ? '/akun' : '/')
@section('content')
<div class="flex items-center gap-2 mb-2">
    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Pusat Bantuan</h1>
</div>
<form method="GET" action="/bantuan" class="mt-2 flex gap-2">
<input type="hidden" name="topik" value="{{ $topik }}">
<div class="relative flex-1">
    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </span>
    <input name="q" value="{{ $q ?? '' }}" placeholder="Cari jawaban…" class="w-full border rounded-2xl pl-9 pr-3 py-2.5 text-sm outline-none">
</div>
<button class="bg-blue-900 text-white font-extrabold px-4 rounded-2xl text-sm">Cari</button>
</form>
<div class="mt-2 flex gap-1.5 text-[11px] font-bold overflow-x-auto pb-1">
<a href="/bantuan?q={{ $q }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ $topik==='semua'?'bg-blue-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">Semua</a>
@foreach(\App\Models\Faq::topiks() as $t)
<a href="/bantuan?topik={{ $t }}&q={{ $q }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ $topik===$t?'bg-blue-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">{{ $t }}</a>
@endforeach
</div>
@if($faqs->isEmpty())
<div class="glass rounded-3xl p-8 text-center text-sm mt-2"><div class="hexbee mx-auto"><svg class="w-7 h-7 text-slate-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8 2 5 5 5 9c0 2.2 1 4.2 2.5 5.5L7 20l5-2 5 2-.5-5.5C18 13.2 19 11.2 19 9c0-4-3-7-7-7z"/><path d="M9 10h6M8 14h8"/></svg></div><div class="mt-2">Tidak ketemu. Coba kata lain atau hubungi admin.</div></div>
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
<a href="https://wa.me/{{ $wa }}?text={{ urlencode('Halo Bubee, saya butuh bantuan') }}" target="_blank" class="flex items-center justify-center gap-2 mt-3 text-white font-extrabold py-3.5 rounded-3xl text-sm" style="background:linear-gradient(90deg,#059669,#00E5FF);text-decoration:none">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Chat Admin via WhatsApp</span>
</a>
@endsection
