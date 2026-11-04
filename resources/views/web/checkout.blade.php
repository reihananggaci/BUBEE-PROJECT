@extends('layouts.web')
@section('title','Checkout')
@section('content')
<a href="/keranjang" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali ke Keranjang</a>
<h1 class="font-extrabold text-2xl mt-1">Checkout</h1>
<div class="h-1.5 rounded-full bg-slate-100 overflow-hidden my-3"><div class="h-full bg-blue-700 rounded-full" style="width:75%"></div></div>
{{-- Form kiri + ringkasan kanan (order-2) --}}
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<form method="POST" action="/checkout" class="bg-white border border-slate-100 rounded-3xl shadow-card p-5 space-y-2 text-sm lg:col-span-2 lg:order-1">@csrf
<b class="flex items-center gap-1.5 text-slate-900">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
    <span>Penerima</span>
</b>
<input name="nama_penerima" value="{{ auth()->user()->name }}" placeholder="Nama penerima" class="w-full border rounded-2xl px-3 py-2.5" required>
<input name="hp_penerima" value="{{ auth()->user()->hp }}" placeholder="HP/WA aktif" class="w-full border rounded-2xl px-3 py-2.5" required>
<textarea name="alamat" placeholder="Alamat lengkap + patokan" class="w-full border rounded-2xl px-3 py-2.5" required>{{ auth()->user()->alamat }}</textarea>
<b class="flex items-center gap-1.5 text-slate-900 mt-2">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
    <span>Kota Tujuan</span>
</b>
<div class="grid gap-2 md:grid-cols-2">
<select id="provinsi" class="w-full border rounded-2xl px-3 py-2.5">
<option value="">— Pilih provinsi —</option>
@foreach($provinces as $pv)<option value="{{ $pv['id'] }}">{{ $pv['nama'] }}</option>@endforeach
</select>
<select name="city_id" id="kota" class="w-full border rounded-2xl px-3 py-2.5" required>
<option value="">— Pilih kota —</option>
</select>
</div>
<input type="hidden" name="kota_tujuan" id="kota_nama">
<script>
const prov=document.getElementById('provinsi'),kota=document.getElementById('kota'),kotaNama=document.getElementById('kota_nama');
prov.onchange=async()=>{kota.innerHTML='<option>Memuat…</option>';const r=await fetch('/wilayah/kota?province='+prov.value);const list=await r.json();kota.innerHTML='<option value="">— Pilih kota —</option>'+list.map(k=>`<option value="${k.id}">${k.nama}</option>`).join('');};
kota.onchange=()=>{kotaNama.value=kota.options[kota.selectedIndex]?.text||'';};
</script>
<b class="flex items-center gap-1.5 text-slate-900 mt-2">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    <span>Kurir</span>
</b>
@if($ongkirLive)<div class="text-[11px] text-emerald-700 font-bold flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span><span>JNE, J&T & Sicepat real-time. Lainnya tarif toko.</span></div>@endif
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
<b class="flex items-center gap-1.5 text-slate-900 mt-2">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg>
    <span>Voucher <span class="font-normal text-slate-400">(opsional)</span></span>
</b>
<input name="voucher_kode" value="{{ old('voucher_kode') }}" placeholder="Ketik kode" class="w-full border rounded-2xl px-3 py-2.5 uppercase">
@if($vouchers->count())
<details class="rounded-2xl bg-amber-50 border border-amber-200 p-2.5 text-xs">
<summary class="font-bold cursor-pointer">Lihat voucher tersedia ({{ $vouchers->count() }})</summary>
@foreach($vouchers as $vc)<div class="py-0.5"><b>{{ $vc->kode }}</b> {{ $vc->label() }}@if($vc->min_belanja>0) • min Rp {{ number_format($vc->min_belanja) }}@endif</div>@endforeach
</details>
@endif
<button class="w-full text-white font-extrabold py-3.5 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#00E5FF)">BUAT ORDER</button>
</form>
<div class="bg-white border border-slate-100 rounded-3xl shadow-card p-5 text-sm lg:order-2 lg:sticky lg:top-20 self-start">
<b class="flex items-center gap-1.5 text-slate-900 mb-2">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12V21l-3-2-3 2-3-2-3 2v-17.5z"/><path d="M9 8.5h6M9 12h6"/></svg>
    <span>Ringkasan ({{ $carts->count() }} item)</span>
</b>
@foreach($carts as $c)<div class="flex justify-between py-1 border-b border-blue-50"><span>{{ $c->product->nama }} <b class="text-blue-700">({{ $c->variant?->label() }})</b> ×{{ $c->qty }}</span><b>Rp {{ number_format(($c->variant ? $c->variant->hargaUntuk(auth()->user()) : $c->product->hargaUntuk(auth()->user()))*$c->qty) }}</b></div>@endforeach
<div class="text-[11px] text-slate-500 mt-2">Ongkir & diskon dihitung saat order dibuat. Pastikan alamat & kota sudah benar.</div>
</div>
</div>
@endsection
