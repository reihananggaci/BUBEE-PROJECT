@extends('layouts.app')
@section('title','Tracking '.$order->kode)
@section('back', '/pesanan')
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Tracking Pesanan</h1>
</div>
<div class="text-xs text-slate-500">{{ $order->kode }} • {{ $order->kurir }} {{ $order->layanan }}@if($order->kota_tujuan) → {{ $order->kota_tujuan }}@endif @if($order->resi)• Resi: <b>{{ $order->resi }}</b>@endif</div>
<div class="glass rounded-3xl shadow-card p-4 mt-2">
@php $labels=['menunggu_bayar'=>'Bayar','diproses'=>'Diproses','dikirim'=>'Dikirim','selesai'=>'Selesai']; @endphp
<div class="flex justify-between text-[10px] font-bold mb-2">@foreach($labels as $k=>$l)<span class="{{ $current>=($steps[$k]??99)?'text-blue-700':'text-slate-300' }}">{{ $l }}</span>@endforeach</div>
<div class="progress-line"><div style="width:{{ $order->status==='batal'?100:($current/4*100) }}%; {{ $order->status==='batal'?'background:#ef4444':'' }}"></div></div>
<div class="text-sm mt-2">Status: <b>{{ strtoupper($order->status) }}</b> • Total: <b>Rp {{ number_format($order->total_bayar) }}</b></div>
<div class="text-[11px] text-slate-400">produk Rp {{ number_format($order->total_produk) }} + ongkir Rp {{ number_format($order->ongkir) }}@if($order->diskon>0) − voucher Rp {{ number_format($order->diskon) }}@endif</div>
@if($order->berat_tagih_gram>0)<div class="text-[11px] text-slate-400">Ditagih {{ number_format($order->berat_tagih_gram/1000,1) }} kg ({{ $order->kurir }})</div>@endif
<div class="text-sm mt-1">{{ $order->nama_penerima }} ({{ $order->hp_penerima }})<br>{{ $order->alamat }}</div>
@foreach($order->items as $it)<div class="text-xs text-slate-500">- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }} = Rp {{ number_format($it->subtotal) }}</div>@endforeach
@if($order->bukti_bayar)<div class="text-xs mt-1 inline-flex items-center gap-1"><svg class="w-3.5 h-3.5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg><span>Bukti: <a href="/storage/{{ $order->bukti_bayar }}" target="_blank" class="text-blue-700 font-bold">lihat gambar</a></span></div>@endif
</div>
@if($order->resi)
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm">
<div class="flex items-center gap-1.5 font-bold mb-1">
    <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    <span>Lacak Paket ({{ $order->kurir }} • {{ $order->resi }})</span>
</div>
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
<form method="POST" action="/pesanan/{{ $order->id }}/selesai" class="flex-1">@csrf<button class="w-full bg-emerald-500 text-white font-bold py-3 rounded-2xl text-sm flex items-center justify-center gap-1.5"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg><span>Konfirmasi Selesai</span></button></form>
@endif
</div>
@if($order->status==='selesai')
<div class="glass rounded-3xl shadow-card p-3 mt-2 text-sm">
<div class="flex items-center gap-1.5 font-bold mb-1 text-slate-800">
    <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    <span>Bagaimana barangnya?</span>
</div>
@foreach($order->items as $it)<a href="/produk/{{ $it->product_id }}#ulasan" class="block text-xs text-blue-700 font-bold py-1">→ Ulas {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }})</a>@endforeach
</div>
<div class="flex gap-2 mt-2">
<a href="/pesanan/{{ $order->id }}/retur" class="flex-1 text-center bg-amber-100 text-amber-800 font-bold py-2.5 rounded-2xl text-sm">Ajukan Retur</a>
<a href="/pesanan/{{ $order->id }}/invoice" class="flex-1 text-center bg-blue-50 text-blue-800 font-bold py-2.5 rounded-2xl text-sm">Invoice PDF</a>
</div>
@endif
@php $waAdmin = \App\Models\Setting::get('wa_admin','6281234567890'); @endphp
<a href="https://wa.me/{{ $waAdmin }}?text={{ urlencode('Halo Bubee, order '.$order->kode) }}" target="_blank" class="flex items-center justify-center gap-2 text-center mt-2 text-sm font-bold text-emerald-700 glass rounded-2xl py-2.5">
    <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Chat Admin WhatsApp</span>
</a>
@endsection
