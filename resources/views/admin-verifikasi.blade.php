@extends('layouts.admin')
@section('title','Verifikasi Bayar')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">✅ Verifikasi Pembayaran</h1>
<div class="text-xs text-slate-500 mb-2">Khusus admin: setujui bukti transfer / tolak. Belum bayar ({{ $menunggu }}) ada di Kelola Order.</div>
@foreach($orders as $o)
<div class="glass rounded-3xl shadow p-4 mb-2 text-sm">
<div class="flex justify-between"><b>{{ $o->kode }}</b><b class="text-fuchsia-700">Rp {{ number_format($o->total_bayar) }}</b></div>
<div class="text-xs text-slate-500">{{ $o->user->name ?? '' }} • {{ $o->created_at->format('d M H:i') }}</div>
@foreach($o->items as $it)<div class="text-xs">- {{ $it->product->nama ?? '' }} ({{ $it->variant?->label() }}) ×{{ $it->qty }}</div>@endforeach
@if($o->bukti_bayar)<a href="/storage/{{ $o->bukti_bayar }}" target="_blank" class="block mt-1 rounded-2xl overflow-hidden border"><img src="/storage/{{ $o->bukti_bayar }}" class="w-full max-h-56 object-contain bg-slate-100"></a>@endif
<div class="flex gap-1.5 mt-2">
<form method="POST" action="/admin/verifikasi/{{ $o->id }}/setuju" class="flex-1">@csrf<input name="resi" required maxlength="50" placeholder="No resi" class="w-full rounded-xl border border-slate-200 px-2 py-1.5 text-xs mb-1"><button class="w-full bg-emerald-500 text-white font-bold py-2.5 rounded-2xl text-xs">✅ SETUJU → KIRIM</button></form>
<form method="POST" action="/admin/verifikasi/{{ $o->id }}/tolak" class="flex-1" onsubmit="return confirm('Tolak bukti? Stok kembali.')">@csrf<button class="w-full bg-red-500 text-white font-bold py-2.5 rounded-2xl text-xs">❌ TOLAK + BATAL</button></form>
</div>
</div>
@endforeach
@if($orders->isEmpty())<div class="glass rounded-3xl p-8 text-center text-sm">Tidak ada bukti menunggu verifikasi. 🎉</div>@endif

{{-- Navigasi Panah Samping (Pagination) --}}
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
