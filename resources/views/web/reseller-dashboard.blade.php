@extends('layouts.web')
@section('title','Reseller Center')
@section('content')
<div class="grid gap-4 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2 rounded-3xl p-6 text-white shadow-card relative overflow-hidden" style="background:linear-gradient(135deg,#083344,#0e7490 60%,#22d3ee)">
<div class="flex items-center gap-3 relative">
<div class="text-4xl">🏪</div>
<div class="flex-1 min-w-0">
<div class="text-[11px] tracking-widest text-cyan-200 font-bold">🏪 RESELLER CENTER</div>
<div class="font-extrabold text-2xl">Halo {{ explode(' ',$user->name)[0] }}!</div>
<div class="text-xs opacity-80">Tier <b>{{ $tier }}</b> • join {{ $user->created_at->format('M Y') }}</div>
</div>
</div>
<div class="grid grid-cols-3 gap-2 mt-4 text-center relative">
<div class="rounded-2xl p-3 bg-black/25"><div class="text-[10px] opacity-70">TOTAL HEMAT</div><div class="font-extrabold">Rp {{ number_format($totalHemat) }}</div></div>
<div class="rounded-2xl p-3 bg-black/25"><div class="text-[10px] opacity-70">PCS</div><div class="font-extrabold text-lg">{{ $totalPcs }}</div></div>
<div class="rounded-2xl p-3 bg-black/25"><div class="text-[10px] opacity-70">ORDER</div><div class="font-extrabold text-lg">{{ $totalOrder }}</div></div>
</div>
<div class="grid grid-cols-3 gap-2 mt-2 text-center text-[11px] font-bold relative">
<a href="/reseller/etalase" class="bg-white text-cyan-900 rounded-2xl py-2.5" style="text-decoration:none">🏷️ Etalase Modal</a>
<a href="/reseller/hemat" class="bg-white/20 rounded-2xl py-2.5 text-white" style="text-decoration:none">💰 Riwayat Hemat</a>
<a href="/pesanan" class="bg-white/20 rounded-2xl py-2.5 text-white" style="text-decoration:none">📦 Pesanan @if($menunggu>0)({{ $menunggu }})@endif</a>
</div>
</div>
<div class="space-y-2 self-start lg:sticky lg:top-20">
@if($menunggu>0)
<a href="/pesanan" class="block bg-white rounded-3xl p-4 text-sm shadow-card" style="text-decoration:none;color:#0f172a">⏳ <b>{{ $menunggu }} order</b> menunggu bayar. <span class="text-blue-700 font-bold">Bayar →</span></a>
@endif
@if($vouchers->count())
<div class="bg-white rounded-3xl shadow-card p-4 text-sm">
<b>🎟️ Voucher khusus kamu</b>
@foreach($vouchers as $v)<div class="flex justify-between text-xs py-1 border-b border-blue-50 last:border-0"><span><b>{{ $v->kode }}</b> {{ $v->label() }}</span><span class="text-slate-400">{{ $v->terpakai }}/{{ $v->kuota }}</span></div>@endforeach
</div>
@endif
@if($menipis->count())
<div class="rounded-3xl p-4 text-sm text-white shadow-card" style="background:linear-gradient(90deg,#b45309,#ef4444)">
⚠️ <b>Stok menipis, amankan sebelum kehabisan:</b>
@foreach($menipis as $p)<div>• {{ $p->nama }} — sisa {{ $p->stok }}</div>@endforeach
</div>
@endif
<div class="bg-white rounded-3xl p-4 text-[12px] shadow-card">
<b>🚀 Cara cuan maksimal:</b> cek Etalase → pilih margin terbesar → share ke WA → order di atas min. pcs biar harga modal aktif + tumpuk voucher reseller.
</div>
</div>
</div>
@endsection
