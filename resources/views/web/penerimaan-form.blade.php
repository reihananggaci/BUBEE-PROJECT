@extends('layouts.web-admin')
@section('title','Catat Kedatangan')
@section('content')
<a href="/admin/penerimaan" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Barang Masuk</a>
<h1 class="font-extrabold text-2xl mt-1">📝 Catat Kedatangan</h1>
<div class="text-xs text-slate-500 mb-2">Langkah 1️⃣ isi surat jalan → 2️⃣ pilih barang lama / centang barang baru → 3️⃣ verifikasi QC di gudang (stok <b>belum</b> bertambah sekarang).</div>
<div class="flex gap-1.5 text-[11px] font-extrabold mb-3">
<span class="px-3 py-1 rounded-full bg-purple-900 text-white">1 Catat</span>
<span class="px-3 py-1 rounded-full bg-white shadow-card text-slate-500">2 Verifikasi QC</span>
<span class="px-3 py-1 rounded-full bg-white shadow-card text-slate-500">3 Stok +</span>
</div>
<form method="POST" action="/admin/penerimaan" class="bg-white rounded-3xl shadow-card p-5 space-y-2 text-sm max-w-2xl">@csrf
<input name="no_surat_jalan" placeholder="No. surat jalan supplier *" maxlength="50" class="w-full border rounded-2xl px-3 py-2.5" required value="{{ old('no_surat_jalan') }}">
<input name="supplier" placeholder="Supplier (opsional, cth: Konveksi Bandung)" maxlength="100" class="w-full border rounded-2xl px-3 py-2.5" value="{{ old('supplier') }}">
<div id="baris-wrap" class="space-y-2">
<div class="baris border rounded-2xl p-2.5 space-y-1.5 bg-slate-50/50">
<label class="flex items-center gap-2 text-xs font-bold text-purple-800"><input type="checkbox" name="items[0][is_baru]" value="1" class="is-baru w-4 h-4"> 🆕 Barang baru (belum dijual)</label>
<input type="text" class="prod-cari w-full border rounded-xl px-2 py-2 text-xs" placeholder="🔍 Cari barang (nama / kode)…" autocomplete="off">
<select name="items[0][product_id]" class="prod-sel w-full border rounded-xl px-2 py-2 text-xs">
<option value="">— Pilih barang yang sudah ada —</option>
@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->nama }} (stok {{ $p->stok }})</option>@endforeach
</select>
<div class="prod-info text-[10px] text-slate-400"></div>
<select name="items[0][variant_id]" class="var-sel w-full border rounded-xl px-2 py-2 text-xs">
<option value="">— Varian: kosongkan jika tanpa varian —</option>
@foreach($products as $p)
@foreach($p->variants as $v)<option value="{{ $v->id }}" data-prod="{{ $p->id }}">{{ $p->nama }} • {{ $v->label() }} (stok {{ $v->stok }})</option>@endforeach
@endforeach
</select>
<div class="baru-fields space-y-1.5 rounded-xl border border-dashed border-purple-300 bg-purple-50/50 p-2" style="display:none">
<input name="items[0][nama_baru]" placeholder="Nama barang baru *" maxlength="100" class="w-full border rounded-xl px-2 py-2 text-xs">
<div class="grid grid-cols-2 gap-1.5">
<input name="items[0][harga_enduser]" type="number" min="0" placeholder="Harga retail (Rp)" class="w-full border rounded-xl px-2 py-2 text-xs">
<input name="items[0][harga_reseller]" type="number" min="0" placeholder="Harga grosir (Rp)" class="w-full border rounded-xl px-2 py-2 text-xs">
</div>
<div class="text-[11px] text-purple-700">Stok awal 0 — akan bertambah otomatis saat QC lolos.</div>
</div>
<input name="items[0][qty_klaim]" type="number" min="1" placeholder="Qty klaim sesuai surat jalan *" class="w-full border rounded-xl px-2 py-2 text-xs" required>
</div>
</div>
<button type="button" id="tambah-baris" class="w-full bg-slate-100 font-bold py-2.5 rounded-2xl text-xs">+ Tambah baris</button>
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">SIMPAN CATATAN</button>
</form>
<script>
(function(){
var w=document.getElementById('baris-wrap'),n=1;
function filterVarian(b){
  var ps=b.querySelector('.prod-sel'),vs=b.querySelector('.var-sel');
  if(!ps||!vs)return;
  var pid=ps.value;
  vs.querySelectorAll('option').forEach(function(o){
    if(!o.value||!o.dataset.prod){o.style.display='';return;}
    o.style.display=(!pid||o.dataset.prod===pid)?'':'none';
  });
  var cur=vs.querySelector('option[value="'+vs.value+'"]');
  if(cur&&cur.style.display==='none')vs.value='';
}
function filterProduk(b){
  var cari=b.querySelector('.prod-cari'),ps=b.querySelector('.prod-sel'),info=b.querySelector('.prod-info');
  if(!cari||!ps)return;
  var q=cari.value.trim().toLowerCase(),cocok=0;
  ps.querySelectorAll('option').forEach(function(o){
    if(!o.value){o.style.display='';return;}
    var ya=!q||o.textContent.toLowerCase().indexOf(q)!==-1;
    o.style.display=ya?'':'none';
    if(ya)cocok++;
  });
  var pilih=ps.options[ps.selectedIndex];
  if(pilih&&pilih.value&&pilih.style.display==='none')ps.value='';
  if(info)info.textContent=q?(cocok?cocok+' barang cocok':'tidak ada barang cocok'):'';
  filterVarian(b);
}
function syncBaris(b){
  var cb=b.querySelector('.is-baru'),ps=b.querySelector('.prod-sel'),bf=b.querySelector('.baru-fields'),cari=b.querySelector('.prod-cari');
  var baru=cb&&cb.checked;
  if(bf)bf.style.display=baru?'':'none';
  if(ps){ps.required=!baru;ps.disabled=!!baru;if(baru)ps.value='';}
  if(cari){cari.disabled=!!baru;if(baru)cari.value='';}
  if(baru)filterVarian(b);else filterProduk(b);
}
w.addEventListener('change',function(e){
  var b=e.target.closest('.baris');if(!b)return;
  if(e.target.classList.contains('is-baru')||e.target.classList.contains('prod-sel'))syncBaris(b);
});
w.addEventListener('input',function(e){
  if(!e.target.classList.contains('prod-cari'))return;
  var b=e.target.closest('.baris');if(b)filterProduk(b);
});
w.addEventListener('keydown',function(e){
  if(e.key!=='Enter'||!e.target.classList.contains('prod-cari'))return;
  e.preventDefault();
  var b=e.target.closest('.baris'),ps=b&&b.querySelector('.prod-sel');
  if(!ps)return;
  var first=Array.prototype.find.call(ps.options,function(o){return o.value&&o.style.display!=='none';});
  if(first){ps.value=first.value;filterVarian(b);}
});
document.getElementById('tambah-baris').onclick=function(){
  var b=w.children[0].cloneNode(true);
  b.querySelectorAll('input,select').forEach(function(i){
    if(i.name)i.name=i.name.replace(/items\[\d+\]/,'items['+n+']');
    if(i.type==='checkbox')i.checked=false;else i.value='';
  });
  b.querySelectorAll('option').forEach(function(o){o.style.display='';});
  w.appendChild(b);syncBaris(b);n++;
};
w.querySelectorAll('.baris').forEach(syncBaris);
})();
</script>
@endsection
