@extends('layouts.web')
@section('title',$product->nama)
@section('content')
<a href="/" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali ke Home</a>
{{-- Galeri + panel sticky desktop --}}
<div class="grid gap-4 mt-2 lg:grid-cols-2 lg:items-start">
<div class="bg-white border border-slate-100 rounded-3xl shadow-card overflow-hidden">
@php
    $galleryImages = $product->semuaFoto();
    $totalFoto = count($galleryImages);
@endphp
{{-- Galeri Foto Desktop --}}
<div class="relative overflow-hidden bg-slate-900 group" id="gallery-container-web">
    <div id="carousel-track-web" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none w-full h-80 sm:h-96 touch-pan-x select-none" style="scroll-behavior: smooth;">
        @forelse($galleryImages as $idx => $img)
            <div class="shrink-0 w-full h-full snap-center relative flex items-center justify-center bg-slate-950/20 overflow-hidden" data-index="{{ $idx }}">
                <img src="/storage/{{ $img }}" alt="{{ $product->nama }} - Foto {{ $idx + 1 }}" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition duration-300" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}" onclick="openGalleryModalWeb('/storage/{{ $img }}')">
            </div>
        @empty
            <div class="shrink-0 w-full h-full flex items-center justify-center text-8xl" style="background:linear-gradient(180deg,#0B2A6B,#1D4ED8)">
                <span class="relative">👕</span>
            </div>
        @endforelse
    </div>

    {{-- Stok Badge --}}
    <span class="absolute bottom-3 left-3 text-[11px] font-bold bg-black/60 backdrop-blur-sm text-white px-3 py-1 rounded-full z-10 pointer-events-none">
        📦 Total stok: {{ $product->stok }} • {{ $product->berat_gram }} gr
    </span>

    @if($totalFoto > 1)
        {{-- Counter Badge (e.g. 1 / 5) --}}
        <span id="carousel-counter-web" class="absolute bottom-3 right-3 text-[11px] font-extrabold bg-black/60 backdrop-blur-sm text-white px-2.5 py-1 rounded-full z-10 pointer-events-none">
            📷 1 / {{ $totalFoto }}
        </span>

        {{-- Nav Buttons --}}
        <button type="button" onclick="scrollCarouselWeb(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/40 hover:bg-black/75 text-white flex items-center justify-center text-xl font-bold backdrop-blur-sm transition z-10 shadow cursor-pointer" aria-label="Foto Sebelumnya">
            ‹
        </button>
        <button type="button" onclick="scrollCarouselWeb(1)" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/40 hover:bg-black/75 text-white flex items-center justify-center text-xl font-bold backdrop-blur-sm transition z-10 shadow cursor-pointer" aria-label="Foto Selanjutnya">
            ›
        </button>
    @endif
</div>

@if($totalFoto > 1)
{{-- Thumbnails bar Desktop --}}
<div class="flex gap-2.5 p-3 overflow-x-auto bg-slate-50 border-b border-slate-100 scrollbar-none" id="carousel-thumbnails-web">
    @foreach($galleryImages as $idx => $img)
        <button type="button" onclick="goCarouselWebTo({{ $idx }})" class="thumb-item-web shrink-0 w-16 h-16 rounded-2xl overflow-hidden border-2 transition duration-200 cursor-pointer {{ $idx === 0 ? 'border-blue-600 ring-2 ring-blue-300 scale-105' : 'border-transparent opacity-60 hover:opacity-100' }}" data-idx="{{ $idx }}" aria-label="Pilih foto {{ $idx + 1 }}">
            <img src="/storage/{{ $img }}" class="w-full h-full object-cover">
        </button>
    @endforeach
</div>
@endif
<div class="p-5">
<h1 class="font-extrabold text-xl">{{ $product->nama }}</h1>
<div class="flex items-center gap-2 mt-1 text-sm">
@if($jml>0)<span class="text-amber-500 font-bold">{{ \App\Models\Review::bintang($rata) }}</span><span class="text-xs text-slate-500">{{ $rata }} ({{ $jml }} ulasan)</span>@else<span class="text-xs text-slate-400">Belum ada ulasan</span>@endif
<span class="text-[11px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-bold">{{ $product->kategori }}</span>
</div>
<p class="text-sm text-slate-500 mt-2">{{ $product->deskripsi }}</p>
</div>
</div>
<div class="lg:sticky lg:top-20 self-start bg-white border border-slate-100 rounded-3xl shadow-card p-5">
@php
$shareText = auth()->check()
    ? 'Lihat nih ' . $product->nama . ' cuma Rp ' . number_format($product->hargaUntuk(auth()->user())) . ' di Bubee! ' . url('/produk/' . $product->id)
    : 'Lihat nih ' . $product->nama . ' di Bubee! ' . url('/produk/' . $product->id);
$share = 'https://wa.me/?text=' . urlencode($shareText);
@endphp
<a href="{{ $share }}" target="_blank" class="block text-center bg-emerald-50 text-emerald-700 font-bold py-2.5 rounded-2xl text-sm" style="text-decoration:none">📤 Bagikan ke WA</a>
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
        <div class="font-extrabold text-blue-900 flex items-center gap-1.5"><svg class="w-4 h-4 text-blue-700 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Harga Terkunci 🔒</div>
        <div class="text-[11px] text-slate-500">Login untuk lihat harga</div>
    </div>
    <a href="/login" class="px-4 py-2 rounded-xl text-xs font-extrabold text-white shrink-0" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8);text-decoration:none">Login →</a>
</div>
@endif

