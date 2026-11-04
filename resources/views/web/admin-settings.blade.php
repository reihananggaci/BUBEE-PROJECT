@extends('layouts.web-admin')
@section('title','Pengaturan')
@section('content')
<h1 class="font-extrabold text-2xl text-slate-900 flex items-center gap-2.5">
    <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-900 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    </span>
    <span>Pengaturan Toko</span>
</h1>
<div class="text-xs text-slate-500 mb-3 mt-0.5">Khusus admin: target harian, WA admin, nama toko, pengumuman.</div>
<div class="grid gap-4 lg:grid-cols-2 lg:items-start">
<form method="POST" action="/admin/pengaturan" class="bg-white rounded-3xl shadow p-5 space-y-2 text-sm">@csrf @method('PUT')
<label class="block">Nama Toko<input name="nama_toko" value="{{ $data['nama_toko'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1 font-bold" required></label>
<label class="block">WA Admin (cth 62812…)<input name="wa_admin" value="{{ $data['wa_admin'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1" required></label>
<label class="block">Target Omzet Harian (Rp)<input name="target_harian" type="number" min="0" value="{{ $data['target_harian'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1 font-bold" required></label>
<label class="block">Pengumuman untuk pembeli<textarea name="pengumuman" rows="2" placeholder="cth: Promo ongkir… (tahap berikutnya tampil di home)" class="w-full border rounded-2xl px-3 py-2.5 mt-1">{{ $data['pengumuman'] }}</textarea></label>
<label class="block">Token Fonnte WA (kosongkan = mode dummy)<input name="fonnte_token" value="{{ $data['fonnte_token'] }}" placeholder="Isi untuk kirim WA beneran" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<div class="rounded-2xl border p-3 space-y-2" style="border-color:#e9d5ff;background:#faf5ff">
<b class="text-sm flex items-center gap-1.5"><svg class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> Captcha Login (Turnstile)</b>
<label class="flex items-center gap-2 text-xs font-semibold"><input type="checkbox" name="turnstile_wajib" value="1" {{ ($data['turnstile_wajib'] ?? '0')==='1'?'checked':'' }}> Wajibkan captcha saat login</label>
<label class="flex items-center gap-2 text-xs font-semibold text-purple-900 mt-1"><input type="checkbox" name="turnstile_mode_demo" value="1" {{ ($data['turnstile_mode_demo'] ?? '0')==='1'?'checked':'' }}> Mode Demo (Aktifkan widget di localhost & link tunnel sementara)</label>
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
<b class="text-sm text-sky-900 flex items-center gap-1.5"><svg class="w-4 h-4 text-sky-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg> Rekening Bank Toko (Transfer Manual)</b>
<div class="text-[11px] text-slate-500">Tampil di halaman pembayaran saat pembeli memilih transfer manual.</div>
<div class="grid grid-cols-2 gap-2">
<label class="block text-xs">Nama Bank<input name="bank_nama" value="{{ $data['bank_nama'] ?? 'BCA' }}" placeholder="cth: BCA / Mandiri / BRI" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
<label class="block text-xs">Nomor Rekening<input name="bank_rekening" value="{{ $data['bank_rekening'] ?? '' }}" placeholder="cth: 1234567890" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
</div>
<label class="block text-xs">Atas Nama Pemilik Rekening<input name="bank_atas_nama" value="{{ $data['bank_atas_nama'] ?? '' }}" placeholder="cth: Toko Bubee / John Doe" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
</div>
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">SIMPAN PENGATURAN</button>
</form>
<div class="space-y-2 self-start lg:sticky lg:top-20">
<div class="bg-white rounded-3xl p-4 text-xs text-slate-500">Target dipakai di progress bar dashboard. WA dipakai tombol chat. Pengumuman tahap berikutnya bisa ditampilkan di home user.</div>
</div>
</div>
@endsection
