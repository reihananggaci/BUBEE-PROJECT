@extends('layouts.app')
@section('title','Checkout')
@section('back', '/keranjang')
@section('content')
<h1 class="font-extrabold text-lg">Checkout</h1>
<div class="progress-line my-2"><div style="width:75%"></div></div>
<div class="glass rounded-3xl shadow-card p-4 text-sm">
@foreach($carts as $c)<div class="flex justify-between py-1 border-b border-blue-50"><span>{{ $c->product->nama }} <b class="text-blue-700">({{ $c->variant?->label() }})</b> ×{{ $c->qty }}</span><b>Rp {{ number_format(($c->variant ? $c->variant->hargaUntuk(auth()->user()) : $c->product->hargaUntuk(auth()->user()))*$c->qty) }}</b></div>@endforeach
</div>
<form method="POST" action="/checkout" class="glass rounded-3xl shadow-card p-4 mt-2 space-y-2 text-sm">@csrf
<div class="flex items-center gap-1.5 font-bold text-slate-900">
    <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
    <span>Penerima</span>
</div>
<input name="nama_penerima" value="{{ auth()->user()->name }}" placeholder="Nama penerima" class="w-full border rounded-2xl px-3 py-2.5" required>
<input name="hp_penerima" value="{{ auth()->user()->hp }}" placeholder="HP/WA aktif" class="w-full border rounded-2xl px-3 py-2.5" required>
<textarea name="alamat" placeholder="Alamat lengkap + patokan" class="w-full border rounded-2xl px-3 py-2.5" required>{{ auth()->user()->alamat }}</textarea>
<div class="flex items-center gap-1.5 font-bold text-slate-900 pt-1">
    <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="22" x2="9" y2="22.01"/><line x1="15" y1="22" x2="15" y2="22.01"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="16" y2="14"/><line x1="8" y1="18" x2="16" y2="18"/></svg>
    <span>Kota Tujuan</span>
</div>
<select id="provinsi" class="w-full border rounded-2xl px-3 py-2.5">
<option value="">— Pilih provinsi —</option>
@foreach($provinces as $pv)<option value="{{ $pv['id'] }}">{{ $pv['nama'] }}</option>@endforeach
</select>
<select name="city_id" id="kota" class="w-full border rounded-2xl px-3 py-2.5" required>
<option value="">— Pilih kota —</option>
</select>
<input type="hidden" name="kota_tujuan" id="kota_nama">
<script>
const prov=document.getElementById('provinsi'),kota=document.getElementById('kota'),kotaNama=document.getElementById('kota_nama');
prov.onchange=async()=>{kota.innerHTML='<option>Memuat…</option>';const r=await fetch('/wilayah/kota?province='+prov.value);const list=await r.json();kota.innerHTML='<option value="">— Pilih kota —</option>'+list.map(k=>`<option value="${k.id}">${k.nama}</option>`).join('');};
kota.onchange=()=>{kotaNama.value=kota.options[kota.selectedIndex]?.text||'';};
</script>
<div class="flex items-center gap-1.5 font-bold text-slate-900 pt-1">
    <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    <span>Kurir Pengiriman</span>
</div>
@if($ongkirLive)<div class="text-[11px] text-emerald-700 font-bold flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>JNE, J&T & Sicepat real-time. Lainnya tarif toko.</div>@endif
<select name="kurir" id="kurir" class="w-full border rounded-2xl px-3 py-2.5" required>@php $real=$ongkirLive?['JNE','JNT','SICEPAT']:[]; @endphp<option value="JNE">JNE{{ in_array('JNE',$real)?' (real-time)':'' }}</option><option value="JNT">J&T Express{{ in_array('JNT',$real)?' (real-time)':'' }}</option><option value="SPX">SPX</option><option value="SICEPAT">Sicepat{{ in_array('SICEPAT',$real)?' (real-time)':'' }}</option><option value="LION">Lion Parcel</option><option value="SENTRAL">Sentral Cargo</option></select>
<div class="text-[11px] text-slate-500">Berat ditagih = yang terbesar antara berat asli vs volumetrik (P×L×T ÷ pembagi kurir).</div>
<select name="layanan" id="layanan" class="w-full border rounded-2xl px-3 py-2.5" required>
@foreach($kurir['JNE'] ?? [] as $l)<option value="{{ $l['kode'] }}">JNE - {{ $l['label'] }} (Rp {{ number_format($l['ongkir_per_kg']) }}/kg)</option>@endforeach
</select>
<script>
(function(){
    const kurirData = @json($kurir);
    const selKurir = document.getElementById('kurir');
    const selLayanan = document.getElementById('layanan');
    function syncLayanan(){
        if (!selKurir || !selLayanan) return;
        const k = selKurir.value;
        const list = kurirData[k] || [];
        selLayanan.innerHTML = '';
        list.forEach(function(l){
            const opt = document.createElement('option');
            opt.value = l.kode;
            opt.textContent = k + ' - ' + l.label + ' (Rp ' + Number(l.ongkir_per_kg).toLocaleString('id-ID') + '/kg)';
            selLayanan.appendChild(opt);
        });
    }
    selKurir.addEventListener('change', syncLayanan);
    syncLayanan();
})();
</script>
<div class="flex items-center gap-1.5 font-bold text-slate-900 pt-1">
    <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
    <span>Voucher</span> <span class="font-normal text-slate-400 text-xs">(opsional)</span>
</div>
<input name="voucher_kode" value="{{ old('voucher_kode') }}" placeholder="Ketik kode" class="w-full border rounded-2xl px-3 py-2.5 uppercase">
@if($vouchers->count())
<details class="rounded-2xl bg-amber-50 border border-amber-200 p-2.5 text-xs">
<summary class="font-bold cursor-pointer">Lihat voucher tersedia ({{ $vouchers->count() }})</summary>
@foreach($vouchers as $vc)<div class="py-0.5"><b>{{ $vc->kode }}</b> {{ $vc->label() }}@if($vc->min_belanja>0) • min Rp {{ number_format($vc->min_belanja) }}@endif</div>@endforeach
</details>
@endif
<button class="w-full text-white font-extrabold py-3.5 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#00E5FF)">BUAT ORDER</button>
</form>
@endsection
