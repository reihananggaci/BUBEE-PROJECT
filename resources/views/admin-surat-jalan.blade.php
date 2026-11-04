<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Surat Jalan {{ $order->kode }}</title>
<style>
body{font-family:sans-serif;margin:0;padding:16px;color:#111}
.sj{max-width:640px;margin:0 auto;border:2px solid #111;border-radius:12px;padding:18px}
h1{margin:0;font-size:22px;letter-spacing:2px}
table{width:100%;border-collapse:collapse;font-size:13px;margin-top:10px}
th,td{border:1px solid #999;padding:6px 8px;text-align:left}
th{background:#f1f5f9}
.right{text-align:right}
.ttd{display:flex;gap:12px;margin-top:18px;font-size:12px}
.ttd div{flex:1;text-align:center;border-top:1px solid #111;padding-top:40px;margin-top:50px}
.small{font-size:11px;color:#555}
@media print{.noprint{display:none}body{padding:0}.sj{border-radius:0}}
</style></head>
<body>
<div class="sj">
<h1>{{ strtoupper($toko['nama']) }} — SURAT JALAN</h1>
<div class="small">No: {{ $order->kode }} • Tgl: {{ $order->created_at->format('d M Y H:i') }} • Kurir: {{ $order->kurir }} {{ $order->layanan }}@if($order->resi) • Resi: <b>{{ $order->resi }}</b>@endif</div>
<table>
<tr><th style="width:50%">PENGIRIM</th><th>PENERIMA</th></tr>
<tr><td><b>{{ $toko['nama'] }}</b>@if($toko['wa'])<br>WA: {{ $toko['wa'] }}@endif</td><td><b>{{ $order->nama_penerima }}</b> ({{ $order->hp_penerima }})<br>{{ $order->alamat }}@if($order->kota_tujuan)<br>{{ $order->kota_tujuan }}@endif</td></tr>
</table>
<table>
<tr><th>No</th><th>Barang</th><th>Varian</th><th class="right">Qty</th></tr>
@foreach($order->items as $i=>$it)<tr><td>{{ $i+1 }}</td><td>{{ $it->product->nama ?? '' }}</td><td>{{ $it->variant?->label() ?? '-' }}</td><td class="right">{{ $it->qty }}</td></tr>@endforeach
<tr><td colspan="3" class="right"><b>Total pcs</b></td><td class="right"><b>{{ $order->items->sum('qty') }}</b></td></tr>
</table>
<div class="small" style="margin-top:8px">Catatan: cek fisik paket saat terima. Komplain/retur via menu Retur di aplikasi maks. sertakan foto.</div>
<div class="ttd"><div>Pengirim<br>{{ $toko['nama'] }}</div><div>Kurir<br>&nbsp;</div><div>Penerima<br>{{ $order->nama_penerima }}</div></div>
</div>
<div class="noprint" style="text-align:center;margin-top:12px"><button onclick="window.print()" style="padding:12px 32px;font-weight:800;border-radius:12px;border:none;background:#111;color:#fff;cursor:pointer">CETAK SURAT JALAN</button></div>
</body></html>
