@extends('layouts.admin')
@section('title','Kelola FAQ')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">❓ Kelola FAQ Bantuan</h1>
<div class="text-xs text-slate-500 mb-2">Tampil di popup beranda, panel bantuan & halaman /bantuan.</div>
<details class="glass rounded-3xl shadow-card p-4">
<summary class="font-extrabold text-sm cursor-pointer">➕ FAQ Baru</summary>
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
@foreach($faqs as $f)
<form method="POST" action="/admin/faq/{{ $f->id }}" class="glass rounded-3xl shadow-card p-4 mt-2 text-sm space-y-1.5">@csrf @method('PUT')
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
@if($faqs->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm mt-2">Belum ada FAQ.</div>@endif
@endsection
