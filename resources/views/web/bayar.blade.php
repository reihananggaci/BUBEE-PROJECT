@extends('layouts.web')
@section('title','Bayar')
@section('content')
<a href="/pesanan" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali ke Pesanan</a>
<h1 class="font-extrabold text-2xl mt-1">Pembayaran</h1>
<div class="h-1.5 rounded-full bg-slate-100 overflow-hidden my-3"><div class="h-full bg-blue-700 rounded-full" style="width:90%"></div></div>
<div class="grid gap-4 lg:grid-cols-2 lg:items-start">
<div class="bg-white border border-slate-100 rounded-3xl shadow-card p-5 text-sm space-y-1.5">
<div class="flex justify-between"><span>Kode</span><b>{{ $order->kode }}</b></div>
<div class="flex justify-between"><span>Kurir</span><b>{{ $order->kurir }} {{ $order->layanan }}</b></div>
<div class="flex justify-between"><span>Ongkir</span><b>Rp {{ number_format($order->ongkir) }}</b></div>
@if($order->diskon>0)<div class="flex justify-between text-emerald-600"><span>Voucher {{ $order->voucher->kode ?? '' }}</span><b>-Rp {{ number_format($order->diskon) }}</b></div>@endif
<div class="text-xl flex justify-between border-t pt-2"><span>Total</span><b class="text-blue-900">Rp {{ number_format($order->total_bayar) }}</b></div>
<div class="rounded-3xl p-4 text-center text-white" style="background:linear-gradient(135deg,#050B2E,#1D4ED8)"><div class="text-[11px] tracking-widest text-cyan-300">{{ ($midtrans['live'] ?? false) ? 'QRIS / VA / E-WALLET' : 'QRIS DUMMY' }}</div><div class="text-xs opacity-70 mt-1">Kode: {{ $order->snap_token }}</div></div>
@foreach($order->items as $it)<div class="text-xs text-slate-500">- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }} = Rp {{ number_format($it->subtotal) }}</div>@endforeach
</div>
<div class="lg:sticky lg:top-20 self-start bg-white border border-slate-100 rounded-3xl shadow-card p-5">
@if($midtrans['live'] ?? false)
<script src="{{ $midtrans['snapJs'] }}" data-client-key="{{ $midtrans['clientKey'] }}"></script>
<button id="pay-btn" class="w-full text-white font-extrabold py-3.5 rounded-3xl" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">BAYAR SEKARANG</button>
<script>document.getElementById('pay-btn').onclick=function(){snap.pay('{{ $order->snap_token }}')};</script>
@else
<form method="POST" action="/bayar/{{ $order->id }}/simulasi">@csrf<button class="w-full text-white font-extrabold py-3.5 rounded-3xl" style="background:linear-gradient(90deg,#059669,#00E5FF)">SAYA SUDAH BAYAR</button></form>
@endif
<details class="border border-slate-100 rounded-3xl p-4 mt-3 text-sm bg-slate-50/50" open>
<summary class="font-bold cursor-pointer text-xs text-blue-900 inline-flex items-center gap-1.5 list-none">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    <span>Transfer Bank Manual & Upload Bukti</span>
</summary>
<div class="mt-2.5 p-3 rounded-2xl bg-white border border-slate-200/80 text-xs space-y-1.5 shadow-sm">
<div class="flex justify-between items-center text-slate-500"><span>Bank Tujuan:</span><b class="text-slate-900 font-extrabold text-sm">{{ $bank['nama'] ?? 'BCA' }}</b></div>
<div class="flex justify-between items-center text-slate-500"><span>No. Rekening:</span><span class="font-mono font-extrabold text-blue-900 text-sm select-all">{{ $bank['rekening'] ?? '1234567890' }}</span></div>
<div class="flex justify-between items-center text-slate-500"><span>Atas Nama:</span><b class="text-slate-800">{{ $bank['atas_nama'] ?? 'Toko Bubee' }}</b></div>
<div class="flex justify-between items-center text-slate-500 border-t border-slate-100 pt-1.5"><span>Jumlah Transfer:</span><b class="text-blue-900 font-extrabold text-sm">Rp {{ number_format($order->total_bayar) }}</b></div>
</div>
<form method="POST" action="/bayar/{{ $order->id }}/bukti" enctype="multipart/form-data" class="mt-3">@csrf
<label class="block text-[11px] font-bold text-slate-600 mb-1">Unggah Bukti Transfer / Resi ATM / M-Banking:</label>
<input type="file" name="bukti" accept="image/*" class="w-full text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-800" required>
<button class="w-full mt-2 bg-slate-900 text-white font-bold py-2.5 rounded-2xl text-sm shadow">KIRIM BUKTI TRANSFER</button>
</form>
</details>
<a href="/pesanan/{{ $order->id }}" class="block text-center mt-2 text-sm font-bold text-blue-700" style="text-decoration:none">Lihat Tracking →</a>
</div>
</div>
@endsection
