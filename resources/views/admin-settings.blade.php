@extends('layouts.admin')
@section('title','Pengaturan')
@section('back', '/admin')
@section('content')
<h1 class="font-extrabold text-lg text-slate-900 flex items-center gap-2">
    <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h9"/><circle cx="16.5" cy="7" r="2"/><path d="M20 17H9"/><circle cx="6" cy="17" r="2"/></svg>
    </span>
    <span>Pengaturan Toko</span>
</h1>
<div class="text-xs text-slate-500 mb-2">Khusus admin: target harian, WA admin, nama toko, pengumuman.</div>
<form method="POST" action="/admin/pengaturan" class="glass rounded-[1.75rem] shadow p-5 space-y-2 text-sm">@csrf @method('PUT')
<label class="block">Nama Toko<input name="nama_toko" value="{{ $data['nama_toko'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1 font-bold" required></label>
<label class="block">WA Admin (cth 62812…)<input name="wa_admin" value="{{ $data['wa_admin'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1" required></label>
<label class="block">Target Omzet Harian (Rp)<input name="target_harian" type="number" min="0" value="{{ $data['target_harian'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1 font-bold" required></label>
<label class="block">Pengumuman untuk pembeli<textarea name="pengumuman" rows="2" placeholder="cth: Promo ongkir… (tahap berikutnya tampil di home)" class="w-full border rounded-2xl px-3 py-2.5 mt-1">{{ $data['pengumuman'] }}</textarea></label>
<label class="block">Token Fonnte WA (kosongkan = mode dummy)<input name="fonnte_token" value="{{ $data['fonnte_token'] }}" placeholder="Isi untuk kirim WA beneran" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<div class="rounded-2xl border p-3 space-y-2" style="border-color:#e9d5ff;background:#faf5ff">
<b class="text-sm flex items-center gap-1.5 text-purple-950">
    <svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    <span>Captcha Login (Turnstile)</span>
</b>
<label class="flex items-center gap-2 text-xs font-semibold"><input type="checkbox" name="turnstile_wajib" value="1" {{ ($data['turnstile_wajib'] ?? '0')==='1'?'checked':'' }}> Wajibkan captcha saat login</label>
<label class="flex items-center gap-2 text-xs font-semibold text-purple-900 mt-1">
    <input type="checkbox" name="turnstile_mode_demo" value="1" {{ ($data['turnstile_mode_demo'] ?? '0')==='1'?'checked':'' }}>
    <span>Mode Demo (Aktifkan widget di localhost & link tunnel sementara)</span>
</label>
<div class="text-[11px]" style="color:#64748b">Mode Demo menggunakan kunci uji resmi Cloudflare (Always-Pass) sehingga widget Turnstile tetap muncul dan bekerja di localhost / tunnel tanpa dicekal error domain mismatch 300030. Matikan mode demo dan isi Site/Secret Key di bawah saat website sudah tayang di domain resmi.</div>
<label class="block text-xs">Site Key (Production)<input name="turnstile_site_key" value="{{ $data['turnstile_site_key'] ?? '' }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<label class="block text-xs">Secret Key (Production)<input name="turnstile_secret" value="{{ $data['turnstile_secret'] ?? '' }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
</div>
<label class="block">RajaOngkir API Key (Starter, kosongkan = dummy)<input name="rajaongkir_key" value="{{ $data['rajaongkir_key'] }}" placeholder="Isi untuk ongkir JNE real-time" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<div class="grid grid-cols-2 gap-2">
<label class="block text-sm">Kota Asal ID<input name="rajaongkir_origin_city" value="{{ $data['rajaongkir_origin_city'] }}" placeholder="cth: 23" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<label class="block text-sm">Kota Asal Nama<input name="rajaongkir_origin_name" value="{{ $data['rajaongkir_origin_name'] }}" placeholder="cth: Kota Bandung" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
</div>
<div class="rounded-2xl border p-3 space-y-2" style="border-color:#bae6fd;background:#f0f9ff">
<b class="text-sm text-sky-900 flex items-center gap-1.5">
    <svg class="w-4 h-4 text-sky-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    <span>Rekening Bank Toko (Transfer Manual)</span>
</b>
<div class="text-[11px] text-slate-500">Tampil di halaman pembayaran saat pembeli memilih transfer manual.</div>
<div class="grid grid-cols-2 gap-2">
<label class="block text-xs">Nama Bank<input name="bank_nama" value="{{ $data['bank_nama'] ?? 'BCA' }}" placeholder="cth: BCA / Mandiri / BRI" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
<label class="block text-xs">Nomor Rekening<input name="bank_rekening" value="{{ $data['bank_rekening'] ?? '' }}" placeholder="cth: 1234567890" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
</div>
<label class="block text-xs">Atas Nama Pemilik Rekening<input name="bank_atas_nama" value="{{ $data['bank_atas_nama'] ?? '' }}" placeholder="cth: Toko Bubee / John Doe" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
</div>
<button class="w-full text-white font-extrabold py-3 rounded-2xl shadow-sm flex items-center justify-center gap-2" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
    <span>Simpan Pengaturan</span>
</button>
</form>
<div class="glass rounded-3xl p-4 mt-2 text-xs text-slate-500">Target dipakai di progress bar dashboard. WA dipakai tombol chat. Pengumuman tahap berikutnya bisa ditampilkan di home user.</div>
@endsection
