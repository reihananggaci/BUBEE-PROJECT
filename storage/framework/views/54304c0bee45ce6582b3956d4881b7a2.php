<?php $__env->startSection('title',$product->nama); ?>
<?php $__env->startSection('back', '/'); ?>
<?php $__env->startSection('content'); ?>
<div class="glass rounded-[2rem] shadow-card overflow-hidden">
<div class="h-52 flex items-center justify-center text-8xl relative overflow-hidden" style="background:linear-gradient(180deg,#0B2A6B,#1D4ED8)">
<?php if($product->foto): ?><img src="/storage/<?php echo e($product->foto); ?>" alt="<?php echo e($product->nama); ?>" class="absolute inset-0 w-full h-full object-cover"><?php else: ?><span class="floaty relative">👕</span><?php endif; ?>
<span class="absolute bottom-3 text-[11px] font-bold bg-black/40 text-white px-3 py-1 rounded-full">📦 Total stok: <?php echo e($product->stok); ?> • <?php echo e($product->berat_gram); ?> gr</span>
</div>
<div class="p-4">
<h1 class="font-extrabold"><?php echo e($product->nama); ?></h1>
<div class="flex items-center gap-2 mt-1 text-sm">
<?php if($jml>0): ?><span class="text-amber-500 font-bold"><?php echo e(\App\Models\Review::bintang($rata)); ?></span><span class="text-xs text-slate-500"><?php echo e($rata); ?> (<?php echo e($jml); ?> ulasan)</span><?php else: ?><span class="text-xs text-slate-400">Belum ada ulasan</span><?php endif; ?>
<span class="text-[11px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-bold"><?php echo e($product->kategori); ?></span>
</div>
<?php
$shareText = auth()->check()
    ? 'Lihat nih ' . $product->nama . ' cuma Rp ' . number_format($product->hargaUntuk(auth()->user())) . ' di Bubee! ' . url('/produk/' . $product->id)
    : 'Lihat nih ' . $product->nama . ' di Bubee! ' . url('/produk/' . $product->id);
$share = 'https://wa.me/?text=' . urlencode($shareText);
?>
<a href="<?php echo e($share); ?>" target="_blank" class="block text-center mt-2 bg-emerald-50 text-emerald-700 font-bold py-2.5 rounded-2xl text-sm">📤 Bagikan ke WA</a>
<p class="text-sm text-slate-500 mt-2"><?php echo e($product->deskripsi); ?></p>
<?php if(auth()->check()): ?>
<?php
    $isRslrDetail = auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved';
    $hargaDetail = $product->hargaUntuk(auth()->user());
?>
<div class="mt-2 rounded-2xl p-3 text-sm bg-blue-50 border border-blue-100">
    <div>Harga: <b class="text-lg text-blue-900">Rp <?php echo e(number_format($hargaDetail)); ?></b> <span class="text-[11px] font-bold <?php echo e($isRslrDetail ? 'text-emerald-600' : 'text-slate-400'); ?>"><?php echo e($isRslrDetail ? 'GROSIR' : 'RETAIL'); ?></span></div>
    <div class="text-[11px] text-slate-500">Normal Rp <?php echo e(number_format($product->harga_enduser)); ?> • Reseller mulai Rp <?php echo e(number_format(\App\Services\PriceService::resellerTermurah($product))); ?> (min <?php echo e(\App\Services\PriceService::minReseller($product)); ?> pcs)</div>
</div>
<?php else: ?>
<div class="mt-2 rounded-2xl p-3 text-sm bg-blue-50 border border-blue-100 flex items-center justify-between gap-3">
    <div>
        <div class="font-extrabold text-blue-900 flex items-center gap-1.5"><svg class="w-4 h-4 text-blue-700 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Harga Terkunci 🔒</div>
        <div class="text-[11px] text-slate-500">Login untuk melihat harga retail & grosir</div>
    </div>
    <a href="/login" class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold text-white shrink-0" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Login →</a>
