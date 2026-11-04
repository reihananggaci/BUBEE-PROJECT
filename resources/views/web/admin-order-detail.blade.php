@extends('layouts.web-admin')
@section('title','Detail '.$order->kode)
@section('content')
<a href="/admin/order" class="text-xs font-bold text-blue-700 inline-flex items-center gap-1" style="text-decoration:none">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    <span>Kembali ke Kelola Order</span>
</a>
<h1 class="font-extrabold text-2xl mt-1 text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8M16 12H8M12 16H8"/></svg>
    </span>
    <span>{{ $order->kode }}</span>
</h1>
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2 bg-white rounded-3xl shadow-card p-5 text-sm space-y-2 border border-slate-100">
<div class="flex items-center gap-2 text-slate-700">
    <svg class="w-4 h-4 text-purple-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    <span>{{ $order->user->name ?? '' }} ({{ $order->user->email ?? '' }})</span>
</div>
<div class="flex items-start gap-2 text-slate-700">
    <svg class="w-4 h-4 text-purple-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
    <div>{{ $order->nama_penerima }} ({{ $order->hp_penerima }})<br><span class="text-xs text-slate-500">{{ $order->alamat }}</span></div>
</div>
<div class="flex items-center gap-2 text-slate-700">
    <svg class="w-4 h-4 text-purple-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><circle cx="7" cy="18" r="2"/><path d="M15 18H9"/><circle cx="17" cy="18" r="2"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14"/></svg>
    <div>{{ $order->kurir }} {{ $order->layanan }}@if($order->kota_tujuan) → {{ $order->kota_tujuan }}@endif (ongkir {{ $order->ongkir_sumber ?? 'dummy' }}@if($order->berat_tagih_gram>0), {{ number_format($order->berat_tagih_gram/1000,1) }} kg@endif) @if($order->resi)• Resi <b>{{ $order->resi }}</b>@endif</div>
