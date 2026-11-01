@extends('layouts.app')
@section('title','Reseller Center')
@section('content')
<div class="rounded-[2rem] p-5 text-white shadow-card relative overflow-hidden" style="background:var(--hex),linear-gradient(135deg,#083344,#0e7490 60%,#22d3ee)">
<div class="flex items-center gap-3 relative">
<div class="hexbee">🏪</div>
<div class="flex-1 min-w-0">
<div class="text-[11px] tracking-widest text-cyan-200 font-bold">🏪 RESELLER CENTER</div>
<div class="font-extrabold text-xl">Halo {{ explode(' ',$user->name)[0] }}!</div>
<div class="text-xs opacity-80">Tier <b>{{ $tier }}</b> • join {{ $user->created_at->format('M Y') }}</div>
</div>
</div>
<div class="grid grid-cols-3 gap-2 mt-3 text-center relative">
<div class="rounded-2xl p-2.5 bg-black/25"><div class="text-[10px] opacity-70">TOTAL HEMAT</div><div class="font-extrabold">Rp {{ number_format($totalHemat) }}</div></div>
<div class="rounded-2xl p-2.5 bg-black/25"><div class="text-[10px] opacity-70">PCS</div><div class="font-extrabold text-lg">{{ $totalPcs }}</div></div>
<div class="rounded-2xl p-2.5 bg-black/25"><div class="text-[10px] opacity-70">ORDER</div><div class="font-extrabold text-lg">{{ $totalOrder }}</div></div>
</div>
<div class="grid grid-cols-3 gap-2 mt-2 text-center text-[11px] font-bold relative">
<a href="/reseller/etalase" class="bg-white text-cyan-900 rounded-2xl py-2.5">🏷️ Etalase Modal</a>
<a href="/reseller/hemat" class="bg-white/20 rounded-2xl py-2.5">💰 Riwayat Hemat</a>
<a href="/pesanan" class="bg-white/20 rounded-2xl py-2.5">📦 Pesanan @if($menunggu>0)({{ $menunggu }})@endif</a>
</div>
</div>

@if($menunggu>0)
<a href="/pesanan" class="block glass rounded-3xl p-3 mt-2 text-sm shadow-card">⏳ <b>{{ $menunggu }} order</b> menunggu bayar. <span class="text-blue-700 font-bold">Bayar →</span></a>
@endif

@if($vouchers->count())
<div class="glass rounded-3xl shadow-card p-4 mt-2 text-sm">
<b>🎟️ Voucher khusus kamu</b>
@foreach($vouchers as $v)<div class="flex justify-between text-xs py-1 border-b border-blue-50 last:border-0"><span><b>{{ $v->kode }}</b> {{ $v->label() }}</span><span class="text-slate-400">{{ $v->terpakai }}/{{ $v->kuota }}</span></div>@endforeach
</div>
@endif

@if($menipis->count())
<div class="rounded-3xl p-4 mt-2 text-sm text-white shadow-card" style="background:linear-gradient(90deg,#b45309,#ef4444)">
⚠️ <b>Stok menipis, amankan sebelum kehabisan:</b>
@foreach($menipis as $p)<div>• {{ $p->nama }} — sisa {{ $p->stok }}</div>@endforeach
</div>
@endif

<div class="glass rounded-3xl p-4 mt-2 text-[12px] shadow-card">
<b>🚀 Cara cuan maksimal:</b> cek Etalase → pilih margin terbesar → share ke WA → order di atas min. pcs biar harga modal aktif + tumpuk voucher reseller.
</div>
@endsection
