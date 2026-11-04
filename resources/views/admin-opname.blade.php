@extends('layouts.admin')
@section('title','Stok Opname')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg flex items-center gap-2">
    <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/></svg>
    </span>
    <span>Stok Opname</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Hitung terpandu per barang, review selisih, baru sahkan. Selisih tercatat otomatis sebagai mutasi.</div>
@if($sesiJalan)
<a href="/admin/opname/{{ $sesiJalan->id }}" class="block rounded-3xl p-4 text-sm text-white shadow-card" style="background:linear-gradient(135deg,#4c1d95,#ec4899);text-decoration:none">
    <span class="font-bold flex items-center gap-1.5"><svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><polygon points="6 3 20 12 6 21 6 3"/></svg> Lanjutkan sesi #{{ $sesiJalan->id }} ({{ $sesiJalan->cakupan }})</span>
    <span class="text-xs opacity-80 mt-1 block">Terisi {{ $sesiJalan->terisi() }}/{{ $sesiJalan->total() }} varian</span>
</a>
@else
<form method="POST" action="/admin/opname/mulai" class="glass rounded-3xl shadow-card p-4 text-sm space-y-2">@csrf
<b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Mulai sesi baru</b>
<select name="cakupan" class="w-full border rounded-2xl px-3 py-2.5">
<option value="semua">Semua barang</option>
@foreach($kategoris as $kt)<option value="{{ $kt }}">{{ $kt }}</option>@endforeach
</select>
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">MULAI HITUNG →</button>
</form>
@endif
@if($riwayat->count())
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm"><b class="flex items-center gap-1.5"><span class="text-purple-700">●</span> Riwayat sesi</b>
@foreach($riwayat as $s)<div class="text-xs py-1 border-b border-blue-50">Sesi #{{ $s->id }} • {{ $s->cakupan }} • {{ $s->status }} • {{ $s->user->name ?? 'sistem' }} • {{ $s->updated_at->format('d M H:i') }}</div>@endforeach
</div>
@endif

<details class="glass rounded-3xl shadow-card p-4 mt-2 text-sm" open>
<summary class="font-bold cursor-pointer text-xs flex items-center gap-1.5"><span class="text-purple-700">●</span> Mode cepat (form massal)</summary>
<form method="POST" action="/admin/opname" onsubmit="return confirm('Simpan hasil opname? Stok akan disesuaikan.')" class="mt-2">@csrf
@php $totalVarian = $products->sum(fn($p) => $p->variants->count()); @endphp

<div id="opname-progres" class="rounded-2xl bg-purple-50 p-2.5 mb-2 text-xs font-bold text-purple-900">Terisi 0/{{ $totalVarian }} varian</div>

{{-- PENCARIAN --}}
<div class="mb-3">
    <input type="text" id="opname-search-m" placeholder="Cari nama / varian produk..." class="w-full border rounded-xl px-3 py-2 text-xs bg-white">
</div>

{{-- PAGINASI PANAH ATAS --}}
<div class="flex items-center justify-between text-xs mb-2 opname-pag-m">
    <button type="button" class="btn-prev-m px-3 py-1 bg-slate-100 rounded-xl font-bold">❮ Sebelumnya</button>
    <span class="info-pag-m font-bold text-slate-500">1 / 1</span>
    <button type="button" class="btn-next-m px-3 py-1 bg-slate-100 rounded-xl font-bold">Berikutnya ❯</button>
</div>

<div id="opname-list-m" class="space-y-3">
@foreach($products as $p)
@php $varNama = $p->variants->pluck('warna')->implode(' ') . ' ' . $p->variants->pluck('ukuran')->implode(' '); @endphp
<div class="opname-card-m py-2 border-b border-purple-50 last:border-0 bg-white/60 p-2.5 rounded-2xl" data-nama="{{ strtolower($p->nama) }}" data-variants="{{ strtolower($varNama) }}">
<b class="text-xs text-slate-900">{{ $p->nama }}</b>
@foreach($p->variants as $v)
<div class="flex items-center gap-2 py-1">
<span class="flex-1 text-xs font-bold">{{ $v->label() }} <span class="text-slate-400 font-normal">sistem: {{ $v->stok }}</span> <span class="opname-selisih text-[11px] font-extrabold"></span></span>
<input name="fisik[{{ $v->id }}]" data-sistem="{{ $v->stok }}" type="number" min="0" placeholder="Fisik" class="opname-fisik w-20 border rounded-xl px-2 py-1.5 text-xs text-center font-bold">
</div>
@endforeach
</div>
@endforeach
</div>