</div>
<div class="hidden lg:block overflow-x-auto mt-2">
<table class="w-full text-xs lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Barang</th><th class="py-1">Varian</th><th class="py-1 text-center">Qty</th><th class="py-1 text-right">Subtotal</th></tr></thead>
<tbody>
@foreach($order->items as $it)<tr class="border-t border-slate-100"><td class="py-1">{{ $it->product->nama ?? '' }} <span class="text-slate-400">@ Rp {{ number_format($it->harga_satuan) }} ({{ $it->tipe_harga }})</span></td><td class="py-1">{{ $it->variant?->label() }}</td><td class="py-1 text-center">×{{ $it->qty }}</td><td class="py-1 text-right font-bold">Rp {{ number_format($it->subtotal) }}</td></tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">@foreach($order->items as $it)<div class="text-xs">- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }} @ Rp {{ number_format($it->harga_satuan) }} ({{ $it->tipe_harga }})</div>@endforeach</div>
<div class="font-extrabold text-slate-900 text-base mt-2">Total Rp {{ number_format($order->total_bayar) }}</div>
@if($order->diskon>0)<div class="text-xs text-emerald-600 flex items-center gap-1.5"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg> Voucher {{ $order->voucher->kode ?? '' }}: -Rp {{ number_format($order->diskon) }}</div>@endif
@if($order->status==='batal' && $order->alasan_batal)<div class="text-xs bg-red-50 border border-red-100 rounded-2xl p-2.5 mt-1 text-red-800 flex items-center gap-1.5"><svg class="w-4 h-4 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/></svg> Alasan batal: <b>{{ $order->alasan_batal }}</b></div>@endif
@if($order->bukti_bayar)<div class="mt-2"><a href="/storage/{{ $order->bukti_bayar }}" target="_blank" class="text-blue-700 font-bold text-xs inline-flex items-center gap-1.5 hover:underline"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg> Lihat bukti bayar</a></div>@endif
</div>
<div class="lg:sticky lg:top-20 self-start bg-white rounded-3xl shadow-card p-5 text-sm space-y-2 border border-slate-100">
<div class="font-extrabold text-slate-900">Update Status</div>
<form method="POST" action="/admin/order/{{ $order->id }}" class="space-y-2">@csrf @method('PUT')
<select name="status" id="status-sel" class="w-full border rounded-2xl px-3 py-2 text-xs font-bold text-slate-800">@foreach(['menunggu_bayar','diproses','dikirim','selesai','batal'] as $s)<option {{ $order->status===$s?'selected':'' }} value="{{ $s }}">{{ $s }}</option>@endforeach</select>
<input name="resi" id="resi-inp" value="{{ $order->resi }}" placeholder="Input no resi manual JNE/J&T/SPX *" class="w-full border rounded-2xl px-3 py-2 text-xs font-bold text-slate-800">
<div class="text-[11px] text-slate-500">Resi diisi <b>manual</b> dari aplikasi kurir (belum otomatis API). Wajib saat status <b>dikirim</b>.</div>
@if($order->resi)
<div class="flex gap-1.5 text-[11px] font-bold">
<a href="https://www.jne.co.id/id/tracking/trace/{{ $order->resi }}" target="_blank" class="flex-1 text-center border rounded-xl py-1.5 text-slate-700 hover:bg-slate-50" style="text-decoration:none">JNE Cek</a>
<a href="https://www.jet.co.id/track?waybill={{ $order->resi }}" target="_blank" class="flex-1 text-center border rounded-xl py-1.5 text-slate-700 hover:bg-slate-50" style="text-decoration:none">J&T Cek</a>
<a href="https://spx.co.id/" target="_blank" class="flex-1 text-center border rounded-xl py-1.5 text-slate-700 hover:bg-slate-50" style="text-decoration:none">SPX Cek</a>
</div>
@endif
<button class="w-full text-white font-bold py-2.5 rounded-2xl text-xs transition hover:opacity-95" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">Update + Kirim Notif WA</button>
</form>
<script>(function(){var s=document.getElementById('status-sel'),i=document.getElementById('resi-inp');function sync(){var need=s.value==='dikirim';i.required=need;i.placeholder=need?'No resi WAJIB saat dikirim *':'Input no resi manual JNE/J&T/SPX';}s.addEventListener('change',sync);sync();})();</script>
<a href="/admin/order/{{ $order->id }}/invoice" target="_blank" class="block text-center text-xs font-bold text-purple-700 border border-slate-100 rounded-2xl py-2 hover:bg-purple-50 transition" style="text-decoration:none">Cetak Invoice PDF</a>
<a href="/admin/order/{{ $order->id }}/surat-jalan" target="_blank" class="block text-center text-xs font-bold text-indigo-700 border border-slate-100 rounded-2xl py-2 hover:bg-indigo-50 transition" style="text-decoration:none">Surat Jalan Pengiriman</a>
<a href="/admin/order/{{ $order->id }}/label" target="_blank" class="block text-center text-xs font-bold text-slate-700 border border-slate-100 rounded-2xl py-2 hover:bg-slate-50 transition" style="text-decoration:none">Cetak Label Kirim</a>
<a href="/admin/order/{{ $order->id }}/struk" target="_blank" class="block text-center text-xs font-bold text-slate-700 border border-slate-100 rounded-2xl py-2 hover:bg-slate-50 transition" style="text-decoration:none">Cetak Struk</a>
<a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$order->hp_penerima ?? '') }}?text=Halo%20{{ urlencode($order->nama_penerima ?? '') }}%20order%20{{ $order->kode }}%20{{ $order->status }}" target="_blank" class="block text-center text-sm font-bold text-emerald-700 border border-emerald-200 bg-emerald-50 rounded-2xl py-2.5 hover:bg-emerald-100 transition" style="text-decoration:none">Chat Pembeli (WhatsApp)</a>
</div>
</div>
@endsection
