@extends('layouts.app')
@section('title',$product->nama)
@section('back', '/')
@section('content')
<div class="glass rounded-[2rem] shadow-card overflow-hidden">
@php
    $galleryList = $product->semuaFotoWithMeta();
    $totalFoto = count($galleryList);
@endphp
{{-- Galeri Foto / Carousel Slider --}}
<div class="relative overflow-hidden bg-slate-900 group" id="gallery-container">
    <div id="carousel-track" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none w-full h-64 sm:h-72 touch-pan-x select-none" style="scroll-behavior: smooth;">
        @forelse($galleryList as $idx => $item)
            <div class="shrink-0 w-full h-full snap-center relative flex items-center justify-center bg-slate-950/20 overflow-hidden" data-index="{{ $idx }}" data-judul="{{ $item['judul'] }}">
                <img src="/storage/{{ $item['foto'] }}" alt="{{ $product->nama }} - {{ $item['judul'] ?: 'Foto '.($idx + 1) }}" class="w-full h-full object-contain cursor-pointer" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}" onclick="openGalleryModal('/storage/{{ $item['foto'] }}')">
                @if(!empty($item['judul']))
                <span class="absolute top-2 left-2 text-[10px] font-bold bg-black/60 backdrop-blur-sm text-white px-2.5 py-0.5 rounded-full z-10 pointer-events-none">
                    {{ $item['judul'] }}
                </span>
                @endif
            </div>
        @empty
            <div class="shrink-0 w-full h-full flex items-center justify-center text-8xl" style="background:linear-gradient(180deg,#0B2A6B,#1D4ED8)">
                <span class="floaty relative">👕</span>
            </div>
        @endforelse
    </div>

    {{-- Stok Badge --}}
    <span class="absolute bottom-3 left-3 text-[11px] font-bold bg-black/60 backdrop-blur-sm text-white px-3 py-1 rounded-full z-10 pointer-events-none inline-flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5 text-white/90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        <span>Total stok: {{ $product->stok }} • {{ $product->berat_gram }} gr</span>
    </span>

    @if($totalFoto > 1)
        {{-- Counter Badge (e.g. 1 / 5) --}}
        <span id="carousel-counter" class="absolute bottom-3 right-3 text-[11px] font-extrabold bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-full z-10 pointer-events-none inline-flex items-center gap-1">
            <svg class="w-3 h-3 text-white/90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            <span id="counter-num">1 / {{ $totalFoto }}</span>
        </span>

        {{-- Nav Buttons --}}
        <button type="button" onclick="scrollCarousel(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/40 hover:bg-black/70 text-white flex items-center justify-center text-lg font-bold backdrop-blur-sm transition z-10 shadow" aria-label="Foto Sebelumnya">
            ‹
        </button>
        <button type="button" onclick="scrollCarousel(1)" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/40 hover:bg-black/70 text-white flex items-center justify-center text-lg font-bold backdrop-blur-sm transition z-10 shadow" aria-label="Foto Selanjutnya">
            ›
        </button>
    @endif
</div>

@if($totalFoto > 1)
{{-- Thumbnails bar --}}
<div class="flex gap-2 p-2.5 px-3 overflow-x-auto bg-slate-50 border-b border-slate-100 scrollbar-none" id="carousel-thumbnails">
    @foreach($galleryList as $idx => $item)
        <button type="button" onclick="goCarouselTo({{ $idx }})" class="thumb-item relative shrink-0 w-12 h-12 rounded-xl overflow-hidden border-2 transition duration-200 {{ $idx === 0 ? 'border-blue-600 ring-2 ring-blue-300 scale-105' : 'border-transparent opacity-70 hover:opacity-100' }}" data-idx="{{ $idx }}" data-judul="{{ $item['judul'] }}" title="{{ $item['judul'] }}" aria-label="Pilih foto {{ $item['judul'] ?: ($idx + 1) }}">
            <img src="/storage/{{ $item['foto'] }}" class="w-full h-full object-cover">
            @if(!empty($item['judul']))
            <span class="absolute inset-x-0 bottom-0 bg-black/75 text-[7px] text-white font-bold truncate px-0.5 text-center leading-tight py-0.5 pointer-events-none">
                {{ $item['judul'] }}
            </span>
            @endif
        </button>
    @endforeach
