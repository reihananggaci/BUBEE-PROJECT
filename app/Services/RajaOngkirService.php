<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    /** Kurir real-time paket Pro. Lion/Sentral/SPX selalu tarif toko. */
    public static function realCouriers(): array
    {
        return ['JNE', 'JNT', 'SICEPAT'];
    }

    public static function key(): string
    {
        return (string) (Setting::get('rajaongkir_key', '') ?: env('RAJAONGKIR_KEY', ''));
    }

    public static function configured(): bool
    {
        return self::key() !== '';
    }

    public static function originCity(): string
    {
        return (string) (Setting::get('rajaongkir_origin_city', '') ?: env('RAJAONGKIR_ORIGIN', ''));
    }

    protected static function base(): string
    {
        return 'https://api.rajaongkir.com/starter';
    }

    public static function provinces(): array
    {
        if (self::configured()) {
            try {
                $res = Http::timeout(15)->get(self::base().'/province', ['key' => self::key()]);
                $list = $res->json('rajaongkir.results') ?? [];
                if ($list) {
                    return collect($list)->map(fn ($p) => ['id' => $p['province_id'], 'nama' => $p['province']])->values()->all();
                }
            } catch (\Throwable $e) {
                Log::warning('[RajaOngkir] provinces gagal: '.$e->getMessage());
            }
        }

        return [
            ['id' => '9', 'nama' => 'Jawa Barat'],
            ['id' => '10', 'nama' => 'Jawa Tengah'],
            ['id' => '11', 'nama' => 'Jawa Timur'],
            ['id' => '6', 'nama' => 'DKI Jakarta'],
            ['id' => '34', 'nama' => 'Sumatera Utara'],
        ];
    }

    public static function cities(string $provinceId): array
    {
        if (self::configured()) {
            try {
                $res = Http::timeout(15)->get(self::base().'/city', ['key' => self::key(), 'province' => $provinceId]);
                $list = $res->json('rajaongkir.results') ?? [];
                if ($list) {
                    return collect($list)->map(fn ($c) => [
                        'id' => $c['city_id'],
                        'nama' => $c['type'].' '.$c['city_name'],
                    ])->values()->all();
                }
            } catch (\Throwable $e) {
                Log::warning('[RajaOngkir] cities gagal: '.$e->getMessage());
            }
        }
        $dummy = [
            '9' => [['id' => '23', 'nama' => 'Kota Bandung'], ['id' => '22', 'nama' => 'Kab. Bekasi']],
            '10' => [['id' => '445', 'nama' => 'Kota Semarang'], ['id' => '446', 'nama' => 'Kota Solo']],
            '11' => [['id' => '444', 'nama' => 'Kota Surabaya'], ['id' => '440', 'nama' => 'Kota Malang']],
            '6' => [['id' => '151', 'nama' => 'Kota Jakarta Timur'], ['id' => '152', 'nama' => 'Kota Jakarta Selatan']],
            '34' => [['id' => '101', 'nama' => 'Kota Medan'], ['id' => '102', 'nama' => 'Kota Binjai']],
        ];

        return $dummy[$provinceId] ?? [];
    }

    /**
     * @return array{ongkir:int, sumber:string, etd:string}
     */
    public static function cost(string $kurir, string $layanan, int $beratGram, ?string $cityId = null): array
    {
        $kurirLow = strtolower($kurir);
        if (self::configured() && in_array($kurir, self::realCouriers()) && self::originCity() !== '' && $cityId) {
            try {
                $res = Http::timeout(20)->asForm()->post(self::base().'/cost', [
                    'key' => self::key(),
                    'origin' => self::originCity(),
                    'destination' => $cityId,
                    'weight' => max(1, $beratGram),
                    'courier' => $kurirLow,
                ]);
                $results = $res->json('rajaongkir.results') ?? [];
                $costs = $results[0]['costs'] ?? [];
                foreach ($costs as $c) {
                    if (strtoupper($c['service'] ?? '') === strtoupper($layanan)) {
                        return ['ongkir' => (int) ($c['cost'][0]['value'] ?? 0), 'sumber' => 'real', 'etd' => $c['cost'][0]['etd'] ?? ''];
                    }
                }
                if ($costs) {
                    return ['ongkir' => (int) ($costs[0]['cost'][0]['value'] ?? 0), 'sumber' => 'real', 'etd' => $costs[0]['cost'][0]['etd'] ?? ''];
                }
            } catch (\Throwable $e) {
                Log::warning('[RajaOngkir] cost gagal, pakai dummy: '.$e->getMessage());
            }
        }

        return ['ongkir' => OngkirDummy::hitung($kurir, $layanan, $beratGram), 'sumber' => 'dummy', 'etd' => ''];
    }

    /** Link lacak eksternal per kurir (selalu tersedia). */
    public static function lacakUrl(string $kurir, string $resi): string
    {
        return match (strtoupper($kurir)) {
            'JNE' => 'https://www.jne.co.id/id/tracking/trace/'.urlencode($resi),
            'JNT' => 'https://jet.co.id/track?awb='.urlencode($resi),
            'SPX' => 'https://spx.co.id/search?wb='.urlencode($resi),
            'SICEPAT' => 'https://www.sicepat.com/checkAwb/'.urlencode($resi),
            'LION' => 'https://lionparcel.com/track?tracking_number='.urlencode($resi),
            default => 'https://www.google.com/search?q='.urlencode($kurir.' '.$resi),
        };
    }

    /**
     * Timeline resi via RajaOngkir waybill. Return null jika tidak tersedia.
     *
     * @return array{status:string, riwayat:array}|null
     */
    public static function track(string $kurir, string $resi): ?array
    {
        if (! self::configured() || ! in_array(strtoupper($kurir), ['JNE', 'JNT', 'SICEPAT'])) {
            return null;
        }
        try {
            $res = Http::timeout(20)->asForm()->post(self::base().'/waybill', [
                'key' => self::key(), 'waybill' => $resi, 'courier' => strtolower($kurir),
            ]);
            $r = $res->json('rajaongkir.result');
            if (! $r || ($res->json('rajaongkir.status.code') ?? 400) !== 200) {
                return null;
            }

            return [
                'status' => $r['delivery_status']['status'] ?? '-',
                'riwayat' => array_reverse($r['manifest'] ?? []),
            ];
        } catch (\Throwable $e) {
            Log::warning('[RajaOngkir] waybill gagal: '.$e->getMessage());

            return null;
        }
    }
}
