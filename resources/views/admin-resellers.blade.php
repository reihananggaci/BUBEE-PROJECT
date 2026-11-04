@extends('layouts.admin')
@section('title','Admin Reseller')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-cyan-100 text-cyan-800 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5 5.5 5h13L20 9.5"/><path d="M4 9.5h16V19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><path d="M9.5 20v-6h5v6"/></svg>
    </span>
    <span>Approval Reseller</span>
</h1>
<div class="flex gap-1.5 text-[11px] font-bold mt-2 overflow-x-auto pb-1">
@foreach(['pending'=>'Antre','approved'=>'Disetujui','rejected'=>'Ditolak','semua'=>'Semua'] as $k=>$l)
<a href="/admin/reseller?status={{ $k }}" class="px-3 py-1.5 rounded-full whitespace-nowrap {{ ($status??'pending')===$k?'bg-purple-900 text-white':'bg-slate-100 text-slate-600' }}">{{ $l }}</a>
@endforeach
</div>
@foreach($resellers as $u)
@php $kurang = $u->kekuranganReseller(); @endphp
<div class="glass rounded-3xl shadow-card p-4 mb-2 text-sm">
<div class="flex justify-between"><b>{{ $u->name }}</b><span class="text-[10px] font-extrabold px-2 py-1 rounded-full {{ $u->status_reseller==='approved'?'bg-emerald-300':($u->status_reseller==='pending'?'bg-amber-300':'bg-red-300') }}">{{ strtoupper($u->status_reseller) }}</span></div>
<div class="text-xs text-slate-500 mt-1">{{ $u->email }} • {{ $u->hp }}@if($u->toko_nama)<br><span class="font-bold text-slate-700">Toko: {{ $u->toko_nama }}</span>@endif<br>{{ $u->alamat }}@if($u->nik)<br>NIK: {{ $u->nik }}@endif</div>
@if($u->ktp_foto)<div class="text-xs mt-1 flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg><a href="/admin/ktp/{{ $u->id }}" target="_blank" class="text-blue-700 font-bold">Lihat foto KTP</a></div>@endif
<div class="mt-2 rounded-2xl bg-slate-50 border border-slate-100 p-2.5 text-[11px] space-y-0.5">
<div class="font-extrabold text-slate-800 mb-1">Kelengkapan Data:</div>
<div>• Email verifikasi: {!! $u->email_verified_at ? '<span class="text-emerald-700 font-bold">✓ Lengkap</span>' : '<span class="text-rose-600 font-bold">✗ Belum</span>' !!}</div>
<div>• Nama toko: {!! trim((string) $u->toko_nama) ? '<span class="text-emerald-700 font-bold">✓ Ada</span>' : '<span class="text-rose-600 font-bold">✗ Kosong</span>' !!}</div>
<div>• NIK 16 digit: {!! preg_match('/^[0-9]{16}$/', (string) $u->nik) ? '<span class="text-emerald-700 font-bold">✓ Valid (16 digit)</span>' : '<span class="text-rose-600 font-bold">✗ Belum valid</span>' !!}</div>
<div>• HP: {!! trim((string) $u->hp) ? '<span class="text-emerald-700 font-bold">✓ Ada</span>' : '<span class="text-rose-600 font-bold">✗ Kosong</span>' !!}</div>
<div>• Alamat: {!! trim((string) $u->alamat) ? '<span class="text-emerald-700 font-bold">✓ Lengkap</span>' : '<span class="text-rose-600 font-bold">✗ Kosong</span>' !!}</div>
<div>• Foto KTP: {!! $u->ktp_foto ? '<span class="text-emerald-700 font-bold">✓ Terlampir</span>' : '<span class="text-slate-400">Opsional</span>' !!}</div>
</div>
@if($u->status_reseller==='pending')
<div class="flex gap-1.5 mt-2">
<form method="POST" action="/admin/reseller/{{ $u->id }}/approve" class="flex-1" onsubmit="return confirm('Setujui {{ $u->name }}{{ $kurang!==[] ? ' MESKIPUN data kurang (akan DITOLAK sistem)' : '' }}?')">@csrf<button class="w-full {{ $kurang!==[] ? 'bg-slate-300 text-slate-600' : 'bg-emerald-500 hover:bg-emerald-600 text-white' }} font-bold py-2.5 rounded-2xl text-xs transition shadow-sm">Setujui Reseller{{ $kurang!==[] ? ' (data kurang!)' : '' }}</button></form>
</div>
<form method="POST" action="/admin/reseller/{{ $u->id }}/reject" class="flex gap-1.5 mt-1.5" onsubmit="return confirm('Tolak {{ $u->name }}?')">@csrf
<input name="alasan" placeholder="Alasan tolak * (min 10 huruf)" maxlength="200" class="flex-1 border border-slate-200 rounded-2xl px-3 py-2 text-xs" required>
<button class="bg-red-500 hover:bg-red-600 text-white font-bold px-4 rounded-2xl text-xs transition shadow-sm">Tolak</button>
</form>
@elseif($u->status_reseller==='rejected')
<form method="POST" action="/admin/reseller/{{ $u->id }}/review-ulang" class="mt-2">@csrf<button class="w-full bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold py-2.5 rounded-2xl text-xs transition">Kembalikan ke antrean</button></form>
@endif
</div>
@endforeach
@if($resellers->isEmpty())<div class="glass rounded-3xl p-6 text-center text-sm">Tidak ada di tab ini.</div>@endif

{{-- Navigasi Panah Samping (Pagination) --}}
<div class="mt-3">{{ $resellers->links() }}</div>
@endsection