</div>
@endif
<div class="p-4">
<h1 class="font-extrabold">{{ $product->nama }}</h1>
<div class="flex items-center gap-2 mt-1 text-sm">
@if($jml>0)<span class="text-amber-500 font-bold">{{ \App\Models\Review::bintang($rata) }}</span><span class="text-xs text-slate-500">{{ $rata }} ({{ $jml }} ulasan)</span>@else<span class="text-xs text-slate-400">Belum ada ulasan</span>@endif
<span class="text-[11px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-bold">{{ $product->kategori }}</span>
</div>
@php
$shareText = auth()->check()
    ? 'Lihat nih ' . $product->nama . ' cuma Rp ' . number_format($product->hargaUntuk(auth()->user())) . ' di Bubee! ' . url('/produk/' . $product->id)
    : 'Lihat nih ' . $product->nama . ' di Bubee! ' . url('/produk/' . $product->id);
$share = 'https://wa.me/?text=' . urlencode($shareText);
@endphp
<a href="{{ $share }}" target="_blank" class="flex items-center justify-center gap-2 mt-2 bg-emerald-50 text-emerald-700 font-bold py-2.5 rounded-2xl text-sm hover:bg-emerald-100 transition" style="text-decoration:none">
    <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
    <span>Bagikan ke WhatsApp</span>
</a>
<p class="text-sm text-slate-500 mt-2">{{ $product->deskripsi }}</p>
@php
    $isRslrDetail = auth()->check() && auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved';
    $hargaDetail = $product->hargaUntuk(auth()->user());
    $daftarUkuran = $product->daftarUkuran();
    $hasUkuran = count($daftarUkuran) > 1;
@endphp
@if(auth()->check())
<div class="mt-2 rounded-2xl p-3 text-sm bg-blue-50 border border-blue-100">
    <div>Harga: <b class="text-lg text-blue-900" id="harga-detail-val">Rp {{ number_format($hargaDetail) }}</b> <span class="text-[11px] font-bold {{ $isRslrDetail ? 'text-emerald-600' : 'text-slate-400' }}">{{ $isRslrDetail ? 'GROSIR' : 'RETAIL' }}</span></div>
    @if($isRslrDetail)
    <div class="text-[11px] text-emerald-700 font-medium mt-0.5">Harga khusus mitra reseller (Mulai 1 pcs / Tanpa Min. Belanja)</div>
    @endif
</div>
@else
<div class="mt-2 rounded-2xl p-3 text-sm bg-blue-50 border border-blue-100 flex items-center justify-between gap-3">
    <div>
        <div class="font-extrabold text-blue-900 flex items-center gap-1.5"><svg class="w-4 h-4 text-blue-700 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Harga Terkunci</div>
        <div class="text-[11px] text-slate-500">Login untuk lihat harga</div>
    </div>
    <a href="/login" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold text-white shrink-0" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Login →</a>
</div>
@endif

@if($hasUkuran)
<div class="mt-3 mb-2 bg-slate-50 p-3 rounded-2xl border border-slate-200">
    <div class="flex items-center justify-between mb-2">
        <label class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
            <span>Pilih Kapasitas / Ukuran:</span>
        </label>
        <span id="label-ukuran-aktif" class="text-xs font-black text-blue-700 bg-blue-100 px-2.5 py-0.5 rounded-full"></span>
    </div>
    <div class="flex flex-wrap gap-2" id="wadah-pilih-ukuran">
        @foreach($daftarUkuran as $iUk => $uk)
        <button type="button" data-ukuran="{{ $uk }}" class="btn-ukuran px-3.5 py-2 rounded-xl text-xs font-bold border-2 transition-all flex items-center gap-1.5 cursor-pointer {{ $iUk === 0 ? 'border-blue-600 bg-blue-600 text-white shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:border-blue-300' }}">
            <span>{{ $uk }}</span>
        </button>
        @endforeach
    </div>
</div>
@endif

