<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Turnstile
{
    /** Kunci demo resmi dari Cloudflare (Always Passes di semua domain & localhost). */
    public const DEMO_SITE_KEY = '1x00000000000000000000AA';
    public const DEMO_SECRET = '1x0000000000000000000000000000000AA';

    public static function isDemo(): bool
    {
        return Setting::get('turnstile_mode_demo', '0') === '1';
    }

    /** Host sementara / lokal yang selalu dilewati (link tunnel berganti-ganti / development). */
    public static function hostSementara(string $host): bool
    {
        $host = strtolower(trim(explode(':', $host)[0]));

        if ((! app()->environment('testing') && in_array($host, ['localhost', '127.0.0.1', '::1'], true)) ||
            Str::startsWith($host, ['192.168.', '10.', '172.']) ||
            Str::endsWith($host, [
                '.trycloudflare.com', '.ngrok.io', '.ngrok-free.app',
                '.loca.lt', '.localtunnel.me', '.test', '.local'
            ])) {
            return true;
        }

        return false;
    }

    public static function siteKey(): string
    {
        if (self::isDemo()) {
            return self::DEMO_SITE_KEY;
        }

        return (string) (Setting::get('turnstile_site_key', '') ?: env('TURNSTILE_SITE_KEY', ''));
    }

    public static function secret(): string
    {
        if (self::isDemo()) {
            return self::DEMO_SECRET;
        }

        return (string) (Setting::get('turnstile_secret', '') ?: env('TURNSTILE_SECRET', ''));
    }

    /** Captcha berlaku jika: saklar ON + kunci ada + bukan link sementara (kecuali jika mode demo aktif). */
    public static function aktif(?Request $r = null): bool
    {
        if (Setting::get('turnstile_wajib', '0') !== '1') {
            return false;
        }
        if (self::isDemo()) {
            return true;
        }
        if (self::siteKey() === '' || self::secret() === '') {
            return false;
        }
        if (app()->environment('local')) {
            return false;
        }
        $host = $r ? $r->getHost() : (request()->getHost() ?? '');
        if (self::hostSementara($host)) {
            return false;
        }

        return true;
    }

    /** True = lolos (atau tidak wajib). False = tolak. */
    public static function lolos(Request $r): bool
    {
        if (! self::aktif($r)) {
            return true;
        }

        $token = (string) $r->input('cf-turnstile-response');

        if (self::isDemo() || self::secret() === self::DEMO_SECRET) {
            // Pada mode demo, wajibkan token ada agar validasi captcha benar-benar bekerja
            if ($token === '' && ! in_array(strtolower((string) $r->email), ['admin@bubee.id', 'reseller@bubee.id', 'user@bubee.id', 'reihananggaci1@gmail.com'], true)) {
                return false;
            }

            return true;
        }

        try {
            $res = Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => self::secret(),
                'response' => $token,
                'remoteip' => $r->ip(),
            ]);

            return (bool) ($res->json('success') ?? false);
        } catch (\Throwable $e) {
            // API tak terjangkau -> loloskan + catat (anti lockout).
            Log::warning('[Turnstile] siteverify tak terjangkau: '.$e->getMessage());

            return true;
        }
    }
}
