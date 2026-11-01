<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8">
<style>
body{font-family:sans-serif;font-size:12px;color:#111}
.header{border-bottom:3px solid #1D4ED8;padding-bottom:10px;margin-bottom:12px}
.header h1{margin:0;color:#0B2A6B;letter-spacing:3px}
table{width:100%;border-collapse:collapse;margin-top:8px}
th{background:#0B2A6B;color:#fff;padding:7px;text-align:left}
td{padding:7px;border-bottom:1px solid #ddd}
.right{text-align:right}
.total td{font-weight:bold;border-top:2px solid #0B2A6B}
.small{font-size:11px;color:#555}
</style>
</head>
<body>
<div class="header">
<h1>BUBEE</h1>
<div class="small">Sistem Informasi Stok & Pemesanan • JNE / J&T / SPX</div>
</div>
<table>
<tr><td><b>No. Order:</b> {{ $order->kode }}<br><b>Tanggal:</b> {{ $order->created_at->format('d M Y H:i') }}<br><b>Status:</b> {{ strtoupper($order->status) }}</td>
<td><b>Penerima:</b> {{ $order->nama_penerima }} ({{ $order->hp_penerima }})<br>{{ $order->alamat }}@if($order->kota_tujuan)<br>{{ $order->kota_tujuan }}@endif<br><b>Kurir:</b> {{ $order->kurir }} {{ $order->layanan }} @if($order->resi)<br><b>Resi:</b> {{ $order->resi }}@endif</td></tr>
</table>
<table>
<tr><th>No</th><th>Barang</th><th>Varian</th><th>Qty</th><th class="right">Harga</th><th class="right">Subtotal</th></tr>
@foreach($order->items as $i=>$it)
<tr><td>{{ $i+1 }}</td><td>{{ $it->product->nama ?? '-' }} <span class="small">({{ $it->tipe_harga }})</span></td><td>{{ $it->variant?->label() ?? '-' }}</td><td>{{ $it->qty }}</td><td class="right">Rp {{ number_format($it->harga_satuan) }}</td><td class="right">Rp {{ number_format($it->subtotal) }}</td></tr>
@endforeach
<tr><td colspan="5" class="right">Total produk</td><td class="right">Rp {{ number_format($order->total_produk) }}</td></tr>
<tr><td colspan="5" class="right">Ongkir</td><td class="right">Rp {{ number_format($order->ongkir) }}</td></tr>
@if($order->diskon>0)<tr><td colspan="5" class="right">Voucher {{ $order->voucher->kode ?? '' }}</td><td class="right">-Rp {{ number_format($order->diskon) }}</td></tr>@endif
<tr class="total"><td colspan="5" class="right">TOTAL BAYAR</td><td class="right">Rp {{ number_format($order->total_bayar) }}</td></tr>
</table>
<p class="small">Dicetak {{ now()->format('d M Y H:i') }} • Simpan invoice ini sebagai bukti pembelian yang sah.</p>
</body>
</html>