@if($hasUkuran)
<div class="mt-3 mb-2 bg-slate-50 p-3 rounded-2xl border border-slate-200">
    <div class="flex items-center justify-between mb-2">
        <label class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <span>📏 Pilih Kapasitas / Ukuran:</span>
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
<label data-stok="{{ $v->stok }}" data-label="{{ $v->label() }}" data-ukuran="{{ $v->ukuran }}" data-harga="{{ $hargaVarian }}" class="kartu-varian border-2 rounded-2xl px-3 py-2 text-center text-sm cursor-pointer transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 {{ $v->stok<=0?'opacity-40':'' }}">
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
@else<a href="/login" class="block text-center mt-3 text-white font-bold py-3 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8);text-decoration:none">Login untuk Lihat Harga & Order →</a>@endif
</div>
</div>
{{-- Ulasan + rekomendasi --}}
<div class="grid gap-4 mt-4 lg:grid-cols-2 lg:items-start">
<div class="bg-white border border-slate-100 rounded-3xl shadow-card p-5">
<div class="font-extrabold text-sm">⭐ Ulasan Pembeli ({{ $jml }})</div>
@if($bolehUlas)
<details class="mt-2 text-sm bg-amber-50 border border-amber-100 rounded-2xl p-3">
<summary class="font-bold cursor-pointer">✍️ Tulis ulasanmu</summary>
<form method="POST" action="/produk/{{ $product->id }}/ulasan" enctype="multipart/form-data" class="mt-2 space-y-1.5">@csrf
<select name="rating" class="w-full border rounded-2xl px-3 py-2"><option value="5">⭐⭐⭐⭐⭐ (5)</option><option value="4">⭐⭐⭐⭐ (4)</option><option value="3">⭐⭐⭐ (3)</option><option value="2">⭐⭐ (2)</option><option value="1">⭐ (1)</option></select>
<textarea name="ulasan" placeholder="Ceritakan pengalamanmu…" class="w-full border rounded-2xl px-3 py-2"></textarea>
<input type="file" name="foto" accept="image/*" class="w-full text-xs">
<button class="w-full bg-amber-400 font-extrabold py-2.5 rounded-2xl text-sm">KIRIM</button>
</form>
</details>
@endif
@foreach($ulasan as $u)
<div class="py-2 border-b border-blue-50 text-sm">
<div class="flex justify-between"><b>{{ $u->user->name ?? 'Anonim' }}</b><span class="text-amber-500 text-xs">{{ str_repeat('★',$u->rating) }}{{ str_repeat('☆',5-$u->rating) }}</span></div>
@if($u->ulasan)<div class="text-xs text-slate-600">{{ $u->ulasan }}</div>@endif
@if($u->foto)<a href="/storage/{{ $u->foto }}" target="_blank"><img src="/storage/{{ $u->foto }}" class="w-20 h-20 rounded-xl object-cover mt-1" alt="foto ulasan"></a>@endif
<div class="text-[10px] text-slate-400">{{ $u->created_at->format('d M Y') }}</div>
</div>
@endforeach
@if($jml===0)<div class="text-xs text-slate-400 mt-1">Jadilah yang pertama mengulas setelah order selesai! 🐝</div>@endif
</div>
<div>
<div class="font-extrabold text-sm mb-2">✨ Mungkin kamu suka</div>
<div class="grid grid-cols-2 gap-2 lg:grid-cols-2">
@foreach($lainnya as $l)<a href="/produk/{{ $l->id }}" class="bg-white border border-slate-100 rounded-3xl p-3 shadow-card flex flex-col h-full" style="text-decoration:none"><div class="text-3xl text-center">@if($l->foto)<img src="/storage/{{ $l->foto }}" alt="{{ $l->nama }}" class="w-full h-20 rounded-2xl object-cover">@else👕@endif</div><div class="text-[12px] font-bold mt-1 text-slate-900">{{ $l->nama }}</div>@if(auth()->check())<div class="text-[12px] text-blue-800 font-extrabold" style="margin-top:auto">Rp {{ number_format($l->hargaUntuk(auth()->user())) }}</div>@else<div style="margin-top:auto"><span class="price-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4.5" y="10.5" width="15" height="9.5" rx="2.5"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/></svg>Login untuk lihat harga</span></div>@endif</a>@endforeach
</div>
</div>
</div>
{{-- Lightbox Modal Web --}}
<div id="gallery-lightbox-web" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden items-center justify-center p-4 transition-opacity duration-300" onclick="closeGalleryModalWeb()">
    <button type="button" onclick="closeGalleryModalWeb()" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/20 text-white font-extrabold flex items-center justify-center text-lg hover:bg-white/40 transition cursor-pointer">✕</button>
    <img id="lightbox-img-web" src="" alt="Full preview" class="max-w-full max-h-[88vh] rounded-2xl object-contain shadow-2xl" onclick="event.stopPropagation()">
</div>

<script>
(function(){
    var track = document.getElementById('carousel-track-web');
    if (!track) return;
    var counter = document.getElementById('carousel-counter-web');
    var thumbs = document.querySelectorAll('#carousel-thumbnails-web .thumb-item-web');
    var total = {{ $totalFoto }};

    function updateState(idx) {
        if (idx < 0) idx = 0;
        if (idx >= total) idx = total - 1;
        if (counter) counter.textContent = '📷 ' + (idx + 1) + ' / ' + total;
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

    window.scrollCarouselWeb = function(dir) {
        var w = track.clientWidth;
        track.scrollBy({ left: dir * w, behavior: 'smooth' });
    };

    window.goCarouselWebTo = function(idx) {
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

    window.openGalleryModalWeb = function(src) {
        var modal = document.getElementById('gallery-lightbox-web');
        var img = document.getElementById('lightbox-img-web');
        if (!modal || !img) return;
        img.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.closeGalleryModalWeb = function() {
        var modal = document.getElementById('gallery-lightbox-web');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };
})();
</script>
@endsection