@if(auth()->check())
@php($varianTerpilih = $product->variants->first(fn($v) => $v->stok > 0)?->id)
<form id="form-keranjang" method="POST" action="/keranjang/{{ $product->id }}" class="mt-3 space-y-2">@csrf
<label class="text-sm font-bold text-slate-800">{{ $hasUkuran ? 'Pilih Warna' : 'Pilih Varian' }}</label>
<div class="grid grid-cols-2 gap-1.5" id="wadah-varian-kartu">
@foreach($product->variants as $v)
@php($hargaVarian = $product->hargaUntuk(auth()->user(), $v))
<label data-stok="{{ $v->stok }}" data-label="{{ $v->label() }}" data-warna="{{ $v->warna }}" data-ukuran="{{ $v->ukuran }}" data-harga="{{ $hargaVarian }}" class="kartu-varian border-2 rounded-2xl px-3 py-2 text-center text-sm cursor-pointer transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 {{ $v->stok<=0?'opacity-40':'' }}">
<input type="radio" name="variant_id" value="{{ $v->id }}" class="sr-only" {{ $varianTerpilih === $v->id ? 'checked' : '' }} {{ $v->stok<=0?'disabled':'' }}>
<div class="font-bold">{{ $hasUkuran ? $v->warna : $v->label() }}</div>
<div class="text-[11px] {{ $v->stok<=0?'text-red-500':'text-slate-500' }}">{{ $v->stok<=0?'Habis':'Sisa '.$v->stok }}</div>
</label>
@endforeach
</div>
<div id="varian-info" class="text-[11px] text-slate-500"></div>
<div class="flex gap-2">
<input type="number" name="qty" value="1" min="1" max="{{ max(1,$product->stok) }}" class="w-20 border rounded-2xl px-3 py-2.5 text-center font-bold">
<button id="tombol-keranjang" class="flex-1 text-white font-extrabold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)" {{ $product->stok<=0?'disabled':'' }}>{{ $product->stok<=0?'STOK HABIS':'+ KERANJANG' }}</button>
</div>
</form>
<script>
(function(){
    var form = document.getElementById('form-keranjang');
    if (!form) return;
    var info = document.getElementById('varian-info');
    var qty = form.querySelector('input[name="qty"]');
    var tombol = document.getElementById('tombol-keranjang');
    var hargaEl = document.getElementById('harga-detail-val');
    var labelUkuranAktif = document.getElementById('label-ukuran-aktif');
    var tombolUkuran = document.querySelectorAll('.btn-ukuran');
    var kartuVarian = form.querySelectorAll('.kartu-varian');

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function switchUkuran(ukuran) {
        if (labelUkuranAktif) {
            labelUkuranAktif.textContent = ukuran;
        }
        tombolUkuran.forEach(function(btn){
            if (btn.dataset.ukuran === ukuran) {
                btn.className = 'btn-ukuran px-3.5 py-2 rounded-xl text-xs font-bold border-2 transition-all flex items-center gap-1.5 cursor-pointer border-blue-600 bg-blue-600 text-white shadow-sm';
            } else {
                btn.className = 'btn-ukuran px-3.5 py-2 rounded-xl text-xs font-bold border-2 transition-all flex items-center gap-1.5 cursor-pointer border-slate-200 bg-white text-slate-700 hover:border-blue-300';
            }
        });

        var visibleFirstAvailable = null;
        var currentlyCheckedStillVisible = false;

        kartuVarian.forEach(function(k){
            var match = !ukuran || k.dataset.ukuran === ukuran;
            var radio = k.querySelector('input[name="variant_id"]');
            if (match) {
                k.classList.remove('hidden');
                if (radio.checked) {
                    currentlyCheckedStillVisible = true;
                }
                var stok = parseInt(k.dataset.stok, 10) || 0;
                if (!visibleFirstAvailable && stok > 0) {
                    visibleFirstAvailable = radio;
                }
            } else {
                k.classList.add('hidden');
                radio.checked = false;
            }
        });

        if (!currentlyCheckedStillVisible) {
            if (visibleFirstAvailable) {
                visibleFirstAvailable.checked = true;
            } else {
                var firstVisible = form.querySelector('.kartu-varian:not(.hidden) input[name="variant_id"]');
                if (firstVisible) firstVisible.checked = true;
            }
        }

        sync();
    }

    tombolUkuran.forEach(function(btn){
        btn.addEventListener('click', function(){
            switchUkuran(btn.dataset.ukuran);
        });
    });

    function sync(){
        var pilih = form.querySelector('input[name="variant_id"]:checked');
        if (!pilih || pilih.closest('.kartu-varian')?.classList.contains('hidden')) {
            tombol.disabled = true;
            tombol.textContent = 'PILIH VARIAN';
            info.textContent = 'Silakan pilih varian yang tersedia.';
            return;
        }
        var kartu = pilih.closest('.kartu-varian');
        var stok = parseInt(kartu.dataset.stok, 10) || 0;
        qty.max = Math.max(1, stok);
        if (parseInt(qty.value, 10) > stok) qty.value = Math.max(1, stok);
        info.textContent = 'Dipilih: ' + kartu.dataset.label + ' • sisa ' + stok;
        tombol.disabled = stok <= 0;
        tombol.textContent = stok <= 0 ? 'STOK HABIS' : '+ KERANJANG';

        if (hargaEl && kartu.dataset.harga) {
            hargaEl.textContent = formatRupiah(kartu.dataset.harga);
        }
        if (kartu.dataset.warna) {
            jumpToVariantColorPhoto(kartu.dataset.warna);
        }
    }

    function jumpToVariantColorPhoto(warna) {
        if (!warna) return;
        var clean = warna.toLowerCase().replace(/\s*\([a-z0-9]+\)/gi, '').trim();
        var full = warna.toLowerCase().trim();
        var slides = document.querySelectorAll('#carousel-track [data-index]');
        for (var i = 0; i < slides.length; i++) {
            var j = (slides[i].dataset.judul || '').toLowerCase();
            if (j && (j.includes(full) || (clean && j.includes(clean)))) {
                if (typeof window.goCarouselTo === 'function') {
                    window.goCarouselTo(i);
                }
                break;
            }
        }
    }

    form.querySelectorAll('input[name="variant_id"]').forEach(function(r){ r.addEventListener('change', sync); });
    qty.addEventListener('change', sync);
    form.addEventListener('submit', function(e){
        var pilih = form.querySelector('input[name="variant_id"]:checked');
        if (!pilih || (parseInt(pilih.closest('.kartu-varian')?.dataset?.stok, 10) || 0) <= 0) {
            e.preventDefault();
            alert('Pilih varian yang masih tersedia.');
        }
    });

    if (tombolUkuran.length > 0) {
        switchUkuran(tombolUkuran[0].dataset.ukuran);
    } else {
        sync();
    }
})();
</script>
@else<a href="/login" class="block text-center mt-3 text-white font-bold py-3 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Login untuk Lihat Harga & Order →</a>@endif
</div>
</div>
{{-- ULASAN --}}
<div id="ulasan" class="glass rounded-[2rem] shadow-card p-4 mt-3">
<div class="font-extrabold text-sm flex items-center gap-1.5 text-slate-900">
    <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    <span>Ulasan Pembeli ({{ $jml }})</span>
