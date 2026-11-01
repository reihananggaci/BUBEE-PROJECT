<?php $__env->startSection('title','Home'); ?>
<?php $__env->startSection('content'); ?>

<div class="rounded-[1.75rem] p-5 text-white shadow-card relative overflow-hidden anim-in" style="--d:0ms;background:var(--hex),linear-gradient(135deg,#050B2E,#0B2A6B 55%,#1D4ED8)">
<div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-cyan-400/30 blur-2xl"></div>
<div class="relative flex items-start gap-3">
<div class="flex-1 min-w-0">
<div class="text-[11px] tracking-widest text-cyan-300 font-bold">● STOK REAL-TIME</div>
<div class="font-extrabold text-xl mt-1">Halo <?php echo e(auth()->check() ? explode(' ',auth()->user()->name)[0] : 'Kak'); ?> 👋</div>
<div class="text-xs opacity-70 mt-1">Lihat stok, cek harga, pesan sekarang. Semua dalam satu tempat.</div>
</div>
<div class="hexbee" title="Bubee">🐝</div>
</div>
<div class="relative">
<form method="GET" action="/" class="mt-3 flex gap-2">
<input type="hidden" name="filter" value="<?php echo e($filter); ?>"><input type="hidden" name="kategori" value="<?php echo e($kategori); ?>"><input type="hidden" name="sort" value="<?php echo e($sort); ?>">
<input name="q" value="<?php echo e($q ?? ''); ?>" placeholder="🔍 Cari barang…" class="flex-1 rounded-2xl px-4 py-2.5 text-sm text-slate-900 outline-none">
<button class="bg-cyan-300 text-slate-900 font-extrabold px-4 rounded-2xl text-sm">Cari</button>
</form>
</div>
</div>


<div class="glass rounded-3xl shadow-card p-3 mt-3 anim-in" style="--d:60ms">
<div class="flex gap-1.5 text-[11px] font-bold overflow-x-auto pb-1">
<?php $__currentLoopData = ['semua'=>'Semua','baru'=>'🆕 Baru','tersedia'=>'Ready','menipis'=>'Menipis','habis'=>'Habis']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<a href="/?filter=<?php echo e($k); ?>&q=<?php echo e($q); ?>&kategori=<?php echo e($kategori); ?>&sort=<?php echo e($sort); ?>" class="px-3 py-1.5 rounded-full whitespace-nowrap <?php echo e(($filter??'semua')===$k?'bg-blue-900 text-white':'bg-slate-100 text-slate-600'); ?>"><?php echo e($l); ?></a>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div class="flex gap-1.5 text-[11px] font-bold overflow-x-auto items-center">
<a href="/?filter=<?php echo e($filter); ?>&q=<?php echo e($q); ?>&kategori=semua&sort=<?php echo e($sort); ?>" class="px-3 py-1.5 rounded-full whitespace-nowrap <?php echo e(($kategori??'semua')==='semua'?'bg-amber-400 text-slate-900':'bg-slate-100 text-slate-600'); ?>">Semua</a>
<?php $__currentLoopData = $daftarKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<a href="/?filter=<?php echo e($filter); ?>&q=<?php echo e($q); ?>&kategori=<?php echo e($kt); ?>&sort=<?php echo e($sort); ?>" class="px-3 py-1.5 rounded-full whitespace-nowrap <?php echo e(($kategori??'semua')===$kt?'bg-amber-400 text-slate-900':'bg-slate-100 text-slate-600'); ?>"><?php echo e($kt); ?></a>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<form method="GET" action="/" class="ml-auto flex-none">
<input type="hidden" name="filter" value="<?php echo e($filter); ?>"><input type="hidden" name="q" value="<?php echo e($q); ?>"><input type="hidden" name="kategori" value="<?php echo e($kategori); ?>">
<select name="sort" onchange="this.form.submit()" class="rounded-full text-[11px] font-bold px-2 py-1.5 bg-slate-100 text-slate-700 outline-none">
<option value="stok" <?php echo e($sort==='stok'?'selected':''); ?>>Stok terbanyak</option>
<option value="murah" <?php echo e($sort==='murah'?'selected':''); ?>>Termurah</option>
<option value="mahal" <?php echo e($sort==='mahal'?'selected':''); ?>>Termahal</option>
</select>
</form>
</div>
</div>