</div>
<?php endif; ?>
<?php if(auth()->check()): ?>
<?php ($varianTerpilih = $product->variants->first(fn($v) => $v->stok > 0)?->id); ?>
<form id="form-keranjang" method="POST" action="/keranjang/<?php echo e($product->id); ?>" class="mt-3 space-y-2"><?php echo csrf_field(); ?>
<label class="text-sm font-bold">Pilih varian</label>
<div class="grid grid-cols-2 gap-1.5">
<?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<label data-stok="<?php echo e($v->stok); ?>" data-label="<?php echo e($v->label()); ?>" class="border-2 rounded-2xl px-3 py-2 text-center text-sm cursor-pointer transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 <?php echo e($v->stok<=0?'opacity-40':''); ?>">
<input type="radio" name="variant_id" value="<?php echo e($v->id); ?>" class="sr-only" <?php echo e($varianTerpilih === $v->id ? 'checked' : ''); ?> <?php echo e($v->stok<=0?'disabled':''); ?>>
<div class="font-bold"><?php echo e($v->label()); ?></div>
<div class="text-[11px] <?php echo e($v->stok<=0?'text-red-500':'text-slate-500'); ?>"><?php echo e($v->stok<=0?'Habis':'Sisa '.$v->stok); ?></div>
</label>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div id="varian-info" class="text-[11px] text-slate-500"></div>
<div class="flex gap-2">
<input type="number" name="qty" value="1" min="1" max="<?php echo e(max(1,$product->stok)); ?>" class="w-20 border rounded-2xl px-3 py-2.5 text-center font-bold">
<button id="tombol-keranjang" class="flex-1 text-white font-extrabold py-2.5 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#00B8D4)" <?php echo e($product->stok<=0?'disabled':''); ?>><?php echo e($product->stok<=0?'STOK HABIS':'+ KERANJANG'); ?></button>
</div>
</form>
<script>
(function(){
    var form = document.getElementById('form-keranjang');
    if (!form) return;
    var info = document.getElementById('varian-info');
    var qty = form.querySelector('input[name="qty"]');
    var tombol = document.getElementById('tombol-keranjang');
    function sync(){
        var pilih = form.querySelector('input[name="variant_id"]:checked');
        if (!pilih) {
            tombol.disabled = true;
            tombol.textContent = 'PILIH VARIAN';
            info.textContent = 'Silakan pilih varian yang tersedia.';
            return;
        }
        var kartu = pilih.closest('label');
        var stok = parseInt(kartu.dataset.stok, 10) || 0;
        qty.max = Math.max(1, stok);
        if (parseInt(qty.value, 10) > stok) qty.value = Math.max(1, stok);
        info.textContent = 'Dipilih: ' + kartu.dataset.label + ' • sisa ' + stok;
        tombol.disabled = stok <= 0;
        tombol.textContent = stok <= 0 ? 'STOK HABIS' : '+ KERANJANG';
    }
    form.querySelectorAll('input[name="variant_id"]').forEach(function(r){ r.addEventListener('change', sync); });
    qty.addEventListener('change', sync);
    form.addEventListener('submit', function(e){
        var pilih = form.querySelector('input[name="variant_id"]:checked');
        if (!pilih || (parseInt(pilih.closest('label')?.dataset?.stok, 10) || 0) <= 0) {
            e.preventDefault();
            alert('Pilih varian yang masih tersedia.');
        }
    });
    sync();
})();
</script>
<?php else: ?><a href="/login" class="block text-center mt-3 text-white font-bold py-3 rounded-2xl" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Login untuk Lihat Harga & Order →</a><?php endif; ?>
</div>
</div>

<div id="ulasan" class="glass rounded-[2rem] shadow-card p-4 mt-3">
<div class="font-extrabold text-sm">⭐ Ulasan Pembeli (<?php echo e($jml); ?>)</div>
<?php if($bolehUlas): ?>
<details class="mt-2 text-sm bg-amber-50 border border-amber-100 rounded-2xl p-3">
<summary class="font-bold cursor-pointer">✍️ Tulis ulasanmu</summary>
<form method="POST" action="/produk/<?php echo e($product->id); ?>/ulasan" enctype="multipart/form-data" class="mt-2 space-y-1.5"><?php echo csrf_field(); ?>
<select name="rating" class="w-full border rounded-2xl px-3 py-2"><option value="5">⭐⭐⭐⭐⭐ (5)</option><option value="4">⭐⭐⭐⭐ (4)</option><option value="3">⭐⭐⭐ (3)</option><option value="2">⭐⭐ (2)</option><option value="1">⭐ (1)</option></select>
<textarea name="ulasan" placeholder="Ceritakan pengalamanmu…" class="w-full border rounded-2xl px-3 py-2"></textarea>
<input type="file" name="foto" accept="image/*" class="w-full text-xs">
<button class="w-full bg-amber-400 font-extrabold py-2.5 rounded-2xl text-sm">KIRIM</button>
</form>
</details>
<?php endif; ?>
<?php $__currentLoopData = $ulasan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="py-2 border-b border-blue-50 last:border-0 text-sm">
<div class="flex justify-between"><b><?php echo e($u->user->name ?? 'Anonim'); ?></b><span class="text-amber-500 text-xs"><?php echo e(str_repeat('★',$u->rating)); ?><?php echo e(str_repeat('☆',5-$u->rating)); ?></span></div>
<?php if($u->ulasan): ?><div class="text-xs text-slate-600"><?php echo e($u->ulasan); ?></div><?php endif; ?>
<?php if($u->foto): ?><a href="/storage/<?php echo e($u->foto); ?>" target="_blank"><img src="/storage/<?php echo e($u->foto); ?>" class="w-20 h-20 rounded-xl object-cover mt-1"></a><?php endif; ?>
<div class="text-[10px] text-slate-400"><?php echo e($u->created_at->format('d M Y')); ?></div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php if($jml===0): ?><div class="text-xs text-slate-400 mt-1">Jadilah yang pertama mengulas setelah order selesai! 🐝</div><?php endif; ?>
</div>
<div>
<div class="font-extrabold text-sm mt-4 mb-2">✨ Mungkin kamu suka</div>
<div class="grid grid-cols-2 gap-2">
<?php $__currentLoopData = $lainnya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="/produk/<?php echo e($l->id); ?>" class="glass rounded-3xl p-3 shadow-card card-hover flex flex-col h-full"><div class="text-3xl text-center"><?php if($l->foto): ?><img src="/storage/<?php echo e($l->foto); ?>" alt="<?php echo e($l->nama); ?>" class="w-full h-20 rounded-2xl object-cover"><?php else: ?>👕<?php endif; ?></div><div class="text-[12px] font-bold mt-1"><?php echo e($l->nama); ?></div><?php if(auth()->check()): ?><div class="text-[12px] text-blue-800 font-extrabold" style="margin-top:auto">Rp <?php echo e(number_format($l->hargaUntuk(auth()->user()))); ?></div><?php else: ?><div style="margin-top:auto"><span class="price-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4.5" y="10.5" width="15" height="9.5" rx="2.5"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/></svg>Login untuk lihat harga</span></div><?php endif; ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\projek wp3 - Copy\resources\views/product-detail.blade.php ENDPATH**/ ?>