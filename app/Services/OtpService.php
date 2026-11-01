<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    public const PANJANG = 6;

    public const MENIT_BERLAKU = 10;

    public const MAKS_SALAH = 5;

    public const DETIK_COOLDOWN = 60;

    public static function kunciKirim(string $email, string $ip): string
    {
        return 'otp-kirim:'.strtolower(trim($email)).'|'.$ip;
    }

    /** Kirim kode baru. True = terkirim (atau tercatat saat mailer log). */
    public static function kirim(User $user, string $ip): bool
    {
        Otp::where('email', $user->email)->whereNull('dipakai_at')->update(['dipakai_at' => now()]);

        $kode = (string) random_int(100000, 999999);
        Otp::create([
            'email' => $user->email,
            'kode_hash' => Hash::make($kode),
            'kedaluwarsa_at' => now()->addMinutes(self::MENIT_BERLAKU),
        ]);
        RateLimiter::hit(self::kunciKirim($user->email, $ip), self::DETIK_COOLDOWN);

        try {
            Mail::to($user->email)->send(new OtpMail($kode, $user->name));

            return true;
        } catch (\Throwable $e) {
            Log::warning('[OTP] email gagal ke '.$user->email.': '.$e->getMessage());

            return false;
        }
    }

    public static function bolehKirimUlang(string $email, string $ip): int
    {
        $key = self::kunciKirim($email, $ip);

        return RateLimiter::tooManyAttempts($key, 1) ? RateLimiter::availableIn($key) : 0;
    }

    /** @return array{ok:bool, pesan:string} */
    public static function cek(string $email, string $kode): array
    {
        $otp = Otp::where('email', $email)->whereNull('dipakai_at')
            ->latest()->first();
        if (! $otp || $otp->kedaluwarsa_at->isPast()) {
            return ['ok' => false, 'pesan' => 'Kode tidak ditemukan / kedaluwarsa. Minta kode baru.'];
        }
        if ($otp->percobaan >= self::MAKS_SALAH) {
            $otp->update(['dipakai_at' => now()]);

            return ['ok' => false, 'pesan' => 'Terlalu banyak salah. Kode hangus, minta kode baru.'];
        }
        if (! Hash::check($kode, $otp->kode_hash)) {
            $otp->increment('percobaan');
            $sisa = self::MAKS_SALAH - $otp->percobaan;
            if ($sisa <= 0) {
                $otp->update(['dipakai_at' => now()]);

                return ['ok' => false, 'pesan' => 'Terlalu banyak salah. Kode hangus, minta kode baru.'];
            }

            return ['ok' => false, 'pesan' => "Kode salah. Sisa $sisa percobaan."];
        }
        $otp->update(['dipakai_at' => now()]);

        return ['ok' => true, 'pesan' => 'Verifikasi berhasil.'];
    }
}
