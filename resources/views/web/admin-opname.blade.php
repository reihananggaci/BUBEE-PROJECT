@extends('layouts.web-admin')
@section('title','Stok Opname')
@section('content')

{{-- ==================== HEADER HALAMAN ==================== --}}
<div class="flex justify-between items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="font-extrabold text-2xl text-slate-900">📦 Stok Opname (Audit Fisik Gudang)</h1>
        <div class="text-xs text-slate-500 mt-0.5">
            Hitung stok fisik barang di rak gudang, bandingkan dengan catatan sistem, dan sesuaikan selisih secara otomatis.
        </div>
    </div>
    <div>
        <a href="/admin/mutasi" class="bg-purple-50 hover:bg-purple-100 text-purple-800 font-bold px-3.5 py-2 rounded-2xl text-xs border border-purple-200 transition inline-flex items-center gap-1.5" style="text-decoration:none">
            <span>📋</span>
            <span>Lihat Log Mutasi Stok →</span>
        </a>
    </div>
</div>

{{-- ==================== SESI TERPANDU & RIWAYAT ==================== --}}
<div class="grid gap-4 lg:grid-cols-2 lg:items-start">
    {{-- KARTU KIRI: SESI OPNAME / MULAI SESI --}}
    <div>
        @if($sesiJalan)
        <a href="/admin/opname/{{ $sesiJalan->id }}" class="block rounded-3xl p-5 text-white shadow-card transition hover:opacity-95" style="background:linear-gradient(135deg,#4c1d95,#ec4899);text-decoration:none">
            <div class="flex items-center justify-between">
                <span class="font-black text-lg">▶️ Lanjutkan Sesi #{{ $sesiJalan->id }}</span>
                <span class="bg-white/20 text-white font-extrabold px-3 py-1 rounded-full text-xs uppercase tracking-wide">{{ $sesiJalan->cakupan }}</span>
            </div>
            <div class="text-xs opacity-90 mt-2">
                Kemajuan: <b>{{ $sesiJalan->terisi() }}</b> dari <b>{{ $sesiJalan->total() }}</b> varian telah dihitung
            </div>
            <div class="mt-3 w-full bg-black/20 rounded-full h-2 overflow-hidden">
                <div class="bg-white h-full rounded-full transition-all" style="width:{{ $sesiJalan->total() > 0 ? round($sesiJalan->terisi() / $sesiJalan->total() * 100) : 0 }}%"></div>
            </div>
        </a>
        @else
        <form method="POST" action="/admin/opname/mulai" class="bg-white rounded-3xl shadow-card p-5 text-sm space-y-3 border border-slate-100">
            @csrf
            <div>
                <b class="text-slate-900 text-sm">🆕 Mulai Sesi Opname Terpandu</b>
                <div class="text-[11px] text-slate-500">Hitung satu per satu dengan konfirmasi foto bila ada selisih besar</div>
            </div>
            <div>
                <label class="block text-[11px] font-extrabold text-slate-400 mb-1 uppercase">Pilih Cakupan Barang:</label>
                <select name="cakupan" class="w-full border border-slate-200 rounded-2xl px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-purple-600">
                    <option value="semua">Semua barang & kategori</option>
                    @foreach($kategoris as $kt)
                        <option value="{{ $kt }}">Khusus Kategori: {{ $kt }}</option>
                    @endforeach
                </select>
            </div>
            <button class="w-full text-white font-extrabold py-3 rounded-2xl text-xs transition shadow-sm hover:opacity-95" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">
                MULAI HITUNG TERPANDU 🚀
            </button>
        </form>
        @endif

        {{-- REKONSILIASI OTOMATIS --}}
        <form method="POST" action="/admin/rekonsiliasi" class="mt-3" onsubmit="return confirm('Selaraskan semua total stok produk induk dengan jumlah varian yang ada?')">
            @csrf
            <button class="w-full bg-purple-50 hover:bg-purple-100 text-purple-800 font-bold py-2.5 rounded-2xl text-xs border border-purple-200/60 transition flex items-center justify-center gap-1.5">
                <span>🔧</span>
                <span>Rekonsiliasi Otomatis (Sinkronkan Stok Induk)</span>
            </button>
        </form>
    </div>

    {{-- KARTU KANAN: RIWAYAT SESI OPNAME --}}
    <div class="bg-white rounded-3xl shadow-card p-5 text-sm border border-slate-100">
        <div class="flex items-center justify-between mb-2">
            <b class="text-slate-900 text-sm">🕘 Riwayat Sesi Opname Selesai</b>
            <span class="text-xs text-slate-400">{{ count($riwayat) }} Sesi Terakhir</span>
        </div>
        
        @if($riwayat->isEmpty())
            <div class="text-xs text-slate-400 py-6 text-center">Belum ada riwayat sesi opname yang disahkan.</div>
        @else
            <div class="hidden lg:block overflow-x-auto mt-2">
                <table class="w-full text-xs lg:table">
                    <thead>
                        <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                            <th class="py-2 px-1">Sesi</th>
                            <th class="py-2 px-2">Cakupan</th>
                            <th class="py-2 px-2">Oleh</th>
                            <th class="py-2 px-2">Status</th>
                            <th class="py-2 px-2 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($riwayat as $s)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-2 px-1 font-bold text-purple-900">#{{ $s->id }}</td>
                            <td class="py-2 px-2 capitalize font-medium text-slate-700">{{ $s->cakupan }}</td>
                            <td class="py-2 px-2 text-slate-600">{{ $s->user->name ?? 'sistem' }}</td>
                            <td class="py-2 px-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $s->status === 'sah' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $s->status === 'sah' ? '✓ Disahkan' : ucfirst($s->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-2 text-right text-slate-400 whitespace-nowrap">{{ $s->updated_at->format('d M, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="lg:hidden space-y-2">
                @foreach($riwayat as $s)
                <div class="text-xs py-2 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <span class="font-bold text-purple-900">Sesi #{{ $s->id }}</span> • {{ $s->cakupan }}
                        <div class="text-[10px] text-slate-400">{{ $s->user->name ?? 'sistem' }} • {{ $s->updated_at->format('d M H:i') }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">{{ $s->status }}</span>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ==================== MODE CEPAT (FORM MASSAL) DENGAN PENCARIAN & PAGINASI PANAH ==================== --}}
<div id="opname-form-anchor" class="mt-6"></div>
<details class="bg-white rounded-3xl shadow-card p-6 text-sm border border-slate-100" open>
    <summary class="font-extrabold cursor-pointer text-base text-slate-900 flex items-center justify-between flex-wrap gap-2 select-none">
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center text-sm font-black">⚡</span>
            <div>
                <span class="text-slate-900">Mode Cepat (Form Massal Stock Opname)</span>
                <span class="block text-xs font-normal text-slate-400">Sesuaikan fisik banyak barang sekaligus pada satu halaman tanpa antre sesi</span>
            </div>
        </div>
        <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-xl border border-purple-200">
            Klik untuk ciutkan / buka
        </span>
    </summary>

    <form method="POST" action="/admin/opname" onsubmit="return confirm('Simpan hasil opname? Selisih stok fisik vs sistem akan otomatis disesuaikan dan dicatat ke mutasi stok.')" class="mt-4">
        @csrf
        @php 
            $totalVarian = $products->sum(fn($p) => $p->variants->count()); 
            $uniqueKategoris = $products->pluck('kategori')->filter()->unique();
        @endphp

        {{-- BAR PROGRESS KEMAJUAN PENGISIAN --}}
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-4">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-2">
                <div>
                    <span id="opname-progres" class="text-xs font-extrabold text-purple-900">Terisi 0/{{ $totalVarian }} varian</span>
                    <span class="text-xs text-slate-500 font-normal"> (Isi kolom fisik hanya pada barang yang telah dihitung)</span>
                </div>
                <div class="text-xs font-extrabold text-purple-900 bg-white border border-purple-200 px-3 py-1 rounded-xl shadow-xs">
                    Progress: <span id="opname-filled-count">0/{{ $totalVarian }}</span>
                </div>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                <div id="opname-progres-bar" class="bg-purple-800 h-full rounded-full transition-all duration-300" style="width:0%"></div>
            </div>
        </div>

        {{-- TOOLBAR PENCARIAN & FILTER KATEGORI --}}
        <div class="bg-slate-50 rounded-2xl p-3 border border-slate-200/70 mb-4 space-y-3">
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                {{-- Input Pencarian Real-time --}}
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                        🔍
                    </span>
                    <input type="text" id="opname-search-input" placeholder="Ketik nama tumbler, kode varian, atau warna untuk mencari..." class="w-full pl-10 pr-10 py-2.5 text-xs font-bold text-slate-800 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition shadow-xs" autocomplete="off">
                    <button type="button" id="opname-clear-search" class="absolute inset-y-0 right-0 pr-3 items-center text-slate-400 hover:text-slate-700 hidden text-sm font-bold" title="Hapus pencarian">
                        ✕
                    </button>
                </div>

                {{-- Pilihan Jumlah Per Halaman --}}
                <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs text-slate-500 font-bold whitespace-nowrap">Tampilkan:</span>
                    <select id="opname-per-page-select" class="bg-white border border-slate-200 text-slate-800 font-bold text-xs rounded-xl px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-purple-600 shadow-xs cursor-pointer">
                        <option value="6">6 produk</option>
                        <option value="8" selected>8 produk</option>
                        <option value="12">12 produk</option>
                        <option value="20">20 produk</option>
                        <option value="999">Semua</option>
                    </select>
                </div>
            </div>

            {{-- Kategori Filter Pills --}}
            <div class="flex items-center gap-1.5 overflow-x-auto py-0.5 scrollbar-none text-xs">
                <span class="text-slate-400 font-bold shrink-0 mr-1">Kategori:</span>
                <button type="button" data-kategori="semua" class="opname-cat-pill px-3 py-1.5 rounded-xl text-xs font-extrabold transition bg-purple-900 text-white shadow-xs cursor-pointer">
                    Semua ({{ count($products) }})
                </button>
                @foreach($uniqueKategoris as $uk)
                @php $cntK = $products->where('kategori', $uk)->count(); @endphp
                <button type="button" data-kategori="{{ strtolower($uk) }}" class="opname-cat-pill px-3 py-1.5 rounded-xl text-xs font-bold transition bg-slate-100 text-slate-600 hover:bg-slate-200 cursor-pointer">
                    {{ $uk }} ({{ $cntK }})
                </button>
                @endforeach
            </div>
        </div>

        {{-- NAVIGASI PAGINASI PANAH ATAS --}}
        <div class="opname-pagination-container flex flex-col sm:flex-row items-center justify-between gap-3 mb-4 select-none">
            {{-- Info Data & Halaman --}}
            <div class="pagination-info-text text-xs text-slate-500 font-medium text-center sm:text-left">
                Memuat data produk...
            </div>

            {{-- Kontrol Panah Samping & Tombol Halaman --}}
            <div class="inline-flex items-center gap-1.5 flex-wrap justify-center">
                {{-- Panah Kiri (Sebelumnya) --}}
                <button type="button" class="btn-prev-page inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-purple-900 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 transition shadow-xs cursor-pointer active:scale-95" title="Halaman Sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    <span>Sebelumnya</span>
                </button>

                {{-- Tombol Nomor Halaman --}}
                <div class="page-numbers-container inline-flex items-center gap-1"></div>

                {{-- Panah Kanan (Berikutnya) --}}
                <button type="button" class="btn-next-page inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-purple-900 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 transition shadow-xs cursor-pointer active:scale-95" title="Halaman Berikutnya">
                    <span>Berikutnya</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        {{-- CONTAINER DAFTAR KARTU PRODUK OPNAME --}}
        <div id="opname-products-list" class="space-y-4">
            @foreach($products as $pIndex => $p)
            @php 
                $varNama = $p->variants->pluck('warna')->implode(' ') . ' ' . $p->variants->pluck('ukuran')->implode(' ');
                $totalStokP = $p->variants->sum('stok');
            @endphp
            <div class="opname-product-card bg-slate-50/70 hover:bg-slate-50 rounded-2xl p-4 border border-slate-200/80 transition"
                 data-nama="{{ strtolower($p->nama) }}"
                 data-kategori="{{ strtolower($p->kategori ?? 'tumbler') }}"
                 data-variants="{{ strtolower($varNama) }}">
                
                {{-- Header Produk --}}
                <div class="flex items-center justify-between flex-wrap gap-2 pb-3 mb-3 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        @if($p->foto)
                            <img src="/storage/{{ $p->foto }}" alt="{{ $p->nama }}" class="w-11 h-11 rounded-xl object-cover shrink-0 border border-slate-200 bg-white shadow-xs" loading="lazy">
                        @else
                            <span class="w-11 h-11 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center text-lg shrink-0 border border-purple-200">🍶</span>
                        @endif
                        <div>
                            <div class="font-extrabold text-sm text-slate-900 leading-snug">{{ $p->nama }}</div>
                            <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-2">
                                <span class="bg-white border border-slate-200 px-2 py-0.5 rounded-lg text-slate-600 font-bold">{{ $p->kategori ?? 'Tumbler' }}</span>
                                <span>{{ $p->variants->count() }} Pilihan Varian</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[11px] text-slate-400 font-bold uppercase">Stok Sistem</div>
                        <div class="text-sm font-black text-slate-800">{{ $totalStokP }} Botol</div>
                    </div>
                </div>

                {{-- Tabel / Rincian Varian Produk --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-xs lg:table">
                        <thead>
                            <tr class="text-left text-[11px] text-slate-400 border-b border-slate-200/60">
                                <th class="py-1.5 px-2">Varian (Warna & Ukuran)</th>
                                <th class="py-1.5 px-3 text-center">Tercatat Sistem</th>
                                <th class="py-1.5 px-3 text-center">Fisik di Rak Gudang</th>
                                <th class="py-1.5 px-3 text-right">Status Selisih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($p->variants as $v)
                            <tr class="hover:bg-white/80 transition">
                                <td class="py-2 px-2 font-bold text-slate-800">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                        <span>{{ $v->label() }}</span>
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-center font-extrabold text-slate-500">
                                    <span class="bg-slate-200/70 px-2 py-0.5 rounded-lg">{{ $v->stok }}</span>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button" class="w-7 h-7 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 font-black text-slate-700 flex items-center justify-center transition active:scale-95 shadow-xs" onclick="var inp=this.parentNode.querySelector('input'); if(inp.value==='') inp.value=inp.dataset.sistem; inp.stepDown(); inp.dispatchEvent(new Event('input'))" title="Kurangi 1">−</button>
                                        
                                        <input name="fisik[{{ $v->id }}]" data-sistem="{{ $v->stok }}" type="number" min="0" placeholder="Fisik" class="opname-fisik w-20 border border-slate-300 rounded-xl px-2 py-1.5 text-xs text-center font-black bg-white focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-purple-600 shadow-xs">
                                        
                                        <button type="button" class="w-7 h-7 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 font-black text-slate-700 flex items-center justify-center transition active:scale-95 shadow-xs" onclick="var inp=this.parentNode.querySelector('input'); if(inp.value==='') inp.value=inp.dataset.sistem; inp.stepUp(); inp.dispatchEvent(new Event('input'))" title="Tambah 1">+</button>
                                    </div>
                                </td>
                                <td class="py-2 px-3 text-right">
                                    <span class="opname-selisih font-bold text-xs"></span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        {{-- PESAN KETIKA PENCARIAN KOSONG --}}
        <div id="opname-empty-search" class="py-12 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200 hidden">
            <div class="text-3xl mb-1">🔍</div>
            <div class="font-bold text-sm text-slate-600">Tidak ada produk tumbler yang cocok</div>
            <div class="text-xs text-slate-400 mt-0.5">Silakan gunakan kata kunci nama lain atau klik tombol reset pencarian.</div>
        </div>

        {{-- NAVIGASI PAGINASI PANAH BAWAH --}}
        <div class="opname-pagination-container flex flex-col sm:flex-row items-center justify-between gap-3 mt-6 pt-4 border-t border-slate-200/80 select-none">
            {{-- Info Data & Halaman --}}
            <div class="pagination-info-text text-xs text-slate-500 font-medium text-center sm:text-left">
                Memuat data produk...
            </div>

            {{-- Kontrol Panah Samping & Tombol Halaman --}}
            <div class="inline-flex items-center gap-1.5 flex-wrap justify-center">
                {{-- Panah Kiri (Sebelumnya) --}}
                <button type="button" class="btn-prev-page inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-purple-900 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 transition shadow-xs cursor-pointer active:scale-95" title="Halaman Sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    <span>Sebelumnya</span>
                </button>

                {{-- Tombol Nomor Halaman --}}
                <div class="page-numbers-container inline-flex items-center gap-1"></div>

                {{-- Panah Kanan (Berikutnya) --}}
                <button type="button" class="btn-next-page inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-purple-900 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 transition shadow-xs cursor-pointer active:scale-95" title="Halaman Berikutnya">
                    <span>Berikutnya</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        {{-- TOMBOL SUBMIT MASSAL --}}
        <div class="mt-6 pt-4 border-t border-slate-200">
            <button type="submit" class="w-full text-white font-black py-3.5 px-6 rounded-2xl text-sm transition shadow-md hover:shadow-lg active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">
                <span>💾</span>
                <span>SIMPAN HASIL OPNAME (SESUAIKAN STOK)</span>
            </button>
            <div class="text-[11px] text-center text-slate-400 mt-2">
                *Hanya varian dengan input fisik yang diisi yang akan disesuaikan nilainya. Varian yang dikosongkan tidak akan diubah.
            </div>
        </div>
    </form>
</details>

{{-- ==================== SCRIPT INTERAKTIF: PENCARIAN, FILTER, PAGINASI PANAH, DAN HITUNG SELISIH ==================== --}}
<script>
(function() {
    let currentPage = 1;
    let perPage = 8;
    let searchQuery = '';
    let selectedKategori = 'semua';
    
    const allProducts = Array.from(document.querySelectorAll('.opname-product-card'));
    const totalVarian = {{ $totalVarian }};
    const searchInput = document.getElementById('opname-search-input');
    const clearSearchBtn = document.getElementById('opname-clear-search');
    const paginationContainers = document.querySelectorAll('.opname-pagination-container');
    const inputs = document.querySelectorAll('.opname-fisik');
    const progText = document.getElementById('opname-progres');
    const progBar = document.getElementById('opname-progres-bar');
    const filledCountBadge = document.getElementById('opname-filled-count');

    function getFilteredProducts() {
        return allProducts.filter(card => {
            const matchKategori = (selectedKategori === 'semua') || (card.dataset.kategori === selectedKategori);
            if (!matchKategori) return false;
            
            if (!searchQuery) return true;
            const q = searchQuery.toLowerCase().trim();
            const nama = card.dataset.nama || '';
            const variants = card.dataset.variants || '';
            return nama.includes(q) || variants.includes(q);
        });
    }

    function renderPagination() {
        const filtered = getFilteredProducts();
        const totalItems = filtered.length;
        const totalPages = Math.max(1, Math.ceil(totalItems / perPage));
        
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * perPage;
        const endIndex = Math.min(startIndex + perPage, totalItems);

        // Tampilkan hanya kartu produk yang masuk halaman aktif
        allProducts.forEach(card => card.style.display = 'none');
        for (let i = startIndex; i < endIndex; i++) {
            if (filtered[i]) {
                filtered[i].style.display = 'block';
            }
        }

        // Tampilan bila pencarian kosong
        const emptyState = document.getElementById('opname-empty-search');
        if (emptyState) {
            emptyState.style.display = (totalItems === 0) ? 'block' : 'none';
        }

        // Teks info data
        const infoHtml = totalItems > 0 
            ? `Menampilkan <span class="font-extrabold text-slate-800">${startIndex + 1}</span>–<span class="font-extrabold text-slate-800">${endIndex}</span> dari <span class="font-extrabold text-slate-800">${totalItems}</span> produk (Halaman <span class="font-extrabold text-purple-900">${currentPage}</span> dari ${totalPages})`
            : `Tidak ada produk yang cocok dengan pencarian.`;

        // Update seluruh bar paginasi (atas & bawah)
        paginationContainers.forEach(container => {
            const prevBtn = container.querySelector('.btn-prev-page');
            const nextBtn = container.querySelector('.btn-next-page');
            const pageNumbersContainer = container.querySelector('.page-numbers-container');
            const infoElem = container.querySelector('.pagination-info-text');

            if (infoElem) infoElem.innerHTML = infoHtml;

            if (prevBtn) {
                prevBtn.disabled = (currentPage <= 1);
                prevBtn.className = (currentPage <= 1)
                    ? 'btn-prev-page inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-300 bg-slate-100 rounded-xl cursor-not-allowed border border-transparent'
                    : 'btn-prev-page inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-purple-900 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 transition shadow-xs cursor-pointer active:scale-95';
            }

            if (nextBtn) {
                nextBtn.disabled = (currentPage >= totalPages);
                nextBtn.className = (currentPage >= totalPages)
                    ? 'btn-next-page inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-300 bg-slate-100 rounded-xl cursor-not-allowed border border-transparent'
                    : 'btn-next-page inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-purple-900 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 transition shadow-xs cursor-pointer active:scale-95';
            }

            if (pageNumbersContainer) {
                pageNumbersContainer.innerHTML = '';
                
                function addPageBtn(p) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = p;
                    if (p === currentPage) {
                        btn.className = 'w-8 h-8 flex items-center justify-center text-xs font-extrabold text-white rounded-xl shadow-xs bg-purple-900 cursor-default';
                    } else {
                        btn.className = 'w-8 h-8 flex items-center justify-center text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition shadow-xs cursor-pointer active:scale-95';
                        btn.addEventListener('click', () => {
                            currentPage = p;
                            renderPagination();
                            scrollToTopForm();
                        });
                    }
                    pageNumbersContainer.appendChild(btn);
                }

                function addDots() {
                    const span = document.createElement('span');
                    span.className = 'px-1.5 text-xs text-slate-400 font-bold';
                    span.textContent = '…';
                    pageNumbersContainer.appendChild(span);
                }

                if (totalPages <= 7) {
                    for (let p = 1; p <= totalPages; p++) addPageBtn(p);
                } else {
                    addPageBtn(1);
                    if (currentPage > 3) addDots();
                    
                    const startP = Math.max(2, currentPage - 1);
                    const endP = Math.min(totalPages - 1, currentPage + 1);
                    for (let p = startP; p <= endP; p++) addPageBtn(p);

                    if (currentPage < totalPages - 2) addDots();
                    addPageBtn(totalPages);
                }
            }
        });
    }

    function scrollToTopForm() {
        const formTop = document.getElementById('opname-form-anchor');
        if (formTop) {
            formTop.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Tombol Panah Paginasi
    document.querySelectorAll('.btn-prev-page').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderPagination();
                scrollToTopForm();
            }
        });
    });

    document.querySelectorAll('.btn-next-page').forEach(btn => {
        btn.addEventListener('click', () => {
            const filtered = getFilteredProducts();
            const totalPages = Math.max(1, Math.ceil(filtered.length / perPage));
            if (currentPage < totalPages) {
                currentPage++;
                renderPagination();
                scrollToTopForm();
            }
        });
    });

    // Pencarian Real-time
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchQuery = this.value;
            currentPage = 1;
            if (clearSearchBtn) {
                clearSearchBtn.style.display = searchQuery ? 'inline-flex' : 'none';
            }
            renderPagination();
        });
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                searchQuery = '';
                clearSearchBtn.style.display = 'none';
                currentPage = 1;
                renderPagination();
                searchInput.focus();
            }
        });
    }

    // Kategori Filter Pills
    document.querySelectorAll('.opname-cat-pill').forEach(pill => {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.opname-cat-pill').forEach(p => {
                p.className = 'opname-cat-pill px-3 py-1.5 rounded-xl text-xs font-bold transition bg-slate-100 text-slate-600 hover:bg-slate-200 cursor-pointer';
            });
            this.className = 'opname-cat-pill px-3 py-1.5 rounded-xl text-xs font-extrabold transition bg-purple-900 text-white shadow-xs cursor-pointer';
            selectedKategori = this.dataset.kategori;
            currentPage = 1;
            renderPagination();
        });
    });

    // Pilihan Jumlah Per Halaman
    const perPageSelect = document.getElementById('opname-per-page-select');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            perPage = parseInt(this.value, 10);
            currentPage = 1;
            renderPagination();
        });
    }

    // Perhitungan Selisih dan Progress Terisi
    function paint() {
        let isi = 0;
        inputs.forEach(function(i) {
            const s = parseInt(i.dataset.sistem, 10);
            const v = i.value === '' ? null : parseInt(i.value, 10);
            const row = i.closest('tr') || i.closest('div');
            const tag = row ? row.querySelector('.opname-selisih') : null;
            
            if (v === null || isNaN(v)) {
                if (tag) tag.innerHTML = '';
                return;
            }
            isi++;
            const d = v - s;
            if (tag) {
                if (d === 0) {
                    tag.innerHTML = '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">✓ Sesuai</span>';
                } else if (d > 0) {
                    tag.innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">+${d} (Lebih)</span>`;
                } else {
                    tag.innerHTML = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-extrabold bg-red-50 text-red-700 border border-red-200">${d} (Kurang)</span>`;
                }
            }
        });

        if (progText) {
            progText.textContent = 'Terisi ' + isi + '/' + totalVarian + ' varian';
        }
        if (filledCountBadge) {
            filledCountBadge.textContent = isi + '/' + totalVarian;
        }
        if (progBar) {
            const pct = totalVarian > 0 ? Math.round(isi / totalVarian * 100) : 0;
            progBar.style.width = pct + '%';
        }
    }

    inputs.forEach(function(i) {
        i.addEventListener('input', paint);
    });

    paint();
    renderPagination();
})();
</script>

@endsection
