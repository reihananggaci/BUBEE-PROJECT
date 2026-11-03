@if ($paginator->hasPages())
<nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3 my-4 px-2 select-none">
    {{-- Info Data & Halaman --}}
    <div class="text-xs text-slate-500 font-medium order-2 sm:order-1 text-center sm:text-left">
        Menampilkan <span class="font-extrabold text-slate-700">{{ $paginator->firstItem() }}</span> – <span class="font-extrabold text-slate-700">{{ $paginator->lastItem() }}</span> dari <span class="font-extrabold text-slate-700">{{ $paginator->total() }}</span> data
        <span class="text-slate-400 mx-1">•</span>
        Halaman <span class="font-extrabold text-purple-900">{{ $paginator->currentPage() }}</span> dari {{ $paginator->lastPage() }}
    </div>

    {{-- Kontrol Navigasi Panah Samping & Nomor Halaman --}}
    <div class="inline-flex items-center gap-1.5 flex-wrap justify-center order-1 sm:order-2">
        {{-- Panah Kiri (Sebelumnya) --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed border border-transparent shadow-none" aria-disabled="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Sebelumnya</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-purple-900 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 hover:border-purple-300 transition shadow-sm active:scale-95" style="text-decoration:none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Sebelumnya</span>
            </a>
        @endif

        {{-- Nomor-Nomor Halaman --}}
        <div class="hidden sm:inline-flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- Ellipsis Separator --}}
                @if (is_string($element))
                    <span class="px-2 py-1 text-xs text-slate-400 font-bold" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Link Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="w-8 h-8 flex items-center justify-center text-xs font-extrabold text-white rounded-xl shadow-sm" style="background:linear-gradient(135deg,#0B2A6B,#1D4ED8)">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:text-purple-900 hover:border-purple-200 transition shadow-sm active:scale-95" style="text-decoration:none">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Indikator Halaman untuk Mobile --}}
        <div class="sm:hidden px-2.5 py-1 text-xs font-bold bg-slate-100 text-slate-700 rounded-xl">
            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </div>

        {{-- Panah Kanan (Berikutnya) --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-purple-900 bg-white border border-slate-200 rounded-xl hover:bg-purple-50 hover:border-purple-300 transition shadow-sm active:scale-95" style="text-decoration:none">
                <span>Berikutnya</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed border border-transparent shadow-none" aria-disabled="true">
                <span>Berikutnya</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </div>
</nav>
@endif
