@extends('layouts.app')
@section('title','Tracking '.$order->kode)
@section('back', '/pesanan')
@section('content')
<h1 class="font-extrabold">Tracking 📍</h1>
<div class="text-xs text-slate-500">{{ $order->kode }} • {{ $order->kurir }} {{ $order->layanan }}@if($order->kota_tujuan) → {{ $order->kota_tujuan }}@endif @if($order->resi)• Resi: <b>{{ $order->resi }}</b>@endif</div>
<div class="glass rounded-3xl shadow-card p-4 mt-2">
@php $labels=['menunggu_bayar'=>'💳 Bayar','diproses'=>'📦 Diproses','dikirim'=>'🚚 Dikirim','selesai'=>'⭐ Selesai']; @endphp
<div class="flex justify-between text-[10px] font-bold mb-2">@foreach($labels as $k=>$l)<span class="{{ $current>=($steps[$k]??99)?'text-blue-700':'text-slate-300' }}">{{ $l }}</span>@endforeach</div>
<div class="progress-line"><div style="width:{{ $order->status==='batal'?100:($current/4*100) }}%; {{ $order->status==='batal'?'background:#ef4444':'' }}"></div></div>
<div class="text-sm mt-2">Status: <b>{{ strtoupper($order->status) }}</b> • Total: <b>Rp {{ number_format($order->total_bayar) }}</b></div>
<div class="text-[11px] text-slate-400">produk Rp {{ number_format($order->total_produk) }} + ongkir Rp {{ number_format($order->ongkir) }}@if($order->diskon>0) − voucher Rp {{ number_format($order->diskon) }}@endif</div>
@if($order->berat_tagih_gram>0)<div class="text-[11px] text-slate-400">Ditagih {{ number_format($order->berat_tagih_gram/1000,1) }} kg ({{ $order->kurir }})</div>@endif
<div class="text-sm mt-1">{{ $order->nama_penerima }} ({{ $order->hp_penerima }})<br>{{ $order->alamat }}</div>
@foreach($order->items as $it)<div class="text-xs text-slate-500">- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }} = Rp {{ number_format($it->subtotal) }}</div>@endforeach
@if($order->bukti_bayar)<div class="text-xs mt-1">📎 Bukti: <a href="/storage/{{ $order->bukti_bayar }}" target="_blank" class="text-blue-700 font-bold">lihat gambar</a></div>@endif
</div>
@if($order->resi)
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm">
<b>📍 Lacak Paket ({{ $order->kurir }} • {{ $order->resi }})</b>
@if($lacak)
<div class="mt-1">Status kurir: <b>{{ $lacak['status'] }}</b></div>
@foreach($lacak['riwayat'] as $m)<div class="text-xs py-1 border-b border-blue-50">• {{ $m['manifest_description'] ?? '' }}<br><span class="text-slate-400">{{ $m['manifest_date'] ?? '' }} {{ $m['manifest_time'] ?? '' }} • {{ $m['city_name'] ?? '' }}</span></div>@endforeach
@else
<div class="text-xs text-slate-500 mt-1">Belum ada update otomatis kurir:</div>
<a href="{{ $lacakUrl }}" target="_blank" class="block text-center mt-1 bg-slate-900 text-white font-bold py-2.5 rounded-2xl text-xs">LACAK DI {{ $order->kurir }} →</a>
@endif
</div>
@endif
<div class="flex gap-2 mt-2">
@if(in_array($order->status,['menunggu_bayar','diproses']))
<details class="flex-1">
<summary class="block text-center w-full bg-red-500 text-white font-bold py-3 rounded-2xl text-sm cursor-pointer list-none">Batalkan</summary>
<form method="POST" action="/pesanan/{{ $order->id }}/batal" class="glass rounded-3xl shadow-card p-3 mt-2 text-sm space-y-2">@csrf
<label class="text-xs font-bold">Kenapa dibatalkan?
<select name="alasan" class="w-full border rounded-2xl px-3 py-2 mt-1 font-normal" required>
<option value="">— Pilih alasan —</option>
@foreach($alasanBatal as $a)<option value="{{ $a }}">{{ $a }}</option>@endforeach
</select></label>
<button class="w-full bg-red-600 text-white font-bold py-2.5 rounded-2xl text-sm" onclick="return confirm('Yakin batalkan order ini?')">Ya, Batalkan</button>
</form>
</details>
@endif
@if($order->status==='menunggu_bayar')<a href="/bayar/{{ $order->id }}" class="flex-1 text-center text-white font-bold py-3 rounded-2xl text-sm" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)">Bayar</a>@endif
@if($order->status==='dikirim')
<form method="POST" action="/pesanan/{{ $order->id }}/selesai" class="flex-1">@csrf<button class="w-full bg-emerald-500 text-white font-bold py-3 rounded-2xl text-sm">Selesai ⭐</button></form>
@endif
</div>
@if($order->status==='selesai')
<div class="glass rounded-3xl shadow-card p-3 mt-2 text-sm">
<b>⭐ Bagaimana barangnya?</b>
@foreach($order->items as $it)<a href="/produk/{{ $it->product_id }}#ulasan" class="block text-xs text-blue-700 font-bold py-1">→ Ulas {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }})</a>@endforeach
</div>
<div class="flex gap-2 mt-2">
<a href="/pesanan/{{ $order->id }}/retur" class="flex-1 text-center bg-amber-100 text-amber-800 font-bold py-2.5 rounded-2xl text-sm">Ajukan Retur</a>
<a href="/pesanan/{{ $order->id }}/invoice" class="flex-1 text-center bg-blue-50 text-blue-800 font-bold py-2.5 rounded-2xl text-sm">Invoice PDF</a>
</div>
@endif
@php $waAdmin = \App\Models\Setting::get('wa_admin','6281234567890'); @endphp
<a href="https://wa.me/{{ $waAdmin }}?text={{ urlencode('Halo Bubee, order '.$order->kode) }}" target="_blank" class="block text-center mt-2 text-sm font-bold text-emerald-700 glass rounded-2xl py-2.5">💬 Chat Admin</a>
@endsection
