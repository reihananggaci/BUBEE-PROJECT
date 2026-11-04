@extends('layouts.web-admin')
@section('title','Admin Reseller')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </span>
    <span>Approval Reseller</span>
</h1>
<div class="text-xs text-slate-500 mb-3 mt-0.5">{{ $resellers->total() }} pendaftar • setujui / tolak dari tabel</div>
<div class="flex gap-1.5 text-[12px] font-bold mb-3">
@foreach(['pending'=>'Antre','approved'=>'Disetujui','rejected'=>'Ditolak','semua'=>'Semua'] as $k=>$l)
<a href="/admin/reseller?status={{ $k }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($status??'pending')===$k?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}" style="text-decoration:none">{{ $l }}</a>
@endforeach
</div>
<div class="hidden lg:block bg-white rounded-3xl shadow-card overflow-x-auto">
<table class="w-full text-sm lg:table">
<thead><tr class="text-left text-[11px] text-slate-400"><th class="px-4 py-2">Nama / Toko</th><th class="px-4 py-2">Kontak</th><th class="px-4 py-2">Data Pribadi</th><th class="px-4 py-2">Kelengkapan</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Aksi</th></tr></thead>
<tbody>
@foreach($resellers as $u)<tr class="border-t border-slate-100">
<td class="px-4 py-2 font-bold">{{ $u->name }}@if($u->toko_nama)<br><span class="font-normal text-xs text-slate-500">Toko: {{ $u->toko_nama }}</span>@endif</td>
<td class="px-4 py-2 text-xs">{{ $u->email }} • {{ $u->hp }}@if($u->ktp_foto)<br><a href="/admin/ktp/{{ $u->id }}" target="_blank" class="text-blue-700 font-bold inline-flex items-center gap-1"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg> KTP</a>@endif</td>
<td class="px-4 py-2 text-xs">{{ $u->alamat }}@if($u->nik)<br>NIK: {{ $u->nik }}@endif</td>
<td class="px-4 py-2 text-[11px]">@php $kurang = $u->kekuranganReseller(); @endphp@if($kurang===[])<span class="font-bold text-emerald-600">Lengkap</span>@else<span class="font-bold text-red-500">Kurang: {{ implode(', ', $kurang) }}</span>@endif</td>
<td class="px-4 py-2"><span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $u->status_reseller==='approved'?'bg-emerald-300':($u->status_reseller==='pending'?'bg-amber-300':'bg-red-300') }}">{{ strtoupper($u->status_reseller) }}</span></td>
<td class="px-4 py-2"><div class="flex flex-col gap-1.5">
@if($u->status_reseller==='pending')
<form method="POST" action="/admin/reseller/{{ $u->id }}/approve" onsubmit="return confirm('Setujui {{ $u->name }}?')">@csrf<button class="w-full {{ $kurang!==[] ? 'bg-slate-200 text-slate-500' : 'bg-emerald-500 text-white' }} font-bold py-1.5 px-4 rounded-2xl text-xs">Approve</button></form>
<form method="POST" action="/admin/reseller/{{ $u->id }}/reject" class="flex gap-1" onsubmit="return confirm('Tolak {{ $u->name }}?')">@csrf<input name="alasan" placeholder="Alasan * min 10" maxlength="200" class="flex-1 border rounded-xl px-2 py-1 text-xs" required><button class="bg-red-500 text-white font-bold py-1.5 px-3 rounded-2xl text-xs">Tolak</button></form>
@elseif($u->status_reseller==='rejected')
<form method="POST" action="/admin/reseller/{{ $u->id }}/review-ulang">@csrf<button class="w-full bg-amber-100 text-amber-800 font-bold py-1.5 px-4 rounded-2xl text-xs">Antrekan lagi</button></form>
@else
<span class="text-xs text-slate-400">—</span>
@endif
</div></td>
</tr>@endforeach
</tbody>
</table>
</div>
<div class="lg:hidden mt-3">
@foreach($resellers as $u)
<div class="bg-white rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>{{ $u->name }}</b><span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $u->status_reseller==='approved'?'bg-emerald-300':($u->status_reseller==='pending'?'bg-amber-300':'bg-red-300') }}">{{ strtoupper($u->status_reseller) }}</span></div>
<div class="text-xs text-slate-500">{{ $u->email }} • {{ $u->hp }}@if($u->toko_nama)<br>Toko: {{ $u->toko_nama }}@endif<br>{{ $u->alamat }}@if($u->nik)<br>NIK: {{ $u->nik }}@endif</div>
@if($u->ktp_foto)<div class="text-xs mt-1"><a href="/admin/ktp/{{ $u->id }}" target="_blank" class="text-blue-700 font-bold inline-flex items-center gap-1"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg> Lihat foto KTP</a></div>@endif
@php $kurangM = $u->kekuranganReseller(); @endphp
<div class="text-[11px] mt-1">@if($kurangM===[])<span class="font-bold text-emerald-600">Data lengkap</span>@else<span class="font-bold text-red-500">Kurang: {{ implode(', ', $kurangM) }}</span>@endif</div>
@if($u->status_reseller==='pending')
<div class="flex gap-1.5 mt-2">
<form method="POST" action="/admin/reseller/{{ $u->id }}/approve" class="flex-1" onsubmit="return confirm('Setujui {{ $u->name }}?')">@csrf<button class="w-full {{ $kurangM!==[] ? 'bg-slate-200 text-slate-500' : 'bg-emerald-500 text-white' }} font-bold py-2.5 rounded-2xl text-xs">Approve</button></form>
</div>
<form method="POST" action="/admin/reseller/{{ $u->id }}/reject" class="flex gap-1.5 mt-1.5" onsubmit="return confirm('Tolak {{ $u->name }}?')">@csrf
<input name="alasan" placeholder="Alasan tolak * (min 10 huruf)" maxlength="200" class="flex-1 border rounded-2xl px-3 py-2 text-xs" required>
<button class="bg-red-500 text-white font-bold px-4 rounded-2xl text-xs">Tolak</button>
</form>
@elseif($u->status_reseller==='rejected')
<form method="POST" action="/admin/reseller/{{ $u->id }}/review-ulang" class="mt-2">@csrf<button class="w-full bg-amber-100 text-amber-800 font-bold py-2.5 rounded-2xl text-xs">↩️ Kembalikan ke antrean</button></form>
@endif
</div>
@endforeach
</div>
@if($resellers->isEmpty())<div class="bg-white rounded-3xl p-6 text-center text-sm mt-3">Belum ada reseller daftar.</div>@endif

{{-- Navigasi Panah Samping (Pagination) --}}
<div class="mt-4">{{ $resellers->links() }}</div>
@endsection
