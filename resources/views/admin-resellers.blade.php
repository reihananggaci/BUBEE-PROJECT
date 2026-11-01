@extends('layouts.admin')
@section('title','Admin Reseller')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">🏪 Approval Reseller</h1>
<div class="flex gap-1.5 text-[11px] font-bold mt-2 overflow-x-auto pb-1">
@foreach(['pending'=>'Antre','approved'=>'Disetujui','rejected'=>'Ditolak','semua'=>'Semua'] as $k=>$l)
<a href="/admin/reseller?status={{ $k }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($status??'pending')===$k?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}">{{ $l }}</a>
@endforeach
</div>
@foreach($resellers as $u)
@php $kurang = $u->kekuranganReseller(); @endphp
<div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>{{ $u->name }}</b><span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $u->status_reseller==='approved'?'bg-emerald-300':($u->status_reseller==='pending'?'bg-amber-300':'bg-red-300') }}">{{ strtoupper($u->status_reseller) }}</span></div>
<div class="text-xs text-slate-500">{{ $u->email }} • {{ $u->hp }}@if($u->toko_nama)<br>🏪 {{ $u->toko_nama }}@endif<br>{{ $u->alamat }}@if($u->nik)<br>🪪 NIK: {{ $u->nik }}@endif</div>
@if($u->ktp_foto)<div class="text-xs mt-1">📷 <a href="/admin/ktp/{{ $u->id }}" target="_blank" class="text-blue-700 font-bold">Lihat foto KTP</a></div>@endif
<div class="mt-1.5 rounded-2xl bg-slate-50 border border-slate-100 p-2 text-[11px]">
<div class="font-extrabold mb-0.5">📋 Kelengkapan</div>
<div>• Email verifikasi: {{ $u->email_verified_at ? '✅' : '❌' }}</div>
<div>• Nama toko: {{ trim((string) $u->toko_nama) ? '✅' : '❌' }}</div>
<div>• NIK 16 digit: {{ preg_match('/^[0-9]{16}$/', (string) $u->nik) ? '✅' : '❌' }}</div>
<div>• HP: {{ trim((string) $u->hp) ? '✅' : '❌' }}</div>
<div>• Alamat: {{ trim((string) $u->alamat) ? '✅' : '❌' }}</div>
<div>• Foto KTP: {{ $u->ktp_foto ? '✅' : '⚠️ opsional' }}</div>
</div>
@if($u->status_reseller==='pending')
<div class="flex gap-1.5 mt-2">
<form method="POST" action="/admin/reseller/{{ $u->id }}/approve" class="flex-1" onsubmit="return confirm('Setujui {{ $u->name }}{{ $kurang!==[] ? ' MESKIPUN data kurang (akan DITOLAK sistem)' : '' }}?')">@csrf<button class="w-full {{ $kurang!==[] ? 'bg-slate-300 text-slate-600' : 'bg-emerald-500 text-white' }} font-bold py-2.5 rounded-2xl text-xs">✅ Approve{{ $kurang!==[] ? ' (data kurang!)' : '' }}</button></form>
</div>
<form method="POST" action="/admin/reseller/{{ $u->id }}/reject" class="flex gap-1.5 mt-1.5" onsubmit="return confirm('Tolak {{ $u->name }}?')">@csrf
<input name="alasan" placeholder="Alasan tolak * (min 10 huruf)" maxlength="200" class="flex-1 border rounded-2xl px-3 py-2 text-xs" required>
<button class="bg-red-500 text-white font-bold px-4 rounded-2xl text-xs">❌ Tolak</button>
</form>
@elseif($u->status_reseller==='rejected')
<form method="POST" action="/admin/reseller/{{ $u->id }}/review-ulang" class="mt-2">@csrf<button class="w-full bg-amber-100 text-amber-800 font-bold py-2.5 rounded-2xl text-xs">↩️ Kembalikan ke antrean</button></form>
@endif
</div>
@endforeach
@if($resellers->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Tidak ada di tab ini.</div>@endif
@endsection
