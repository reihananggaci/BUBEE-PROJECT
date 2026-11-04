@extends('layouts.admin')
@section('title','Kelola Users')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8.5" r="3"/><path d="M2.8 19.5c1.2-3 3.4-4.5 6.2-4.5s5 1.5 6.2 4.5"/><circle cx="16.8" cy="9.5" r="2.4"/><path d="M16 15c2.3.2 4 1.5 5 4"/></svg>
    </span>
    <span>Kelola Pengguna</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Khusus admin: ubah role, reset password (→ bubee123), hapus user. Admin tidak bisa menurunkan/hapus diri sendiri.</div>
<form method="GET" action="/admin/users" class="flex gap-2 mb-2">
<input name="q" value="{{ $q ?? '' }}" placeholder="Cari nama/email…" class="flex-1 border rounded-2xl px-4 py-2 text-sm bg-white">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm shadow-sm">Cari</button>
</form>
@foreach($users as $u)
<div class="glass rounded-3xl shadow p-4 mb-2 text-sm">
<div class="flex justify-between items-center"><b>{{ $u->name }}</b>
<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $u->role==='admin'?'bg-fuchsia-500 text-white':($u->role==='reseller'?'bg-cyan-300':'bg-slate-200') }}">{{ strtoupper(nama_role($u->role)) }}{{ $u->role==='reseller'?' • '.$u->status_reseller:'' }}</span></div>
<div class="text-xs text-slate-500">{{ $u->email }} • {{ $u->hp }} • gabung {{ $u->created_at->format('d M Y') }}</div>
<div class="flex gap-1 mt-2">
<form method="POST" action="/admin/users/{{ $u->id }}/role" class="flex gap-1 flex-1">@csrf @method('PUT')
<select name="role" class="border rounded-xl px-2 py-1.5 text-xs font-bold flex-1">@foreach(['admin','reseller','enduser'] as $r)<option value="{{ $r }}" {{ $u->role===$r?'selected':'' }}>{{ nama_role($r) }}</option>@endforeach</select>
<button class="bg-purple-900 text-white text-xs font-bold px-3 rounded-xl shadow-sm">Ubah</button></form>
<form method="POST" action="/admin/users/{{ $u->id }}/reset">@csrf<button class="bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-bold px-3 py-1.5 rounded-xl transition">Reset</button></form>
<form method="POST" action="/admin/users/{{ $u->id }}" onsubmit="return confirm('Hapus {{ $u->name }}?')">@csrf @method('DELETE')<button class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold px-2.5 py-1.5 rounded-xl transition flex items-center justify-center" title="Hapus"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></form>
</div>
</div>
@endforeach
<div class="mt-2">{{ $users->links() }}</div>
@endsection
