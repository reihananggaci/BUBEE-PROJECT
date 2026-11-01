@extends('layouts.web')
@section('title','Pengaturan')
@section('content')
<a href="/akun" class="text-xs font-bold text-blue-700" style="text-decoration:none">← Kembali ke Akun</a>
<h1 class="font-extrabold text-2xl mt-1">⚙️ Pengaturan</h1>
<div class="text-xs text-slate-500 mb-3">Profil, password & notifikasi akunmu.</div>
<div class="grid gap-4 lg:grid-cols-2 lg:items-start">
<form method="POST" action="/akun" class="bg-white rounded-3xl shadow-card p-5 space-y-2 text-sm">@csrf @method('PUT')
<b>👤 Profil</b>
<input name="name" value="{{ $user->name }}" class="w-full border rounded-2xl px-3 py-2" required>
<input name="hp" value="{{ $user->hp }}" placeholder="HP/WA" class="w-full border rounded-2xl px-3 py-2">
<textarea name="alamat" placeholder="Alamat" class="w-full border rounded-2xl px-3 py-2">{{ $user->alamat }}</textarea>
@if($user->role==='reseller')
<input name="toko_nama" value="{{ $user->toko_nama }}" placeholder="Nama tokoku (cth: Berkah Fashion)" maxlength="60" class="w-full border rounded-2xl px-3 py-2">
@endif
<label class="flex items-center gap-2 text-xs bg-emerald-50 border border-emerald-100 rounded-2xl px-3 py-2.5"><input type="checkbox" name="notif_wa" value="1" {{ $user->notif_wa?'checked':'' }}> Notif WA update order & retur</label>
<button class="w-full bg-blue-900 text-white font-bold py-2.5 rounded-2xl">Simpan</button>
</form>
<div class="space-y-4">
<details class="bg-white rounded-3xl shadow-card p-5 text-sm">
<summary class="font-bold cursor-pointer">🔑 Ganti Password</summary>
<form method="POST" action="/akun/password" class="space-y-2 mt-2">@csrf
<input name="password_lama" type="password" placeholder="Password lama *" class="w-full border rounded-2xl px-3 py-2" required>
<input name="password" type="password" placeholder="Password baru (min 8) *" class="w-full border rounded-2xl px-3 py-2" required>
<input name="password_confirmation" type="password" placeholder="Ulangi password baru *" class="w-full border rounded-2xl px-3 py-2" required>
<button class="w-full bg-slate-900 text-white font-bold py-2.5 rounded-2xl">Ganti</button>
</form>
</details>
@if($user->role==='admin')
<div class="bg-white rounded-3xl shadow-card p-5 text-sm font-bold space-y-2">
<a href="/admin/pengaturan" class="block" style="text-decoration:none;color:#0f172a">🏬 Pengaturan Toko →</a>
<a href="/admin/integrasi" class="block" style="text-decoration:none;color:#0f172a">🔌 Status Integrasi →</a>
</div>
@endif
<details class="bg-white rounded-3xl shadow-card p-5 text-sm" style="border-color:#fecaca">
<summary class="font-bold cursor-pointer text-red-600">🗑️ Hapus Akun Saya</summary>
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
