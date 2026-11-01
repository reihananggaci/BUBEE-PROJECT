@extends(auth()->check() && auth()->user()->role==='admin' ? 'layouts.web-admin' : 'layouts.web')
@section('title','Retur #'.$retur->id)
@section('content')
<a href="{{ auth()->check() && auth()->user()->role==='admin' ? '/admin/retur' : '/retur' }}" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali</a>
<h1 class="font-extrabold text-2xl mt-1">🔄 Retur #{{ $retur->id }}</h1>
<div class="text-xs text-slate-500">Order {{ $retur->order->kode ?? '' }} • {{ $retur->user->name ?? '' }} • Status <b>{{ strtoupper($retur->status) }}</b></div>
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
@if($retur->foto)<div>📎 <a href="/storage/{{ $retur->foto }}" target="_blank" class="text-blue-700 font-bold">Lihat foto</a></div>@endif
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
<a href="https://wa.me/{{ \App\Models\Setting::get('wa_admin','6281234567890') }}?text={{ urlencode('Halo Bubee, retur #'.$retur->id) }}" target="_blank" class="block text-center text-sm font-bold text-emerald-700 border border-slate-100 rounded-2xl py-2.5 mt-2" style="text-decoration:none">💬 Chat Admin</a>
@endif
</div>
</div>
@endsection
