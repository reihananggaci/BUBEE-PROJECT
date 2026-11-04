@extends('layouts.web')
@section('title','Pengaturan')
@section('content')
<a href="/akun" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali ke Akun</a>
<h1 class="font-extrabold text-2xl text-slate-900 mt-2 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
    </span>
    <span>Pengaturan Akun</span>
</h1>
<div class="text-xs text-slate-500 mb-3">Profil, password & notifikasi akunmu.</div>
<div class="grid gap-4 lg:grid-cols-2 lg:items-start">
<form method="POST" action="/akun" class="bg-white rounded-3xl shadow-card p-5 space-y-2 text-sm border border-slate-100">@csrf @method('PUT')
<b class="flex items-center gap-1.5 text-slate-900 mb-1">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>
    <span>Profil Pengguna</span>
</b>
<input name="name" value="{{ $user->name }}" class="w-full border rounded-2xl px-3 py-2" required>
<input name="hp" value="{{ $user->hp }}" placeholder="HP/WA" class="w-full border rounded-2xl px-3 py-2">
<textarea name="alamat" placeholder="Alamat" class="w-full border rounded-2xl px-3 py-2">{{ $user->alamat }}</textarea>
@if($user->role==='reseller')
<input name="toko_nama" value="{{ $user->toko_nama }}" placeholder="Nama tokoku (cth: Berkah Fashion)" maxlength="60" class="w-full border rounded-2xl px-3 py-2">
@endif
<label class="flex items-center gap-2 text-xs bg-emerald-50 border border-emerald-100 rounded-2xl px-3 py-2.5"><input type="checkbox" name="notif_wa" value="1" {{ $user->notif_wa?'checked':'' }}> Notif WA update order & retur</label>
<button class="w-full bg-blue-900 text-white font-bold py-2.5 rounded-2xl shadow-sm hover:bg-blue-800 transition">Simpan</button>
</form>
<div class="space-y-4">
<details class="bg-white rounded-3xl shadow-card p-5 text-sm border border-slate-100">
<summary class="font-bold cursor-pointer flex items-center gap-1.5 text-slate-800">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/></svg>
    <span>Ganti Password</span>
</summary>
<form method="POST" action="/akun/password" class="space-y-2 mt-2">@csrf
<input name="password_lama" type="password" placeholder="Password lama *" class="w-full border rounded-2xl px-3 py-2" required>
<input name="password" type="password" placeholder="Password baru (min 8) *" class="w-full border rounded-2xl px-3 py-2" required>
<input name="password_confirmation" type="password" placeholder="Ulangi password baru *" class="w-full border rounded-2xl px-3 py-2" required>
<button class="w-full bg-slate-900 text-white font-bold py-2.5 rounded-2xl shadow-sm hover:bg-slate-800 transition">Ganti Password</button>
</form>
</details>
@if($user->role==='admin')
<div class="bg-white rounded-3xl shadow-card p-5 text-sm font-bold space-y-2.5 border border-slate-100">
<a href="/admin/pengaturan" class="flex items-center gap-2 text-slate-800 hover:text-purple-700 transition" style="text-decoration:none">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h9"/><circle cx="16.5" cy="7" r="2"/><path d="M20 17H9"/><circle cx="6" cy="17" r="2"/></svg>
    <span>Pengaturan Toko →</span>
</a>
<a href="/admin/integrasi" class="flex items-center gap-2 text-slate-800 hover:text-purple-700 transition" style="text-decoration:none">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v4"/><path d="M15 3h4a2 2 0 0 1 2 2v4"/><path d="M9 21H5a2 2 0 0 1-2-2v-4"/><path d="M15 21h4a2 2 0 0 0 2-2v-4"/><circle cx="12" cy="12" r="3"/></svg>
    <span>Status Integrasi →</span>
</a>
</div>
@endif
<details class="bg-white rounded-3xl shadow-card p-5 text-sm border border-red-100">
<summary class="font-bold cursor-pointer text-red-600 flex items-center gap-1.5">
    <svg class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
    <span>Hapus Akun Saya</span>
</summary>
<div class="text-xs mt-1" style="color:#64748b">Riwayat order tetap tersimpan anonim. Tidak bisa dibatalkan. Ditolak jika ada order aktif.</div>
<form method="POST" action="/akun" class="space-y-2 mt-2" onsubmit="return confirm('Yakin hapus akun permanen?')">@csrf @method('DELETE')
<input name="konfirmasi" placeholder='Ketik HAPUS untuk setuju' class="w-full border rounded-2xl px-3 py-2" required>
<input name="password" type="password" placeholder="Password *" class="w-full border rounded-2xl px-3 py-2" required>
<button class="w-full bg-red-600 text-white font-bold py-2.5 rounded-2xl">Hapus Permanen</button>
</form>
</details>
</div>
</div>
@endsection