</div>
@if($bolehUlas)
<details class="mt-2 text-sm bg-amber-50 border border-amber-100 rounded-2xl p-3">
<summary class="font-bold cursor-pointer inline-flex items-center gap-1.5 text-amber-900">
    <svg class="w-3.5 h-3.5 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    <span>Tulis ulasanmu</span>
</summary>
<form method="POST" action="/produk/{{ $product->id }}/ulasan" enctype="multipart/form-data" class="mt-2 space-y-1.5">@csrf
<select name="rating" class="w-full border rounded-2xl px-3 py-2"><option value="5">★★★★★ (5)</option><option value="4">★★★★ (4)</option><option value="3">★★★ (3)</option><option value="2">★★ (2)</option><option value="1">★ (1)</option></select>
<textarea name="ulasan" placeholder="Ceritakan pengalamanmu…" class="w-full border rounded-2xl px-3 py-2"></textarea>
<input type="file" name="foto" accept="image/*" class="w-full text-xs">
<button class="w-full bg-amber-400 font-extrabold py-2.5 rounded-2xl text-sm">KIRIM</button>
</form>
</details>
@endif
@foreach($ulasan as $u)
<div class="py-2 border-b border-blue-50 last:border-0 text-sm">
<div class="flex justify-between"><b>{{ $u->user->name ?? 'Anonim' }}</b><span class="text-amber-500 text-xs">{{ str_repeat('★',$u->rating) }}{{ str_repeat('☆',5-$u->rating) }}</span></div>
@if($u->ulasan)<div class="text-xs text-slate-600">{{ $u->ulasan }}</div>@endif
@if($u->foto)<a href="/storage/{{ $u->foto }}" target="_blank"><img src="/storage/{{ $u->foto }}" class="w-20 h-20 rounded-xl object-cover mt-1"></a>@endif
<div class="text-[10px] text-slate-400">{{ $u->created_at->format('d M Y') }}</div>
</div>
@endforeach
@if($jml===0)<div class="text-xs text-slate-400 mt-1">Jadilah yang pertama mengulas setelah order selesai!</div>@endif
</div>
<div>
<div class="font-extrabold text-sm mt-4 mb-2 flex items-center gap-1.5 text-slate-900">
    <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
    <span>Mungkin kamu suka</span>
