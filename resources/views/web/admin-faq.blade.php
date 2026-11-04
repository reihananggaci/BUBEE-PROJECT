@extends('layouts.web-admin')
@section('title','Kelola FAQ')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
    </span>
    <span>Kelola FAQ Bantuan</span>
</h1>
<div class="text-xs text-slate-500 mb-3 mt-0.5">Tampil di popup beranda, panel bantuan & halaman /bantuan.</div>
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<details class="bg-white rounded-3xl shadow-card p-5 lg:sticky lg:top-20 self-start" open>
<summary class="font-extrabold text-sm cursor-pointer flex items-center gap-1.5"><svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg> <span>FAQ Baru</span></summary>
<form method="POST" action="/admin/faq" class="space-y-1.5 mt-2 text-sm">@csrf
<select name="topik" class="w-full border rounded-2xl px-3 py-2">@foreach(\App\Models\Faq::topiks() as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach</select>
<input name="pertanyaan" placeholder="Pertanyaan *" maxlength="200" class="w-full border rounded-2xl px-3 py-2" required>
<textarea name="jawaban" placeholder="Jawaban *" rows="2" class="w-full border rounded-2xl px-3 py-2" required></textarea>
<div class="flex gap-1.5">
<input name="urutan" type="number" min="0" value="0" placeholder="Urutan" class="flex-1 border rounded-2xl px-3 py-2">
<label class="text-xs flex items-center gap-1 border rounded-2xl px-3"><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
</div>
<button class="w-full text-white font-extrabold py-2.5 rounded-2xl text-sm" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">SIMPAN</button>
</form>
</details>
<div class="lg:col-span-2">
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Topik</th><th class="px-4 py-2">Pertanyaan</th><th class="px-4 py-2">Status</th><th class="px-4 py-2"></th></tr></thead>
<tbody>
@foreach($faqs as $f)<tr class="border-t border-slate-100">
<td class="px-4 py-2 text-xs font-bold">{{ $f->topik }}</td>
<td class="px-4 py-2"><b>{{ $f->pertanyaan }}</b><div class="text-xs text-slate-500">{{ \Str::limit($f->jawaban,120) }}</div></td>
<td class="px-4 py-2"><span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $f->is_active?'bg-emerald-200':'bg-slate-200' }}">{{ $f->is_active?'AKTIF':'OFF' }}</span></td>
<td class="px-4 py-2"><a href="#faq-{{ $f->id }}" class="text-[11px] font-bold text-purple-700">Kelola ↓</a></td>
</tr>@endforeach
</tbody>
</table>
</div>
@foreach($faqs as $f)
<form method="POST" action="/admin/faq/{{ $f->id }}" id="faq-{{ $f->id }}" class="bg-white rounded-3xl shadow-card p-4 mt-2 text-sm space-y-1.5">@csrf @method('PUT')
<div class="flex justify-between items-center">
<select name="topik" class="border rounded-xl px-2 py-1 text-xs font-bold">@foreach(\App\Models\Faq::topiks() as $t)<option value="{{ $t }}" {{ $f->topik===$t?'selected':'' }}>{{ $t }}</option>@endforeach</select>
<span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $f->is_active?'bg-emerald-200':'bg-slate-200' }}">{{ $f->is_active?'AKTIF':'OFF' }}</span>
</div>
<input name="pertanyaan" value="{{ $f->pertanyaan }}" maxlength="200" class="w-full border rounded-xl px-2 py-1.5 font-bold" required>
<textarea name="jawaban" rows="2" class="w-full border rounded-xl px-2 py-1.5 text-xs" required>{{ $f->jawaban }}</textarea>
<div class="flex gap-1.5">
<input name="urutan" type="number" min="0" value="{{ $f->urutan }}" class="flex-1 border rounded-xl px-2 py-1.5 text-xs">
<label class="text-[11px] flex items-center gap-1 border rounded-xl px-2"><input type="checkbox" name="is_active" value="1" {{ $f->is_active?'checked':'' }}> Aktif</label>
<button class="flex-1 text-white font-bold py-1.5 rounded-xl text-xs" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">Simpan</button>
</div>
</form>
<form method="POST" action="/admin/faq/{{ $f->id }}" onsubmit="return confirm('Hapus FAQ ini?')">@csrf @method('DELETE')<button class="text-[11px] text-red-400 underline mt-1">hapus</button></form>
@endforeach
@if($faqs->isEmpty())<div class="bg-white rounded-3xl p-6 text-center text-sm mt-2">Belum ada FAQ.</div>@endif
</div>
</div>
@endsection