{{-- PAGINASI PANAH BAWAH --}}
<div class="flex items-center justify-between text-xs mt-3 opname-pag-m">
    <button type="button" class="btn-prev-m px-3 py-1 bg-slate-100 rounded-xl font-bold">❮ Sebelumnya</button>
    <span class="info-pag-m font-bold text-slate-500">1 / 1</span>
    <button type="button" class="btn-next-m px-3 py-1 bg-slate-100 rounded-xl font-bold">Berikutnya ❯</button>
</div>

<button class="w-full text-white font-extrabold py-3 rounded-2xl text-xs mt-3 shadow-sm" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">SIMPAN MASSAL</button>
</form>

<script>
(function(){
    var inputs = document.querySelectorAll('.opname-fisik'),
        bar = document.getElementById('opname-progres'),
        total = {{ $totalVarian }},
        cards = Array.from(document.querySelectorAll('.opname-card-m')),
        search = document.getElementById('opname-search-m'),
        page = 1,
        perPage = 6;

    function getFiltered() {
        var q = (search ? search.value : '').toLowerCase().trim();
        return cards.filter(function(c) {
            if (!q) return true;
            return (c.dataset.nama || '').includes(q) || (c.dataset.variants || '').includes(q);
        });
    }

    function render() {
        var filtered = getFiltered();
        var totalPages = Math.max(1, Math.ceil(filtered.length / perPage));
        if (page > totalPages) page = totalPages;
        if (page < 1) page = 1;
        var start = (page - 1) * perPage;
        var end = Math.min(start + perPage, filtered.length);

        cards.forEach(function(c) { c.style.display = 'none'; });
        for (var i = start; i < end; i++) {
            if (filtered[i]) filtered[i].style.display = 'block';
        }

        document.querySelectorAll('.info-pag-m').forEach(function(el) {
            el.textContent = 'Hal ' + page + ' dari ' + totalPages + ' (' + filtered.length + ' produk)';
        });
    }

    document.querySelectorAll('.btn-prev-m').forEach(function(b) {
        b.addEventListener('click', function() { if (page > 1) { page--; render(); } });
    });
    document.querySelectorAll('.btn-next-m').forEach(function(b) {
        b.addEventListener('click', function() {
            var filtered = getFiltered();
            var totalPages = Math.max(1, Math.ceil(filtered.length / perPage));
            if (page < totalPages) { page++; render(); }
        });
    });

    if (search) {
        search.addEventListener('input', function() { page = 1; render(); });
    }

    function paint(){
        var isi=0;
        inputs.forEach(function(i){
            var s=parseInt(i.dataset.sistem,10),
                v=i.value===''?null:parseInt(i.value,10);
            var tag=i.closest('div').querySelector('.opname-selisih');
            if(v===null || isNaN(v)){
                if (tag) tag.textContent='';
                return;
            }
            isi++;
            var d=v-s;
            if (tag) {
                tag.textContent=(d>0?'+'+d:d);
                tag.style.color=d===0?'#059669':(d>0?'#1D4ED8':'#dc2626');
            }
        });
        if (bar) bar.textContent='Terisi '+isi+'/'+total+' varian';
    }

    inputs.forEach(function(i){i.addEventListener('input',paint);});
    paint();
    render();
})();
</script>
</details>

<form method="POST" action="/admin/rekonsiliasi" class="mt-2" onsubmit="return confirm('Selaraskan semua total dengan jumlah varian?')">@csrf<button class="w-full bg-purple-50 text-purple-700 font-bold py-2.5 rounded-2xl text-xs inline-flex items-center justify-center gap-1.5"><svg class="w-3.5 h-3.5 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg><span>Rekonsiliasi otomatis</span></button></form>
@endsection