<?php if(auth()->check() && auth()->user()->role==='reseller' && auth()->user()->status_reseller!=='approved'): ?>
<div class="rounded-2xl p-3 mt-3 text-xs bg-amber-50 border border-amber-200 text-amber-800">⏳ Menunggu approval admin. Cek status di <a href="/akun" class="font-bold underline">Akun</a>.</div>
<?php endif; ?>
<?php if(auth()->check() && auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved'): ?>
<div class="rounded-2xl p-3 mt-3 text-xs font-bold text-white" style="background:linear-gradient(90deg,#059669,#00E5FF)">🎉 Harga reseller aktif! Beli di atas min. pcs per produk.</div>
<?php endif; ?>


<div class="grid grid-cols-2 gap-3 mt-3">
<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $st=$p->statusStok(); $harga=$p->hargaUntuk(auth()->user()); $disk=$p->harga_enduser>0?round(($p->harga_enduser-$p->harga_reseller)/$p->harga_enduser*100):0; ?>
<div class="glass rounded-[1.5rem] shadow-card overflow-hidden card-hover flex flex-col h-full anim-in" style="--d:<?php echo e(min($loop->index*50,400)); ?>ms">
<a href="/produk/<?php echo e($p->id); ?>" class="flex-none flex items-center justify-center text-5xl relative overflow-hidden block" style="background:linear-gradient(180deg,#D8E6FF,#fff);aspect-ratio:4/3">
<?php if($p->foto): ?><img src="/storage/<?php echo e($p->foto); ?>" alt="<?php echo e($p->nama); ?>" class="absolute inset-0 w-full h-full object-cover"><?php else: ?><span class="floaty relative">👕</span><?php endif; ?>
<?php if($disk>0): ?><span class="absolute top-2 left-2 text-[10px] font-extrabold bg-pink-500 text-white px-2 py-0.5 rounded-full">-<?php echo e($disk); ?>% RSLR</span><?php endif; ?>
<?php if($st==='habis'): ?><span class="absolute top-2 right-2 text-[10px] font-bold bg-slate-900 text-white px-2 py-0.5 rounded-full">HABIS</span>
<?php elseif($st==='menipis'): ?><span class="absolute top-2 right-2 text-[10px] font-bold bg-amber-400 px-2 py-0.5 rounded-full chip-live">SISA <?php echo e($p->stok); ?></span>
<?php else: ?><span class="absolute top-2 right-2 text-[10px] font-bold bg-emerald-400 px-2 py-0.5 rounded-full">● <?php echo e($p->stok); ?></span><?php endif; ?>
</a>
<div class="p-3 flex flex-col flex-1">
<a href="/produk/<?php echo e($p->id); ?>" class="p-title font-bold text-[13px] leading-tight block text-slate-900 hover:text-blue-700"><?php echo e($p->nama); ?> <?php if($p->created_at && $p->created_at->gte(now()->subDays(7))): ?><span class="text-[9px] font-extrabold bg-cyan-400 text-slate-900 px-1.5 py-0.5 rounded-full align-middle">BARU</span><?php endif; ?></a>
<div class="text-[10px] mt-0.5 text-slate-500"><?php if($p->reviews_count>0): ?><span class="text-amber-500 font-bold">★ <?php echo e(number_format($p->reviews_avg_rating,1)); ?></span> • <?php endif; ?><?php echo e($p->kategori); ?> • min <?php echo e($p->min_reseller); ?> pcs</div>
<div style="margin-top:auto">
<?php if(auth()->check()): ?>
<?php
    $isRslrM = auth()->user()->role==='reseller' && auth()->user()->status_reseller==='approved';
?>
<div class="price-slot">
    <div class="font-extrabold text-blue-900">Rp <?php echo e(number_format($harga)); ?> <span class="text-[10px] font-bold <?php echo e($isRslrM?'text-emerald-600':'text-slate-400'); ?>"><?php echo e($isRslrM?'GROSIR':'RETAIL'); ?></span></div>
    <?php if(! $isRslrM): ?><div class="text-[10px] text-emerald-600 font-bold">Grosir Rp <?php echo e(number_format($p->harga_reseller)); ?> • min <?php echo e($p->min_reseller); ?> pcs</div><?php endif; ?>
</div>
<a href="/produk/<?php echo e($p->id); ?>" class="lihat-btn block text-center text-[13px] font-extrabold py-2 rounded-2xl text-white" style="background:linear-gradient(90deg,#0B2A6B,#1D4ED8)">Lihat</a>
<?php else: ?>
<a href="/login" class="price-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Login untuk lihat harga</a>
<?php endif; ?>
</div>
</div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php if($products->isEmpty()): ?><div class="glass rounded-3xl p-8 text-center text-sm mt-3"><div class="hexbee mx-auto">🐝</div><div class="mt-2">Produk tidak ketemu di sarang…<br><a href="/" class="font-bold text-blue-700">Reset →</a></div></div><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\projek wp3 - Copy\resources\views/home.blade.php ENDPATH**/ ?>