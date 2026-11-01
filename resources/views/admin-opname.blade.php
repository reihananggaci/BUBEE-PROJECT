@extends('layouts.admin')
@section('title','Stok Opname')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">📦 Stok Opname</h1>
<div class="text-xs text-slate-500 mb-2">Hitung terpandu per barang, review selisih, baru sahkan. Selisih tercatat otomatis sebagai mutasi.</div>
@if($sesiJalan)
<a href="/admin/opname/{{ $sesiJalan->id }}" class="block rounded-3xl p-4 text-sm text-white shadow-card" style="background:linear-gradient(135deg,#4c1d95,#ec4899);text-decoration:none">▶️ Lanjutkan sesi #{{ $sesiJalan->id }} ({{ $sesiJalan->cakupan }})<br><span class="text-xs opacity-80">Terisi {{ $sesiJalan->terisi() }}/{{ $sesiJalan->total() }} varian</span></a>
@else
<form method="POST" action="/admin/opname/mulai" class="glass rounded-3xl shadow-card p-4 text-sm space-y-2">@csrf
<b>🆕 Mulai sesi baru</b>
<select name="cakupan" class="w-full border rounded-2xl px-3 py-2.5">
<option value="semua">Semua barang</option>
@foreach($kategoris as $kt)<option value="{{ $kt }}">{{ $kt }}</option>@endforeach
</select>
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">MULAI HITUNG 🚀</button>
</form>
@endif
@if($riwayat->count())
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm"><b>🕘 Riwayat sesi</b>
@foreach($riwayat as $s)<div class="text-xs py-1 border-b border-blue-50">Sesi #{{ $s->id }} • {{ $s->cakupan }} • {{ $s->status }} • {{ $s->user->name ?? 'sistem' }} • {{ $s->updated_at->format('d M H:i') }}</div>@endforeach
</div>
@endif
<details class="glass rounded-3xl shadow-card p-4 mt-2 text-sm">
<summary class="font-bold cursor-pointer text-xs">⚡ Mode cepat (form massal lama)</summary>
<form method="POST" action="/admin/opname" onsubmit="return confirm('Simpan hasil opname? Stok akan disesuaikan.')" class="mt-2">@csrf
<div id="opname-progres" class="rounded-2xl bg-purple-50 p-2 mb-2 text-xs font-bold text-purple-900">Terisi 0/{{ $products->sum(fn($p) => $p->variants->count()) }} varian</div>
@php $totalVarian = $products->sum(fn($p) => $p->variants->count()); @endphp
@foreach($products as $p)
<div class="py-1 border-b border-purple-50 last:border-0">
<b class="text-xs">{{ $p->nama }}</b>
@foreach($p->variants as $v)
<div class="flex items-center gap-2 py-1">
<span class="flex-1 text-xs font-bold">{{ $v->label() }} <span class="text-slate-400 font-normal">sistem: {{ $v->stok }}</span> <span class="opname-selisih text-[11px] font-extrabold"></span></span>
<input name="fisik[{{ $v->id }}]" data-sistem="{{ $v->stok }}" type="number" min="0" placeholder="Fisik" class="opname-fisik w-20 border rounded-xl px-2 py-1.5 text-xs text-center font-bold">
</div>
@endforeach
</div>
@endforeach
<button class="w-full text-white font-extrabold py-3 rounded-2xl text-xs mt-2" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">💾 SIMPAN MASSAL</button>
</form>
<script>
(function(){var inputs=document.querySelectorAll('.opname-fisik'),bar=document.getElementById('opname-progres'),total={{ $totalVarian }};function paint(){var isi=0;inputs.forEach(function(i){var s=parseInt(i.dataset.sistem,10),v=i.value===''?null:parseInt(i.value,10);var tag=i.closest('div').querySelector('.opname-selisih');if(v===null){tag.textContent='';return;}isi++;var d=v-s;tag.textContent=(d>0?'+'+d:d);tag.style.color=d===0?'#059669':(d>0?'#1D4ED8':'#dc2626');});bar.textContent='Terisi '+isi+'/'+total+' varian';}inputs.forEach(function(i){i.addEventListener('input',paint);});paint();})();
</script>
</details>
<form method="POST" action="/admin/rekonsiliasi" class="mt-2" onsubmit="return confirm('Selaraskan semua total dengan jumlah varian?')">@csrf<button class="w-full bg-purple-50 text-purple-700 font-bold py-2.5 rounded-2xl text-xs">🔧 Rekonsiliasi otomatis</button></form>
@endsection
