<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Label {{ $order->kode }}</title>
<style>
body{font-family:sans-serif;margin:0;padding:12px;color:#111}
.label{max-width:420px;margin:0 auto;border:3px dashed #111;border-radius:12px;padding:14px}
h1{margin:0;font-size:22px;letter-spacing:2px}
.kurir{font-size:28px;font-weight:800;margin:6px 0}
.box{border:1px solid #999;border-radius:8px;padding:8px;margin-top:8px;font-size:13px}
.small{font-size:11px;color:#555}
@media print{.noprint{display:none}body{padding:0}}
</style></head>
<body>
<div class="label">
<h1 style="display:flex;align-items:center;gap:8px"><img src="/images/logo-bubee.png" style="width:26px;height:26px;object-fit:contain" alt="Bubee"> BUBEE</h1>
<div class="kurir">{{ $order->kurir }} {{ $order->layanan }}</div>
<div><b>{{ $order->kode }}</b> • {{ $order->created_at->format('d M Y') }}</div>
<div class="box"><b>KEPADA:</b><br><b>{{ $order->nama_penerima }}</b> ({{ $order->hp_penerima }})<br>{{ $order->alamat }}@if($order->kota_tujuan)<br>{{ $order->kota_tujuan }}@endif</div>
<div class="box"><b>ISI:</b><br>@foreach($order->items as $it){{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }}<br>@endforeach</div>
@if($order->resi)<div class="box"><b>RESI: {{ $order->resi }}</b></div>@endif
<div class="small" style="margin-top:6px">Dicetak {{ now()->format('d M Y H:i') }} • Tempel di paket.</div>
</div>
<div class="noprint" style="text-align:center;margin-top:12px"><button onclick="window.print()" style="padding:12px 32px;font-weight:800;border-radius:12px;border:none;background:#111;color:#fff;cursor:pointer">CETAK LABEL</button></div>
</body></html>