</div>
<div class="grid grid-cols-2 gap-2">
@foreach($lainnya as $l)<a href="/produk/{{ $l->id }}" class="glass rounded-3xl p-3 shadow-card card-hover flex flex-col h-full"><div class="text-3xl text-center">@if($l->foto)<img src="/storage/{{ $l->foto }}" alt="{{ $l->nama }}" class="w-full h-20 rounded-2xl object-cover">@else👕@endif</div><div class="text-[12px] font-bold mt-1">{{ $l->nama }}</div>@if(auth()->check())<div class="text-[12px] text-blue-800 font-extrabold" style="margin-top:auto">Rp {{ number_format($l->hargaUntuk(auth()->user())) }}</div>@else<div style="margin-top:auto"><span class="price-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4.5" y="10.5" width="15" height="9.5" rx="2.5"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/></svg>Login untuk lihat harga</span></div>@endif</a>@endforeach
</div>
</div>
{{-- Lightbox Modal --}}
<div id="gallery-lightbox" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden items-center justify-center p-4 transition-opacity duration-300" onclick="closeGalleryModal()">
    <button type="button" onclick="closeGalleryModal()" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/20 text-white font-extrabold flex items-center justify-center text-lg hover:bg-white/40 transition">✕</button>
    <img id="lightbox-img" src="" alt="Full preview" class="max-w-full max-h-[85vh] rounded-2xl object-contain shadow-2xl" onclick="event.stopPropagation()">
</div>

<script>
(function(){
    var track = document.getElementById('carousel-track');
    if (!track) return;
    var counter = document.getElementById('carousel-counter');
    var thumbs = document.querySelectorAll('#carousel-thumbnails .thumb-item');
    var total = {{ $totalFoto }};

    function updateState(idx) {
        if (idx < 0) idx = 0;
        if (idx >= total) idx = total - 1;
        var numEl = document.getElementById('counter-num');
        if (numEl) {
            numEl.textContent = (idx + 1) + ' / ' + total;
        } else if (counter) {
            counter.textContent = (idx + 1) + ' / ' + total;
        }
        thumbs.forEach(function(btn, i) {
            if (i === idx) {
                btn.classList.add('border-blue-600', 'ring-2', 'ring-blue-300', 'scale-105');
                btn.classList.remove('border-transparent', 'opacity-60');
                btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                btn.classList.remove('border-blue-600', 'ring-2', 'ring-blue-300', 'scale-105');
                btn.classList.add('border-transparent', 'opacity-60');
            }
        });
    }

    window.scrollCarousel = function(dir) {
        var w = track.clientWidth;
        track.scrollBy({ left: dir * w, behavior: 'smooth' });
    };

    window.goCarouselTo = function(idx) {
        var w = track.clientWidth;
        track.scrollTo({ left: idx * w, behavior: 'smooth' });
        updateState(idx);
    };

    var scrollTimeout;
    track.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            var w = track.clientWidth;
            if (w > 0) {
                var idx = Math.round(track.scrollLeft / w);
                updateState(idx);
            }
        }, 50);
    }, { passive: true });

    window.openGalleryModal = function(src) {
        var modal = document.getElementById('gallery-lightbox');
        var img = document.getElementById('lightbox-img');
        if (!modal || !img) return;
        img.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.closeGalleryModal = function() {
        var modal = document.getElementById('gallery-lightbox');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };
})();
</script>
@endsection
