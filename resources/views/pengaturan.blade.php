@extends('layouts.app')
@section('title','Pengaturan')
@section('back', '/akun')
@section('content')
<div class="flex items-center gap-2 mb-1">
    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    </div>
    <h1 class="font-extrabold text-lg text-slate-900">Pengaturan Akun</h1>
</div>
<div class="text-xs text-slate-500 mb-2">Profil, password & notifikasi akunmu.</div>
<form method="POST" action="/akun" class="glass rounded-3xl shadow-card p-4 mt-2 space-y-2 text-sm">@csrf @method('PUT')
<div class="flex items-center gap-1.5 font-bold text-slate-900">
    <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    <span>Profil</span>
</div>
<input name="name" value="{{ $user->name }}" class="w-full border rounded-2xl px-3 py-2" required>
<input name="hp" value="{{ $user->hp }}" placeholder="HP/WA" class="w-full border rounded-2xl px-3 py-2">
<textarea name="alamat" placeholder="Alamat" class="w-full border rounded-2xl px-3 py-2">{{ $user->alamat }}</textarea>
@if($user->role==='reseller')
<input name="toko_nama" value="{{ $user->toko_nama }}" placeholder="Nama tokoku (cth: Berkah Fashion)" maxlength="60" class="w-full border rounded-2xl px-3 py-2">
@endif
<label class="flex items-center gap-2 text-xs bg-emerald-50 border border-emerald-100 rounded-2xl px-3 py-2.5"><input type="checkbox" name="notif_wa" value="1" {{ $user->notif_wa?'checked':'' }}> Notif WA update order & retur</label>
<button class="w-full bg-blue-900 text-white font-bold py-2.5 rounded-2xl">Simpan</button>
</form>
<details class="glass rounded-3xl shadow-card p-4 mt-2 text-sm">
<summary class="font-bold cursor-pointer inline-flex items-center gap-1.5">
    <svg class="w-4 h-4 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    <span>Ganti Password</span>
</summary>
<form method="POST" action="/akun/password" class="space-y-2 mt-2">@csrf
<input name="password_lama" type="password" placeholder="Password lama *" class="w-full border rounded-2xl px-3 py-2" required>
<input name="password" type="password" placeholder="Password baru (min 8) *" class="w-full border rounded-2xl px-3 py-2" required>
<input name="password_confirmation" type="password" placeholder="Ulangi password baru *" class="w-full border rounded-2xl px-3 py-2" required>
<button class="w-full bg-slate-900 text-white font-bold py-2.5 rounded-2xl">Ganti</button>
</form>
</details>
@if($user->role==='admin')
<a href="/admin/pengaturan" class="flex items-center justify-between glass rounded-3xl shadow-card p-4 mt-2 text-sm font-bold text-slate-900">
    <span class="inline-flex items-center gap-2"><svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Pengaturan Toko</span>
    <span class="text-slate-400">→</span>
</a>
<a href="/admin/integrasi" class="flex items-center justify-between glass rounded-3xl shadow-card p-4 mt-2 text-sm font-bold text-slate-900">
    <span class="inline-flex items-center gap-2"><svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v4"/><path d="M15 3h4a2 2 0 0 1 2 2v4"/><path d="M9 21H5a2 2 0 0 1-2-2v-4"/><path d="M15 21h4a2 2 0 0 0 2-2v-4"/><circle cx="12" cy="12" r="3"/></svg>Status Integrasi</span>
    <span class="text-slate-400">→</span>
</a>
@endif
<details class="glass rounded-3xl shadow-card p-4 mt-2 text-sm" style="border-color:#fecaca">
<summary class="font-bold cursor-pointer text-red-600 inline-flex items-center gap-1.5">
    <svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
    <span>Hapus Akun Saya</span>
</summary>
<div class="text-xs mt-1" style="color:#64748b">Riwayat order tetap tersimpan anonim. Tidak bisa dibatalkan. Ditolak jika ada order aktif.</div>
<form method="POST" action="/akun" class="space-y-2 mt-2" onsubmit="return confirm('Yakin hapus akun permanen?')">@csrf @method('DELETE')
<input name="konfirmasi" placeholder='Ketik HAPUS untuk setuju' class="w-full border rounded-2xl px-3 py-2" required>
<input name="password" type="password" placeholder="Password *" class="w-full border rounded-2xl px-3 py-2" required>
<button class="w-full bg-red-600 text-white font-bold py-2.5 rounded-2xl">Hapus Permanen</button>
</form>
</details>
@endsection
