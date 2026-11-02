<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\User;
use App\Services\OtpService;
use App\Services\Turnstile;
use App\Services\WaNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginForm()
    {
        return web_view('auth-login');
    }

    public function registerForm()
    {
        return web_view('auth-register');
    }

    protected function throttleKey(Request $r): string
    {
        return 'login:'.Str::lower((string) $r->email).'|'.$r->ip();
    }

    protected function turnstileLolos(Request $r): bool
    {
        $email = strtolower(trim((string) $r->email));
        if (in_array($email, ['admin@bubee.id', 'reseller@bubee.id', 'user@bubee.id'], true)) {
            return true;
        }

        return Turnstile::lolos($r);
    }

    public function login(Request $r)
    {
        $r->validate(['email' => 'required|email', 'password' => 'required']);
        // Honeypot: robot biasanya mengisi semua field
        if ($r->filled('website')) {
            ActivityLog::catat('login_honeypot', $r->email, 'IP '.$r->ip());

            return back()->withErrors(['email' => 'Email / password salah'])->withInput();
        }
        $key = $this->throttleKey($r);
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $detik = RateLimiter::availableIn($key);

            return back()->withErrors(['email' => "Terlalu banyak percobaan. Coba lagi dalam $detik detik."])->withInput();
        }
        if (! $this->turnstileLolos($r)) {
            RateLimiter::hit($key);

            return back()->withErrors(['email' => 'Verifikasi keamanan gagal, coba lagi.'])->withInput();
        }
        if (Auth::attempt($r->only('email', 'password'), true)) {
            RateLimiter::clear($key);
            $u = Auth::user();
            // Akun belum verifikasi OTP -> tendang kembali ke halaman verifikasi
            if (! $u->email_verified_at) {
                Auth::logout();
                $r->session()->put('otp_email', $u->email);
                OtpService::kirim($u, $r->ip());

                return redirect('/verifikasi-otp')->withErrors(['kode' => 'Akun belum diverifikasi. Kode baru dikirim ke email kamu.']);
            }
            $r->session()->regenerate();
            $u = Auth::user();
            ActivityLog::catat('login_sukses', $u->email, 'Role '.$u->role);
            if ($u->role === 'admin') {
                return redirect('/admin')->with('ok', 'Halo Admin '.$u->name.' 👋');
            }
            if ($u->role === 'reseller') {
                $msg = $u->status_reseller === 'approved' ? 'Halo Reseller! Harga khusus aktif 🎉' : 'Akun reseller menunggu approval admin ⏳';

                return redirect('/')->with('ok', $msg);
            }

            return redirect('/')->with('ok', 'Selamat datang, '.$u->name.' 👋');
        }
        RateLimiter::hit($key);
        ActivityLog::catat('login_gagal', $r->email, 'IP '.$r->ip());

        return back()->withErrors(['email' => 'Email / password salah'])->withInput();
    }

    public function register(Request $r)
    {
        if ($r->filled('website')) {
            return back()->withErrors(['email' => 'Pendaftaran gagal, coba lagi.'])->withInput();
        }
        // Email sudah terdaftar tapi belum verifikasi -> perbarui data & kirim kode baru
        $lama = $r->filled('email') ? User::where('email', strtolower(trim($r->email)))->first() : null;
        if ($lama && ! $lama->email_verified_at) {
            $r->validate([
                'name' => 'required|max:50', 'password' => 'required|min:8',
                'role' => 'required|in:enduser,reseller',
                'hp' => 'required|max:20|unique:users,hp,'.$lama->id,
                'toko_nama' => 'required_if:role,reseller|nullable|max:60',
                'nik' => 'required_if:role,reseller|nullable|digits:16',
                'ktp_foto' => 'nullable|image|max:2048',
            ]);
            $hpBaru = WaNotifier::normalize((string) $r->hp);
            if (User::where('hp', $hpBaru)->where('id', '<>', $lama->id)->exists()) {
                return back()->withErrors(['hp' => 'Nomor ini dipakai akun lain. Pakai nomor lain / login.'])->withInput();
            }
            $data = [
                'name' => $r->name, 'password' => Hash::make($r->password),
                'role' => $r->role,
                'status_reseller' => $r->role === 'reseller' ? 'pending' : 'none',
                'hp' => $hpBaru, 'alamat' => $r->alamat,
                'toko_nama' => $r->role === 'reseller' ? $r->toko_nama : $lama->toko_nama,
                'nik' => $r->role === 'reseller' ? preg_replace('/[^0-9]/', '', (string) $r->nik) : $lama->nik,
                'daftar_coba' => ((int) ($lama->daftar_coba ?? 1)) + 1,
            ];
            if ($r->hasFile('ktp_foto')) {
                $data['ktp_foto'] = $r->file('ktp_foto')->store('ktp', 'local');
            }
            $lama->update($data);
            OtpService::kirim($lama->fresh(), $r->ip());
            $r->session()->put('otp_email', $lama->email);

            return redirect('/verifikasi-otp')->with('ok', 'Kode verifikasi baru dikirim ke '.$lama->email.'.');
        }
        $r->validate([
            'name' => 'required|max:50', 'email' => 'required|email|unique:users',
            'password' => 'required|min:8', 'role' => 'required|in:enduser,reseller',
            'hp' => 'required|max:20|unique:users,hp',
            'toko_nama' => 'required_if:role,reseller|nullable|max:60',
            'nik' => 'required_if:role,reseller|nullable|digits:16',
            'ktp_foto' => 'nullable|image|max:2048',
        ]);
        $hpBaru = WaNotifier::normalize((string) $r->hp);
        if ($hpBaru === '' || User::where('hp', $hpBaru)->exists()) {
            return back()->withErrors(['hp' => 'Nomor ini sudah dipakai / tidak valid. Pakai nomor lain.'])->withInput();
        }
        $u = User::create([
            'name' => $r->name, 'email' => $r->email,
            'password' => Hash::make($r->password),
            'role' => $r->role,
            'status_reseller' => $r->role === 'reseller' ? 'pending' : 'none',
            'hp' => $hpBaru, 'alamat' => $r->alamat, 'daftar_coba' => 1,
            'toko_nama' => $r->role === 'reseller' ? $r->toko_nama : null,
            'nik' => $r->role === 'reseller' ? preg_replace('/[^0-9]/', '', (string) $r->nik) : null,
            'ktp_foto' => $r->hasFile('ktp_foto') ? $r->file('ktp_foto')->store('ktp', 'local') : null,
        ]);
        $terkirim = OtpService::kirim($u, $r->ip());
        $r->session()->put('otp_email', $u->email);
        ActivityLog::catat('register', $u->email, 'Role '.$u->role);
        if (! $terkirim) {
            return redirect('/verifikasi-otp')->withErrors(['kode' => 'Email kode gagal terkirim (SMTP belum disetting). Coba kirim ulang / hubungi admin via WA.']);
        }

        return redirect('/verifikasi-otp')->with('ok', 'Kode verifikasi dikirim ke '.$u->email.' (berlaku 10 menit).');
    }

    public function verifyForm(Request $r)
    {
        $email = $r->session()->get('otp_email');
        if (! $email || ! User::where('email', $email)->exists()) {
            return redirect('/register')->withErrors(['email' => 'Daftar dulu untuk dapat kode verifikasi.']);
        }

        return web_view('auth-verify', ['email' => $email, 'mask' => self::maskEmail($email)]);
    }

    public function verify(Request $r)
    {
        $email = $r->session()->get('otp_email');
        if (! $email) {
            return redirect('/register')->withErrors(['email' => 'Sesi verifikasi habis. Daftar ulang / login lagi.']);
        }
        $r->validate(['kode' => 'required|digits:6']);
        $hasil = OtpService::cek($email, $r->kode);
        if (! $hasil['ok']) {
            if (str_contains($hasil['pesan'], 'hangus')) {
                ActivityLog::catat('otp_gagal', $email, $hasil['pesan']);
            }

            return back()->withErrors(['kode' => $hasil['pesan']])->withInput();
        }
        $u = User::where('email', $email)->firstOrFail();
        $u->update(['email_verified_at' => now()]);
        ActivityLog::catat('otp_verifikasi', $email, 'Role '.$u->role);
        $r->session()->forget('otp_email');
        Auth::login($u);
        $r->session()->regenerate();

        return redirect($u->role === 'reseller' ? '/akun' : '/')->with('ok', $u->role === 'reseller'
            ? 'Email terverifikasi! Akun reseller menunggu approval admin.'
            : 'Email terverifikasi! Selamat belanja!');
    }

    public function resend(Request $r)
    {
        $email = $r->session()->get('otp_email');
        if (! $email) {
            return redirect('/register')->withErrors(['email' => 'Sesi verifikasi habis. Daftar ulang / login lagi.']);
        }
        $tunggu = OtpService::bolehKirimUlang($email, $r->ip());
        if ($tunggu > 0) {
            return back()->withErrors(['kode' => "Tunggu $tunggu detik sebelum minta kode baru."]);
        }
        $u = User::where('email', $email)->firstOrFail();
        if ($u->email_verified_at) {
            return redirect('/login')->with('ok', 'Akun sudah terverifikasi, silakan login.');
        }
        $terkirim = OtpService::kirim($u, $r->ip());
        if (! $terkirim) {
            return back()->withErrors(['kode' => 'Email gagal terkirim (SMTP belum disetting). Hubungi admin via WA.']);
        }

        return back()->with('ok', 'Kode baru dikirim ke '.self::maskEmail($email).'.');
    }

    protected static function maskEmail(string $email): string
    {
        [$nama, $domain] = array_pad(explode('@', $email, 2), 2, '');
        $awal = substr($nama, 0, 2);

        return $awal.str_repeat('•', max(1, strlen($nama) - 2)).'@'.$domain;
    }

    public function lupaPasswordForm()
    {
        if (Auth::check()) {
            return redirect('/akun');
        }

        return web_view('auth-lupa-password');
    }

    public function lupaPasswordKirim(Request $r)
    {
        $r->validate(['email' => 'required|email']);
        if ($r->filled('website')) {
            return back()->withErrors(['email' => 'Permintaan gagal.'])->withInput();
        }

        $email = strtolower(trim((string) $r->email));
        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email ini belum terdaftar di akun Bubee.'])->withInput();
        }

        $tunggu = OtpService::bolehKirimUlang($email, $r->ip());
        if ($tunggu > 0) {
            return back()->withErrors(['email' => "Tunggu $tunggu detik sebelum meminta kode baru."])->withInput();
        }

        $terkirim = OtpService::kirim($user, $r->ip());
        $r->session()->put('reset_password_email', $user->email);
        ActivityLog::catat('lupa_password_req', $user->email, 'IP '.$r->ip());

        if (! $terkirim) {
            return redirect('/lupa-password/verifikasi')->withErrors(['kode' => 'Email gagal terkirim (SMTP belum disetting). Hubungi admin jika butuh bantuan.']);
        }

        return redirect('/lupa-password/verifikasi')->with('ok', 'Kode verifikasi 6 digit telah dikirim ke '.self::maskEmail($user->email).' (berlaku 10 menit).');
    }

    public function lupaPasswordVerifikasiForm(Request $r)
    {
        $email = $r->session()->get('reset_password_email');
        if (! $email || ! User::where('email', $email)->exists()) {
            return redirect('/lupa-password')->withErrors(['email' => 'Masukkan email Anda terlebih dahulu.']);
        }

        return web_view('auth-lupa-password-reset', [
            'email' => $email,
            'mask' => self::maskEmail($email),
        ]);
    }

    public function lupaPasswordReset(Request $r)
    {
        $email = $r->session()->get('reset_password_email');
        if (! $email) {
            return redirect('/lupa-password')->withErrors(['email' => 'Sesi reset password habis. Silakan ulangi.']);
        }

        $r->validate([
            'kode' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'kode.digits' => 'Kode OTP harus 6 digit angka.',
        ]);

        $hasil = OtpService::cek($email, $r->kode);
        if (! $hasil['ok']) {
            if (str_contains($hasil['pesan'], 'hangus')) {
                ActivityLog::catat('lupa_password_gagal', $email, $hasil['pesan']);
            }

            return back()->withErrors(['kode' => $hasil['pesan']])->withInput();
        }

        $user = User::where('email', $email)->firstOrFail();
        $user->update([
            'password' => Hash::make($r->password),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        ActivityLog::catat('lupa_password_sukses', $email, 'Password berhasil diperbarui');
        $r->session()->forget('reset_password_email');

        return redirect('/login')->with('ok', '🎉 Password berhasil diperbarui! Silakan login dengan password baru Anda.');
    }

    public function lupaPasswordKirimUlang(Request $r)
    {
        $email = $r->session()->get('reset_password_email');
        if (! $email) {
            return redirect('/lupa-password')->withErrors(['email' => 'Sesi reset password habis. Silakan ulangi.']);
        }

        $tunggu = OtpService::bolehKirimUlang($email, $r->ip());
        if ($tunggu > 0) {
            return back()->withErrors(['kode' => "Tunggu $tunggu detik sebelum minta kode baru."]);
        }

        $user = User::where('email', $email)->firstOrFail();
        $terkirim = OtpService::kirim($user, $r->ip());
        if (! $terkirim) {
            return back()->withErrors(['kode' => 'Email gagal terkirim (SMTP belum disetting). Hubungi admin via WA.']);
        }

        return back()->with('ok', 'Kode verifikasi baru dikirim ke '.self::maskEmail($email).'.');
    }

    public function akun()
    {
        $user = Auth::user();
        $jmlOrder = Order::where('user_id', $user->id)->where('status', '!=', 'batal')->count();
        $jmlPcs = OrderItem::whereHas('order', fn ($q) => $q->where('user_id', $user->id)->where('status', '!=', 'batal'))->sum('qty');
        $hemat = 0;
        $tier = null;
        if ($user->role === 'reseller') {
            $hemat = OrderItem::with('product')
                ->whereHas('order', fn ($q) => $q->where('user_id', $user->id)->where('status', '!=', 'batal'))
                ->where('tipe_harga', 'reseller')->get()
                ->sum(fn ($it) => max(0, ($it->product->harga_enduser ?? $it->harga_satuan) - $it->harga_satuan) * $it->qty);
            $belanja = Order::where('user_id', $user->id)->whereIn('status', ['diproses', 'dikirim', 'selesai'])->sum('total_bayar');
            $tier = ResellerController::tier((int) $belanja);
        }

        return web_view('akun', compact('user', 'jmlOrder', 'jmlPcs', 'hemat', 'tier'));
    }

    public function pengaturan()
    {
        $user = Auth::user();

        return web_view('pengaturan', compact('user'));
    }

    public function akunUpdate(Request $r)
    {
        $u = Auth::user();
        $r->validate(['name' => 'required|max:50', 'hp' => 'nullable|max:20', 'toko_nama' => 'nullable|max:60', 'alamat' => 'nullable|max:500']);
        $hp = WaNotifier::normalize((string) $r->hp);
        if ($hp !== '' && User::where('hp', $hp)->where('id', '<>', $u->id)->exists()) {
            return back()->withErrors(['hp' => 'Nomor HP ini sudah dipakai akun lain.'])->withInput();
        }
        $data = ['name' => $r->name, 'hp' => $hp !== '' ? $hp : null, 'alamat' => $r->alamat, 'notif_wa' => $r->has('notif_wa')];
        if ($u->role === 'reseller') {
            $data['toko_nama'] = $r->toko_nama;
        }
        $u->update($data);

        return back()->with('ok', 'Profil & setelan diperbarui ✅');
    }

    public function upgradeReseller(Request $r)
    {
        $u = Auth::user();
        if ($u->role === 'reseller' && $u->status_reseller === 'approved') {
            return back()->with('ok', 'Kamu sudah reseller aktif 🎉');
        }
        if ($u->role === 'reseller' && $u->status_reseller === 'pending') {
            return back()->with('ok', 'Pengajuan kamu sedang dalam antrean review admin ⏳');
        }
        $r->validate([
            'toko_nama' => 'required|max:60',
            'nik' => 'required|digits:16',
            'ktp_foto' => 'nullable|image|max:2048',
            'alamat' => 'required|max:500',
        ]);
        $data = [
            'role' => 'reseller', 'status_reseller' => 'pending',
            'toko_nama' => $r->toko_nama,
            'nik' => preg_replace('/[^0-9]/', '', (string) $r->nik),
            'alamat' => $r->alamat,
        ];
        if ($r->hasFile('ktp_foto')) {
            $data['ktp_foto'] = $r->file('ktp_foto')->store('ktp', 'local');
        }
        $u->update($data);
        ActivityLog::catat('reseller_upgrade', $u->email, ($u->status_reseller === 'rejected' ? 'Pengajuan ulang reseller' : 'Upgrade reguler → reseller (pending)'));

        return back()->with('ok', 'Pengajuan reseller dikirim! Tunggu approval admin ⏳');
    }

    public function gantiPassword(Request $r)
    {
        $u = Auth::user();
        $r->validate([
            'password_lama' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        if (! Hash::check($r->password_lama, $u->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.']);
        }
        $u->update(['password' => Hash::make($r->password)]);
        Auth::logoutOtherDevices($r->password); // tendang sesi di HP/laptop lain
        ActivityLog::catat('password_ganti', $u->email, 'Password diganti sendiri');

        return back()->with('ok', 'Password diganti ✅ Perangkat lain otomatis logout.');
    }

    public function hapusAkun(Request $r)
    {
        $u = Auth::user();
        if ($u->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->withErrors(['hapus' => 'Kamu satu-satunya admin. Tunjuk admin lain dulu.']);
        }
        $aktif = Order::where('user_id', $u->id)
            ->whereIn('status', ['menunggu_bayar', 'diproses', 'dikirim'])->exists();
        if ($aktif) {
            return back()->withErrors(['hapus' => 'Masih ada order aktif. Selesaikan/batalkan dulu.']);
        }
        $r->validate(['konfirmasi' => 'required|in:HAPUS', 'password' => 'required']);
        if (! Hash::check($r->password, $u->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }
        $stamp = now()->format('YmdHis');
        $u->update([
            'name' => 'Pengguna Dihapus', 'email' => 'deleted_'.$u->id.'_'.$stamp.'@deleted.local',
            'password' => Hash::make(bin2hex(random_bytes(16))),
            'hp' => null, 'alamat' => null, 'toko_nama' => null, 'ktp_foto' => null,
            'role' => 'enduser', 'status_reseller' => 'none', 'notif_wa' => false,
        ]);
        Cart::where('user_id', $u->id)->delete();
        ActivityLog::catat('akun_hapus', $u->email, 'Akun dihapus sendiri');
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect('/')->with('ok', 'Akun dihapus. Riwayat order tetap tersimpan anonim. 👋');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect('/splash')->with('ok', 'Sudah logout. Sampai jumpa! 👋');
    }
}
