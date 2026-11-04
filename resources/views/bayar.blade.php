@extends('layouts.app')
@section('title','Bayar')
@section('back', '/pesanan')
@section('content')
<h1 class="font-extrabold text-lg">Pembayaran</h1>
<div class="progress-line my-2"><div style="width:90%"></div></div>
<div class="glass rounded-[2rem] shadow-card p-5 text-sm space-y-1.5">
<div class="flex justify-between"><span>Kode</span><b>{{ $order->kode }}</b></div>
<div class="flex justify-between"><span>Kurir</span><b>{{ $order->kurir }} {{ $order->layanan }}</b></div>
<div class="flex justify-between"><span>Ongkir</span><b>Rp {{ number_format($order->ongkir) }}</b></div>
@if($order->diskon>0)<div class="flex justify-between text-emerald-600"><span>Voucher {{ $order->voucher->kode ?? '' }}</span><b>-Rp {{ number_format($order->diskon) }}</b></div>@endif
<div class="text-xl flex justify-between border-t pt-2"><span>Total</span><b class="text-blue-900">Rp {{ number_format($order->total_bayar) }}</b></div>
<div class="rounded-3xl p-4 text-center text-white" style="background:linear-gradient(135deg,#050B2E,#1D4ED8)"><div class="text-[11px] tracking-widest text-cyan-300">{{ ($midtrans['live'] ?? false) ? 'QRIS / VA / E-WALLET' : 'QRIS DUMMY' }}</div><div class="text-xs opacity-70 mt-1">Kode: {{ $order->snap_token }}</div></div>
</div>
@if($midtrans['live'] ?? false)
<script src="{{ $midtrans['snapJs'] }}" data-client-key="{{ $midtrans['clientKey'] }}"></script>
<button id="pay-btn" class="w-full text-white font-extrabold py-3.5 rounded-3xl mt-2" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">BAYAR SEKARANG</button>
<script>document.getElementById('pay-btn').onclick=function(){snap.pay('{{ $order->snap_token }}')};</script>
@else
<form method="POST" action="/bayar/{{ $order->id }}/simulasi" class="mt-2">@csrf<button class="w-full text-white font-extrabold py-3.5 rounded-3xl" style="background:linear-gradient(90deg,#059669,#00E5FF)">SAYA SUDAH BAYAR</button></form>
@endif
<details class="glass rounded-3xl p-3.5 mt-2 text-sm shadow-card" open>
<summary class="font-bold cursor-pointer text-xs text-blue-900 inline-flex items-center gap-1.5">
    <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2l10 5H2l10-5z"/></svg>
    <span>Transfer Bank Manual & Upload Bukti</span>
</summary>
<div class="mt-2.5 p-3 rounded-2xl bg-blue-50/60 border border-blue-100 text-xs space-y-1.5">
<div class="flex justify-between items-center text-slate-500"><span>Bank Tujuan:</span><b class="text-slate-900 font-extrabold text-sm">{{ $bank['nama'] ?? 'BCA' }}</b></div>
<div class="flex justify-between items-center text-slate-500"><span>No. Rekening:</span><span class="font-mono font-extrabold text-blue-900 text-sm select-all">{{ $bank['rekening'] ?? '1234567890' }}</span></div>
<div class="flex justify-between items-center text-slate-500"><span>Atas Nama:</span><b class="text-slate-800">{{ $bank['atas_nama'] ?? 'Toko Bubee' }}</b></div>
<div class="flex justify-between items-center text-slate-500 border-t border-blue-100/80 pt-1.5"><span>Jumlah Pas:</span><b class="text-blue-900 font-extrabold text-sm">Rp {{ number_format($order->total_bayar) }}</b></div>
</div>
<form method="POST" action="/bayar/{{ $order->id }}/bukti" enctype="multipart/form-data" class="mt-3">@csrf
<label class="block text-[11px] font-bold text-slate-600 mb-1">Unggah Struk / Tangkapan Layar Bukti Transfer:</label>
<input type="file" name="bukti" accept="image/*" class="w-full text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-800" required>
<button class="w-full mt-2 bg-slate-900 text-white font-bold py-2.5 rounded-2xl text-sm shadow">KIRIM BUKTI TRANSFER</button>
</form>
</details>
<a href="/pesanan/{{ $order->id }}" class="block text-center mt-2 text-sm font-bold text-blue-700">Lihat Tracking →</a>
@endsection
