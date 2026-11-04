@extends('layouts.web-admin')
@section('title','Admin Order')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
    <div>
        <h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8M16 12H8M12 16H8"/></svg>
            </span>
            <span>Kelola Order</span>
        </h1>
        <div class="text-xs text-slate-500 mt-0.5">{{ $orders->total() }} order • ubah status + isi resi langsung dari tabel</div>
    </div>
    <div class="flex gap-2">
        <form method="POST" action="/admin/order/bersihkan-kedaluwarsa" onsubmit="return confirm('Batalkan pesanan yang belum dibayar > 24 jam dan kembalikan stok?')">
            @csrf
            <button class="bg-rose-50 text-rose-700 font-bold py-2.5 px-4 rounded-2xl text-xs hover:bg-rose-100 transition inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Auto-Batal 24 Jam</span>
            </button>
        </form>
        <a href="/admin/order/export" class="bg-emerald-50 text-emerald-700 font-bold py-2.5 px-5 rounded-2xl text-xs hover:bg-emerald-100 transition inline-flex items-center gap-1.5" style="text-decoration:none">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            <span>Export CSV</span>
        </a>
    </div>
</div>

{{-- Filter & Pencarian Order --}}
<div class="flex flex-col sm:flex-row gap-2 mb-3">
    <form method="GET" action="/admin/order" class="flex gap-1.5 flex-1 max-w-md">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </span>
            <input name="q" value="{{ $q ?? '' }}" placeholder="Cari kode / resi / nama pembeli…" class="w-full border rounded-2xl pl-9 pr-3 py-2 text-xs">
        </div>
        @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
        <button class="bg-purple-900 text-white font-bold px-3.5 rounded-2xl text-xs">Cari</button>
    </form>
    <div class="flex gap-1 overflow-x-auto pb-1 items-center">
        @foreach(['semua' => 'Semua', 'menunggu_bayar' => 'Menunggu', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'batal' => 'Batal'] as $st => $lbl)
            <a href="/admin/order?status={{ $st }}&q={{ $q ?? '' }}" 
               class="px-2.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap {{ ($status ?? 'semua') === $st ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}" 
               style="text-decoration:none">
                {{ $lbl }}
            </a>
        @endforeach
    </div>
</div>

@if($orders->isEmpty())
    <div class="bg-white rounded-3xl p-8 text-center text-sm mt-4 text-slate-400">Belum ada order yang cocok.</div>
@else
    <div class="hidden lg:block bg-white rounded-3xl shadow-card mt-3 overflow-x-auto">
        <table class="w-full text-sm lg:table">
            <thead>
                <tr class="text-left text-[11px] text-slate-400 border-b border-slate-100">
                    <th class="px-4 py-2">Kode</th>
                    <th class="px-4 py-2">Pembeli</th>
                    <th class="px-4 py-2">Kurir</th>
                    <th class="px-4 py-2 text-right">Total</th>
                    <th class="px-4 py-2">Status + Resi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr class="border-t border-slate-100 hover:bg-slate-50/50 transition">
                    <td class="px-4 py-2 font-bold whitespace-nowrap">
                        <a href="/admin/order/{{ $o->id }}" class="text-blue-700 hover:underline" style="text-decoration:none">{{ $o->kode }}</a>
                        <div class="text-[10px] text-slate-400 font-normal">{{ $o->created_at->format('d M H:i') }}</div>
                    </td>
                    <td class="px-4 py-2 text-slate-800">
                        <div class="font-bold">{{ $o->user->name ?? '' }}</div>
                        <div class="text-[10px] text-slate-400">{{ $o->items->count() }} item</div>
                    </td>
                    <td class="px-4 py-2 text-xs text-slate-600">{{ $o->kurir }} {{ $o->layanan }}</td>
                    <td class="px-4 py-2 text-right font-bold whitespace-nowrap text-slate-900">Rp {{ number_format($o->total_bayar) }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="/admin/order/{{ $o->id }}" class="flex gap-1">
                            @csrf @method('PUT')
                            <select name="status" class="border rounded-xl px-2 py-1 text-xs font-bold">
                                @foreach(['menunggu_bayar','diproses','dikirim','selesai','batal'] as $s)
                                    <option value="{{ $s }}" {{ $o->status===$s?'selected':'' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            <input name="resi" value="{{ $o->resi }}" placeholder="No resi" class="border rounded-xl px-2 py-1 text-xs w-28">
                            <button class="bg-blue-900 text-white text-xs font-bold px-3 rounded-xl hover:bg-blue-800 transition">OK</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="lg:hidden mt-3">
        @foreach($orders as $o)
        <div class="bg-white rounded-3xl shadow-card p-4 mb-2 text-sm border border-slate-100">
            <a href="/admin/order/{{ $o->id }}" class="flex justify-between items-center" style="text-decoration:none">
                <b class="text-slate-900">{{ $o->kode }}</b>
                <span class="text-blue-700 font-bold text-xs">Detail →</span>
            </a>
            <div class="text-xs text-slate-500 mt-1">{{ $o->user->name ?? '' }} • {{ $o->kurir }} {{ $o->layanan }} • <span class="font-bold text-slate-900">Rp {{ number_format($o->total_bayar) }}</span></div>
            <form method="POST" action="/admin/order/{{ $o->id }}" class="flex gap-1 mt-2">
                @csrf @method('PUT')
                <select name="status" class="border rounded-xl px-2 py-1.5 text-xs font-bold">
                    @foreach(['menunggu_bayar','diproses','dikirim','selesai','batal'] as $s)
                        <option value="{{ $s }}" {{ $o->status===$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <input name="resi" value="{{ $o->resi }}" placeholder="No resi" class="flex-1 border rounded-xl px-2 py-1.5 text-xs">
                <button class="bg-blue-900 text-white text-xs font-bold px-3 rounded-xl">OK</button>
            </form>
        </div>
        @endforeach
    </div>

    {{-- Navigasi Panah Samping (Pagination) --}}
    <div class="mt-4">{{ $orders->links() }}</div>
@endif
@endsection
