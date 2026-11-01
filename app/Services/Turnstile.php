<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Turnstile
{
    /** Host sementara / lokal yang selalu dilewati (link tunnel berganti-ganti / development). */
    public static function hostSementara(string $host): bool
    {
        $host = strtolower(trim(explode(':', $host)[0]));

        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true) ||
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
        return (string) (Setting::get('turnstile_site_key', '') ?: env('TURNSTILE_SITE_KEY', ''));
    }

    public static function secret(): string
    {
        return (string) (Setting::get('turnstile_secret', '') ?: env('TURNSTILE_SECRET', ''));
    }

    /** Captcha berlaku hanya jika: bukan env local + saklar ON + kunci ada + host bukan link sementara/lokal. */
    public static function aktif(?Request $r = null): bool
    {
        if (app()->environment('local')) {
            return false;
        }
        if (Setting::get('turnstile_wajib', '0') !== '1') {
            return false;
        }
        if (self::siteKey() === '' || self::secret() === '') {
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
        try {
            $res = Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => self::secret(),
                'response' => $r->input('cf-turnstile-response'),
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
