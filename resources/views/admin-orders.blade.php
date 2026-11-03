@extends('layouts.admin')
@section('title','Admin Order')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">Kelola Order</h1>
<div class="flex gap-2 mt-1 mb-2">
<a href="/admin/order/export" class="flex-1 text-center bg-emerald-50 text-emerald-700 font-bold py-2.5 rounded-2xl text-xs">📊 Export CSV</a>
<form method="POST" action="/admin/order/bersihkan-kedaluwarsa" class="flex-1" onsubmit="return confirm('Batalkan pesanan yang belum dibayar > 24 jam dan kembalikan stok?')">@csrf<button class="w-full bg-rose-50 text-rose-700 font-bold py-2.5 rounded-2xl text-xs">⏳ Auto-Batal 24 Jam</button></form>
</div>

{{-- Filter Status Mobile --}}
<div class="flex gap-1 overflow-x-auto pb-1 mb-2 text-xs">
@foreach(['semua' => 'Semua', 'menunggu_bayar' => 'Menunggu', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'batal' => 'Batal'] as $st => $lbl)
    <a href="/admin/order?status={{ $st }}&q={{ $q ?? '' }}" 
       class="px-2.5 py-1 rounded-full font-bold whitespace-nowrap {{ ($status ?? 'semua') === $st ? 'bg-purple-900 text-white' : 'bg-slate-100 text-slate-600' }}">
        {{ $lbl }}
    </a>
@endforeach
</div>

@if($orders->isEmpty())
    <div class="glass rounded-3xl p-8 text-center text-sm text-slate-400">Belum ada order.</div>
@else
    @foreach($orders as $o)
    <div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
        <a href="/admin/order/{{ $o->id }}" class="flex justify-between items-center">
            <b>{{ $o->kode }}</b>
            <span class="text-blue-700 font-bold text-xs">Detail →</span>
        </a>
        <div class="text-xs text-slate-500 mt-0.5">{{ $o->user->name ?? '' }} • {{ $o->kurir }} {{ $o->layanan }} • <span class="font-bold text-slate-900">Rp {{ number_format($o->total_bayar) }}</span></div>
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

    {{-- Navigasi Panah Samping (Pagination) --}}
    <div class="mt-3">{{ $orders->links() }}</div>
@endif
@endsection
