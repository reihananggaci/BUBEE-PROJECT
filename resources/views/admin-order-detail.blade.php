@extends('layouts.admin')
@section('title','Detail '.$order->kode)
@section('back', '/admin/order')
@section('content')
<h1 class="font-extrabold text-sm">{{ $order->kode }}</h1>
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm space-y-1">
<div>👤 {{ $order->user->name ?? '' }} ({{ $order->user->email ?? '' }})</div>
<div>📍 {{ $order->nama_penerima }} ({{ $order->hp_penerima }})<br>{{ $order->alamat }}</div>
<div>🚚 {{ $order->kurir }} {{ $order->layanan }}@if($order->kota_tujuan) → {{ $order->kota_tujuan }}@endif (ongkir {{ $order->ongkir_sumber ?? 'dummy' }}@if($order->berat_tagih_gram>0), {{ number_format($order->berat_tagih_gram/1000,1) }} kg@endif) @if($order->resi)• Resi <b>{{ $order->resi }}</b>@endif</div>
@foreach($order->items as $it)<div class="text-xs">- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }} @ Rp {{ number_format($it->harga_satuan) }} ({{ $it->tipe_harga }})</div>@endforeach
<div class="font-extrabold">Total Rp {{ number_format($order->total_bayar) }}</div>
@if($order->diskon>0)<div class="text-xs text-emerald-600">🎟️ Voucher {{ $order->voucher->kode ?? '' }}: -Rp {{ number_format($order->diskon) }}</div>@endif
@if($order->status==='batal' && $order->alasan_batal)<div class="text-xs bg-red-50 border border-red-100 rounded-2xl p-2 mt-1">❌ Alasan batal: <b>{{ $order->alasan_batal }}</b></div>@endif
<a href="/admin/order/{{ $order->id }}/invoice" target="_blank" class="block text-center text-xs font-bold text-purple-700 glass rounded-2xl py-2">🧾 Cetak Invoice PDF</a>
<a href="/admin/order/{{ $order->id }}/label" target="_blank" class="block text-center text-xs font-bold text-slate-700 glass rounded-2xl py-2 mt-1">🏷️ Cetak Label Kirim</a>
<a href="/admin/order/{{ $order->id }}/struk" target="_blank" class="block text-center text-xs font-bold text-slate-700 glass rounded-2xl py-2 mt-1">🧾 Cetak Struk</a>
@if($order->bukti_bayar)<div>📎 <a href="/storage/{{ $order->bukti_bayar }}" target="_blank" class="text-blue-700 font-bold">Lihat bukti bayar</a></div>@endif
</div>
<form method="POST" action="/admin/order/{{ $order->id }}" class="glass rounded-3xl p-4 mt-2 text-sm space-y-2">@csrf @method('PUT')
<select name="status" id="status-sel" class="w-full border rounded-2xl px-3 py-2">@foreach(['menunggu_bayar','diproses','dikirim','selesai','batal'] as $s)<option {{ $order->status===$s?'selected':'' }} value="{{ $s }}">{{ $s }}</option>@endforeach</select>
<input name="resi" id="resi-inp" value="{{ $order->resi }}" placeholder="Input no resi manual JNE/J&T/SPX" class="w-full border rounded-2xl px-3 py-2">
<div class="text-[11px] text-slate-500">✍️ Resi manual dari aplikasi kurir. Wajib saat <b>dikirim</b>.</div>
<button class="w-full text-white font-bold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">Update + Kirim Notif WA</button>
</form>
<script>(function(){var s=document.getElementById('status-sel'),i=document.getElementById('resi-inp');function sync(){i.required=s.value==='dikirim';}s.addEventListener('change',sync);sync();})();</script>
<a href="/admin/order/{{ $order->id }}/surat-jalan" target="_blank" class="block text-center mt-1 text-xs font-bold text-indigo-700 glass rounded-2xl py-2">📄 Surat Jalan Pengiriman</a>
<a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$order->hp_penerima ?? '') }}?text=Halo%20{{ urlencode($order->nama_penerima ?? '') }}%20order%20{{ $order->kode }}%20{{ $order->status }}" target="_blank" class="block text-center mt-2 text-sm font-bold text-emerald-700 glass rounded-2xl py-2.5">💬 Chat Pembeli</a>
@endsection

