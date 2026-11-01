@extends('layouts.web')
@section('title','Checkout')
@section('content')
<a href="/keranjang" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali ke Keranjang</a>
<h1 class="font-extrabold text-2xl mt-1">Checkout</h1>
<div class="h-1.5 rounded-full bg-slate-100 overflow-hidden my-3"><div class="h-full bg-blue-700 rounded-full" style="width:75%"></div></div>
{{-- Form kiri + ringkasan kanan (order-2) --}}
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<form method="POST" action="/checkout" class="bg-white border border-slate-100 rounded-3xl shadow-card p-5 space-y-2 text-sm lg:col-span-2 lg:order-1">@csrf
<b>📍 Penerima</b>
<input name="nama_penerima" value="{{ auth()->user()->name }}" placeholder="Nama penerima" class="w-full border rounded-2xl px-3 py-2.5" required>
<input name="hp_penerima" value="{{ auth()->user()->hp }}" placeholder="HP/WA aktif" class="w-full border rounded-2xl px-3 py-2.5" required>
<textarea name="alamat" placeholder="Alamat lengkap + patokan" class="w-full border rounded-2xl px-3 py-2.5" required>{{ auth()->user()->alamat }}</textarea>
<b>🏙️ Kota Tujuan</b>
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
<b>🚚 Kurir</b>
@if($ongkirLive)<div class="text-[11px] text-emerald-700 font-bold">🟢 JNE, J&T & Sicepat real-time. Lainnya tarif toko.</div>@endif
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
<b>🎟️ Voucher <span class="font-normal text-slate-400">(opsional)</span></b>
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
<b>🧾 Ringkasan ({{ $carts->count() }} item)</b>
@foreach($carts as $c)<div class="flex justify-between py-1 border-b border-blue-50"><span>{{ $c->product->nama }} <b class="text-blue-700">({{ $c->variant?->label() }})</b> ×{{ $c->qty }}</span><b>Rp {{ number_format($c->product->hargaUntuk(auth()->user())*$c->qty) }}</b></div>@endforeach
<div class="text-[11px] text-slate-500 mt-2">Ongkir & diskon dihitung saat order dibuat. Pastikan alamat & kota sudah benar.</div>
</div>
</div>
@endsection
