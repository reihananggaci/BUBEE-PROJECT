@extends('layouts.web-admin')
@section('title','Pengaturan')
@section('content')
<h1 class="font-extrabold text-2xl">⚙️ Pengaturan Toko</h1>
<div class="text-xs text-slate-500 mb-3">Khusus admin: target harian, WA admin, nama toko, pengumuman.</div>
<div class="grid gap-4 lg:grid-cols-2 lg:items-start">
<form method="POST" action="/admin/pengaturan" class="bg-white rounded-3xl shadow p-5 space-y-2 text-sm">@csrf @method('PUT')
<label class="block">Nama Toko<input name="nama_toko" value="{{ $data['nama_toko'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1 font-bold" required></label>
<label class="block">WA Admin (cth 62812…)<input name="wa_admin" value="{{ $data['wa_admin'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1" required></label>
<label class="block">Target Omzet Harian (Rp)<input name="target_harian" type="number" min="0" value="{{ $data['target_harian'] }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1 font-bold" required></label>
<label class="block">Pengumuman untuk pembeli<textarea name="pengumuman" rows="2" placeholder="cth: Promo ongkir… (tahap berikutnya tampil di home)" class="w-full border rounded-2xl px-3 py-2.5 mt-1">{{ $data['pengumuman'] }}</textarea></label>
<label class="block">Token Fonnte WA (kosongkan = mode dummy)<input name="fonnte_token" value="{{ $data['fonnte_token'] }}" placeholder="Isi untuk kirim WA beneran" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<div class="rounded-2xl border p-3 space-y-2" style="border-color:#e9d5ff;background:#faf5ff">
<b class="text-sm">🛡️ Captcha Login (Turnstile)</b>
<label class="flex items-center gap-2 text-xs"><input type="checkbox" name="turnstile_wajib" value="1" {{ ($data['turnstile_wajib'] ?? '0')==='1'?'checked':'' }}> Wajibkan captcha saat login</label>
<div class="text-[11px]" style="color:#64748b">Matikan bila login via link tunnel (domain berubah-ubah). Nyalakan di domain production + daftarkan domainnya di dashboard Cloudflare.</div>
<label class="block text-xs">Site Key<input name="turnstile_site_key" value="{{ $data['turnstile_site_key'] ?? '' }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<label class="block text-xs">Secret Key<input name="turnstile_secret" value="{{ $data['turnstile_secret'] ?? '' }}" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
</div>
<label class="block">RajaOngkir API Key (Starter, kosongkan = dummy)<input name="rajaongkir_key" value="{{ $data['rajaongkir_key'] }}" placeholder="Isi untuk ongkir JNE real-time" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<div class="grid grid-cols-2 gap-2">
<label class="block text-sm">Kota Asal ID<input name="rajaongkir_origin_city" value="{{ $data['rajaongkir_origin_city'] }}" placeholder="cth: 23" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
<label class="block text-sm">Kota Asal Nama<input name="rajaongkir_origin_name" value="{{ $data['rajaongkir_origin_name'] }}" placeholder="cth: Kota Bandung" class="w-full border rounded-2xl px-3 py-2.5 mt-1"></label>
</div>
<div class="rounded-2xl border p-3 space-y-2" style="border-color:#bae6fd;background:#f0f9ff">
<b class="text-sm text-sky-900">🏦 Rekening Bank Toko (Transfer Manual)</b>
<div class="text-[11px] text-slate-500">Tampil di halaman pembayaran saat pembeli memilih transfer manual.</div>
<div class="grid grid-cols-2 gap-2">
<label class="block text-xs">Nama Bank<input name="bank_nama" value="{{ $data['bank_nama'] ?? 'BCA' }}" placeholder="cth: BCA / Mandiri / BRI" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
<label class="block text-xs">Nomor Rekening<input name="bank_rekening" value="{{ $data['bank_rekening'] ?? '' }}" placeholder="cth: 1234567890" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
</div>
<label class="block text-xs">Atas Nama Pemilik Rekening<input name="bank_atas_nama" value="{{ $data['bank_atas_nama'] ?? '' }}" placeholder="cth: Toko Bubee / John Doe" class="w-full border rounded-2xl px-3 py-2 mt-1 font-bold"></label>
</div>
<button class="w-full text-white font-extrabold py-3 rounded-2xl" style="background:linear-gradient(90deg,#4c1d95,#ec4899)">💾 SIMPAN PENGATURAN</button>
</form>
<div class="space-y-2 self-start lg:sticky lg:top-20">
<div class="bg-white rounded-3xl p-4 text-xs text-slate-500">Target dipakai di progress bar dashboard. WA dipakai tombol chat. Pengumuman tahap berikutnya bisa ditampilkan di home user.</div>
</div>
</div>
@endsection
