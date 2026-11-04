@extends('layouts.web-admin')
@section('title','Hitung Sesi #'.$sesi->id)
@section('content')
<a href="/admin/opname" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Semua sesi</a>
<h1 class="font-extrabold text-2xl text-slate-900 mt-2 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect width="8" height="4" x="8" y="3" rx="1"/><path d="m9 14 2 2 4-4"/></svg>
    </span>
    <span>Sesi #{{ $sesi->id }} ({{ $sesi->cakupan }})</span>
</h1>
<div class="text-xs text-slate-500">Terisi {{ $sesi->terisi() }}/{{ $sesi->total() }} varian</div>
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="bg-white rounded-3xl shadow-card p-4 text-sm border border-slate-100">
<div class="font-extrabold mb-2 text-slate-900">Daftar barang</div>
<div class="mb-2 relative">
    <input type="text" id="cari-barang-sesi" placeholder="Cari nama barang..." class="w-full border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs bg-slate-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-purple-600 font-medium">
</div>
<div class="space-y-1 max-h-96 overflow-y-auto" id="list-barang-sesi">
@foreach($products as $i => $p)<a href="/admin/opname/{{ $sesi->id }}?p={{ $i }}" class="item-barang-sesi block px-3 py-2 rounded-2xl text-xs font-bold {{ $i===$pos?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}" data-nama="{{ strtolower($p->nama) }}" style="text-decoration:none">{{ $i+1 }}. {{ $p->nama }}</a>@endforeach
</div>
<script>
document.getElementById('cari-barang-sesi')?.addEventListener('input', function() {
    var q = this.value.toLowerCase().trim();
    document.querySelectorAll('.item-barang-sesi').forEach(function(el) {
        el.style.display = (!q || (el.dataset.nama || '').includes(q)) ? 'block' : 'none';
    });
});
</script>
<a href="/admin/opname/{{ $sesi->id }}?belum=1" class="block text-center mt-2 text-xs font-bold text-purple-700">yang belum saja →</a>
</div>
<div class="lg:col-span-2 bg-white rounded-3xl shadow-card p-5">
<div class="font-extrabold text-lg">{{ $pos+1 }}. {{ $product->nama }}</div>
@if($product->foto)<img src="/storage/{{ $product->foto }}" alt="{{ $product->nama }}" class="w-full h-56 rounded-2xl object-cover mt-2">@endif
<form method="POST" action="/admin/opname/{{ $sesi->id }}/hitung" class="mt-3 space-y-2">@csrf
<input type="hidden" name="next" value="{{ $pos+1 }}">
<div class="hidden lg:block overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Varian</th><th class="py-1 text-center">Sistem</th><th class="py-1 text-center">Fisik</th><th class="py-1 text-center">− / +</th></tr></thead>
<tbody>
@foreach($product->variants as $v)
@php $it = $itemMap->get($v->id); @endphp
<tr class="border-t border-slate-100">
<td class="py-1 font-bold text-xs">{{ $v->label() }}</td>
<td class="py-1 text-center text-xs text-slate-400">{{ $v->stok }}</td>
<td class="py-1 text-center"><input name="fisik[{{ $v->id }}]" value="{{ $it->fisik ?? '' }}" type="number" min="0" class="w-24 border rounded-xl px-2 py-2 text-center font-extrabold"></td>
<td class="py-1 text-center"><div class="flex gap-1 justify-center items-center"><button type="button" class="w-9 h-9 rounded-xl bg-slate-100 font-extrabold" onclick="var i=this.closest('tr').querySelector('input');i.stepDown()">−</button><button type="button" class="w-9 h-9 rounded-xl bg-slate-100 font-extrabold" onclick="var i=this.closest('tr').querySelector('input');i.stepUp()">+</button></div></td>
</tr>
@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden space-y-2">
@foreach($product->variants as $v)
@php $it = $itemMap->get($v->id); @endphp
<label class="flex items-center gap-2 border rounded-2xl px-3 py-2 text-sm">
<div class="flex-1"><b>{{ $v->label() }}</b><div class="text-[11px] text-slate-400">sistem: {{ $v->stok }}</div></div>
<input name="fisik[{{ $v->id }}]" value="{{ $it->fisik ?? '' }}" type="number" min="0" class="w-20 border rounded-xl px-2 py-2 text-center font-extrabold">
</label>
@endforeach
</div>
<div class="flex gap-2 mt-3">
@if($pos>0)<a href="/admin/opname/{{ $sesi->id }}?p={{ $pos-1 }}" class="flex-1 text-center bg-slate-100 font-bold py-3 rounded-2xl text-sm" style="text-decoration:none;color:#0f172a">← Sebelumnya</a>@endif
<button class="flex-[2] text-white font-extrabold py-3 rounded-2xl text-sm" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">Simpan & Lanjut →</button>
</div>
</form>
</div>
</div>
<div class="text-center mt-3"><a href="/admin/opname/{{ $sesi->id }}/review" class="text-sm font-bold text-purple-700" style="text-decoration:none">Review & Sahkan →</a></div>
@endsection
