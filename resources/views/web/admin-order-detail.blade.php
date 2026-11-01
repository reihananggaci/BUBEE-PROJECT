@extends('layouts.web-admin')
@section('title','Detail '.$order->kode)
@section('content')
<a href="/admin/order" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali ke Kelola Order</a>
<h1 class="font-extrabold text-2xl mt-1">{{ $order->kode }}</h1>
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2 bg-white rounded-3xl shadow-card p-5 text-sm space-y-1">
<div>👤 {{ $order->user->name ?? '' }} ({{ $order->user->email ?? '' }})</div>
<div>📍 {{ $order->nama_penerima }} ({{ $order->hp_penerima }})<br>{{ $order->alamat }}</div>
<div>🚚 {{ $order->kurir }} {{ $order->layanan }}@if($order->kota_tujuan) → {{ $order->kota_tujuan }}@endif (ongkir {{ $order->ongkir_sumber ?? 'dummy' }}@if($order->berat_tagih_gram>0), {{ number_format($order->berat_tagih_gram/1000,1) }} kg@endif) @if($order->resi)• Resi <b>{{ $order->resi }}</b>@endif</div>
<div class="hidden lg:block overflow-x-auto mt-2">
<table class="w-full text-xs lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Barang</th><th class="py-1">Varian</th><th class="py-1 text-center">Qty</th><th class="py-1 text-right">Subtotal</th></tr></thead>
<tbody>
@foreach($order->items as $it)<tr class="border-t border-slate-100"><td class="py-1">{{ $it->product->nama ?? '' }} <span class="text-slate-400">@ Rp {{ number_format($it->harga_satuan) }} ({{ $it->tipe_harga }})</span></td><td class="py-1">{{ $it->variant?->label() }}</td><td class="py-1 text-center">×{{ $it->qty }}</td><td class="py-1 text-right font-bold">Rp {{ number_format($it->subtotal) }}</td></tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">@foreach($order->items as $it)<div class="text-xs">- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }} @ Rp {{ number_format($it->harga_satuan) }} ({{ $it->tipe_harga }})</div>@endforeach</div>
<div class="font-extrabold">Total Rp {{ number_format($order->total_bayar) }}</div>
@if($order->diskon>0)<div class="text-xs text-emerald-600">🎟️ Voucher {{ $order->voucher->kode ?? '' }}: -Rp {{ number_format($order->diskon) }}</div>@endif
@if($order->status==='batal' && $order->alasan_batal)<div class="text-xs bg-red-50 border border-red-100 rounded-2xl p-2 mt-1">❌ Alasan batal: <b>{{ $order->alasan_batal }}</b></div>@endif
@if($order->bukti_bayar)<div>📎 <a href="/storage/{{ $order->bukti_bayar }}" target="_blank" class="text-blue-700 font-bold">Lihat bukti bayar</a></div>@endif
</div>
<div class="lg:sticky lg:top-20 self-start bg-white rounded-3xl shadow-card p-5 text-sm space-y-2">
<div class="font-extrabold">Update Status</div>
<form method="POST" action="/admin/order/{{ $order->id }}" class="space-y-2">@csrf @method('PUT')
<select name="status" id="status-sel" class="w-full border rounded-2xl px-3 py-2">@foreach(['menunggu_bayar','diproses','dikirim','selesai','batal'] as $s)<option {{ $order->status===$s?'selected':'' }} value="{{ $s }}">{{ $s }}</option>@endforeach</select>
<input name="resi" id="resi-inp" value="{{ $order->resi }}" placeholder="Input no resi manual JNE/J&T/SPX *" class="w-full border rounded-2xl px-3 py-2">
<div class="text-[11px] text-slate-500">✍️ Resi diisi <b>manual</b> dari aplikasi kurir (belum otomatis API). Wajib saat status <b>dikirim</b>.</div>
@if($order->resi)
<div class="flex gap-1.5 text-[11px] font-bold">
<a href="https://www.jne.co.id/id/tracking/trace/{{ $order->resi }}" target="_blank" class="flex-1 text-center border rounded-xl py-1.5 text-slate-700" style="text-decoration:none">JNE 🔍</a>
<a href="https://www.jet.co.id/track?waybill={{ $order->resi }}" target="_blank" class="flex-1 text-center border rounded-xl py-1.5 text-slate-700" style="text-decoration:none">J&T 🔍</a>
<a href="https://spx.co.id/" target="_blank" class="flex-1 text-center border rounded-xl py-1.5 text-slate-700" style="text-decoration:none">SPX 🔍</a>
</div>
@endif
<button class="w-full text-white font-bold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">Update + Kirim Notif WA</button>
</form>
<script>(function(){var s=document.getElementById('status-sel'),i=document.getElementById('resi-inp');function sync(){var need=s.value==='dikirim';i.required=need;i.placeholder=need?'No resi WAJIB saat dikirim *':'Input no resi manual JNE/J&T/SPX';}s.addEventListener('change',sync);sync();})();</script>
<a href="/admin/order/{{ $order->id }}/invoice" target="_blank" class="block text-center text-xs font-bold text-purple-700 border border-slate-100 rounded-2xl py-2" style="text-decoration:none">🧾 Cetak Invoice PDF</a>
<a href="/admin/order/{{ $order->id }}/surat-jalan" target="_blank" class="block text-center text-xs font-bold text-indigo-700 border border-slate-100 rounded-2xl py-2" style="text-decoration:none">📄 Surat Jalan Pengiriman</a>
<a href="/admin/order/{{ $order->id }}/label" target="_blank" class="block text-center text-xs font-bold text-slate-700 border border-slate-100 rounded-2xl py-2" style="text-decoration:none">🏷️ Cetak Label Kirim</a>
<a href="/admin/order/{{ $order->id }}/struk" target="_blank" class="block text-center text-xs font-bold text-slate-700 border border-slate-100 rounded-2xl py-2" style="text-decoration:none">🧾 Cetak Struk</a>
<a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$order->hp_penerima ?? '') }}?text=Halo%20{{ urlencode($order->nama_penerima ?? '') }}%20order%20{{ $order->kode }}%20{{ $order->status }}" target="_blank" class="block text-center text-sm font-bold text-emerald-700 border border-slate-100 rounded-2xl py-2.5" style="text-decoration:none">💬 Chat Pembeli</a>
</div>
</div>
@endsection
