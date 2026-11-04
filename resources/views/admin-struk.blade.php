<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Struk {{ $order->kode }}</title>
<style>
body{font-family:'Courier New',monospace;margin:0;padding:8px;color:#111;font-size:12px}
.struk{max-width:280px;margin:0 auto}
.center{text-align:center}
.right{text-align:right}
hr{border:none;border-top:1px dashed #111;margin:6px 0}
table{width:100%;border-collapse:collapse;font-size:12px}
@media print{.noprint{display:none}body{padding:0}}
</style></head>
<body>
<div class="struk">
<div class="center"><b>BUBEE</b><br>Stok Real-time • JNE/J&T/SPX<br>{{ $order->kode }}<br>{{ $order->created_at->format('d-m-Y H:i') }}</div>
<hr>
@foreach($order->items as $it)
<div>{{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }})</div>
<table><tr><td>{{ $it->qty }} x {{ number_format($it->harga_satuan) }}</td><td class="right">{{ number_format($it->subtotal) }}</td></tr></table>
@endforeach
<hr>
<table>
<tr><td>Total produk</td><td class="right">{{ number_format($order->total_produk) }}</td></tr>
<tr><td>Ongkir ({{ $order->kurir }})</td><td class="right">{{ number_format($order->ongkir) }}</td></tr>
@if($order->diskon>0)<tr><td>Voucher</td><td class="right">-{{ number_format($order->diskon) }}</td></tr>@endif
<tr><td><b>TOTAL</b></td><td class="right"><b>{{ number_format($order->total_bayar) }}</b></td></tr>
</table>
<hr>
<div class="center">Makasih sudah belanja!<br>Simpan struk ini ya.</div>
</div>
<div class="noprint center" style="margin-top:12px"><button onclick="window.print()" style="padding:12px 32px;font-weight:800;border-radius:12px;border:none;background:#111;color:#fff;cursor:pointer">CETAK STRUK</button></div>
</body></html>
