@extends(auth()->check() && auth()->user()->role==='admin' ? 'layouts.web-admin' : 'layouts.web')
@section('title','Retur #'.$retur->id)
@section('content')
<a href="{{ auth()->check() && auth()->user()->role==='admin' ? '/admin/retur' : '/retur' }}" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali</a>
<h1 class="font-extrabold text-2xl text-slate-900 mt-2 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V5.5A1.5 1.5 0 0 1 5.5 4H9"/><path d="M4 5.5A9 9 0 1 1 3 13"/><path d="M3 13h5"/></svg>
    </span>
    <span>Retur #{{ $retur->id }}</span>
</h1>
<div class="text-xs text-slate-500">{{ $order->kode ?? '' }} • {{ $retur->user->name ?? '' }} • Status <b>{{ strtoupper($retur->status) }}</b></div>
<div class="grid gap-4 mt-3 lg:grid-cols-3 lg:items-start">
<div class="lg:col-span-2 bg-white rounded-3xl shadow-card p-5 text-sm space-y-1">
<div class="hidden lg:block overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="py-1">Barang</th><th class="py-1">Varian</th><th class="py-1 text-center">Qty</th></tr></thead>
<tbody>
@foreach($retur->items as $ri)<tr class="border-t border-slate-100"><td class="py-1">{{ $ri->orderItem->product->nama ?? '' }}</td><td class="py-1">{{ $ri->orderItem->variant?->label() }}</td><td class="py-1 text-center">×{{ $ri->qty }}</td></tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden">@foreach($retur->items as $ri)<div>- {{ $ri->orderItem->product->nama ?? '' }} ({{ $ri->orderItem->variant?->label() }}) ×{{ $ri->qty }}</div>@endforeach</div>
<div><b>Alasan:</b> {{ $retur->alasan }}</div>
@if($retur->foto)<div class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l7.88-7.88"/></svg><a href="/storage/{{ $retur->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto</a></div>@endif
@if($retur->catatan_admin)<div class="bg-purple-50 rounded-2xl p-2.5 text-xs"><b>Catatan admin:</b> {{ $retur->catatan_admin }}</div>@endif
</div>
<div class="lg:sticky lg:top-20 self-start bg-white rounded-3xl shadow-card p-5 text-sm">
@if(auth()->check() && auth()->user()->role==='admin')
<div class="font-extrabold">Update Retur</div>
<form method="POST" action="/admin/retur/{{ $retur->id }}" class="space-y-2 mt-2">@csrf @method('PUT')
<select name="status" class="w-full border rounded-2xl px-3 py-2 text-sm font-bold">@foreach(['diajukan','disetujui','ditolak','selesai'] as $s)<option value="{{ $s }}" {{ $retur->status===$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
<input name="catatan_admin" value="{{ $retur->catatan_admin }}" placeholder="Catatan admin…" class="w-full border rounded-2xl px-3 py-2 text-sm">
<button class="w-full bg-purple-900 text-white font-bold py-2.5 rounded-2xl text-sm">Simpan</button>
</form>
@else
<a href="/retur" class="block text-center text-sm font-bold text-blue-700" style="text-decoration:none">← Semua retur saya</a>
<a href="https://wa.me/{{ \App\Models\Setting::get('wa_admin','6281234567890') }}?text={{ urlencode('Halo Bubee, retur #'.$retur->id) }}" target="_blank" class="flex items-center justify-center gap-2 text-sm font-bold text-emerald-700 border border-slate-100 rounded-2xl py-2.5 mt-2" style="text-decoration:none">
    <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <span>Chat Admin</span>
</a>
@endif
</div>
</div>
@endsection
