@extends('layouts.admin')
@section('title','Kelola Users')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg">👥 Kelola Pengguna</h1>
<div class="text-xs text-slate-500 mb-2">Khusus admin: ubah role, reset password (→ bubee123), hapus user. Admin tidak bisa menurunkan/hapus diri sendiri.</div>
<form method="GET" action="/admin/users" class="flex gap-2 mb-2">
<input name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari nama/email…" class="flex-1 border rounded-2xl px-4 py-2 text-sm">
<button class="bg-purple-900 text-white font-bold px-4 rounded-2xl text-sm">Cari</button>
</form>
@foreach($users as $u)
<div class="glass rounded-3xl shadow p-4 mb-2 text-sm">
<div class="flex justify-between items-center"><b>{{ $u->name }}</b>
<span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $u->role==='admin'?'bg-fuchsia-500 text-white':($u->role==='reseller'?'bg-cyan-300':'bg-slate-200') }}">{{ strtoupper(nama_role($u->role)) }}{{ $u->role==='reseller'?' • '.$u->status_reseller:'' }}</span></div>
<div class="text-xs text-slate-500">{{ $u->email }} • {{ $u->hp }} • gabung {{ $u->created_at->format('d M Y') }}</div>
<div class="flex gap-1 mt-2">
<form method="POST" action="/admin/users/{{ $u->id }}/role" class="flex gap-1 flex-1">@csrf @method('PUT')
<select name="role" class="border rounded-xl px-2 py-1.5 text-xs font-bold flex-1">@foreach(['admin','reseller','enduser'] as $r)<option value="{{ $r }}" {{ $u->role===$r?'selected':'' }}>{{ nama_role($r) }}</option>@endforeach</select>
<button class="bg-purple-900 text-white text-xs font-bold px-3 rounded-xl">Ubah</button></form>
<form method="POST" action="/admin/users/{{ $u->id }}/reset">@csrf<button class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-xl">🔑 Reset</button></form>
<form method="POST" action="/admin/users/{{ $u->id }}" onsubmit="return confirm('Hapus {{ $u->name }}?')">@csrf @method('DELETE')<button class="bg-red-50 text-red-600 text-xs font-bold px-3 py-1.5 rounded-xl">🗑️</button></form>
</div>
</div>
@endforeach
<div class="mt-2">{{ $users->links() }}</div>
@endsection
