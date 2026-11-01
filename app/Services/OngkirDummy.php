<?php

namespace App\Services;

class OngkirDummy
{
    /**
     * Pembagi volumetrik per kurir (dari mitra).
     * Berat volumetrik (kg) = P x L x T (cm) / pembagi.
     */
    public static function pembagi(string $kurir): int
    {
        return match (strtoupper($kurir)) {
            'SENTRAL' => 4000,
            'JNT' => 5000,
            default => 6000, // JNE, SICEPAT, LION, SPX
        };
    }

    /**
     * Berat yang ditagih (gram) = maks(aktual, volumetrik).
     * $items: list ['berat_gram'=>int,'qty'=>int,'p'=>int,'l'=>int,'t'=>int]
     *
     * @return array{aktual:int, volumetrik:int, tagih:int}
     */
    public static function beratTagih(array $items, string $kurir): array
    {
        $aktual = 0;
        $volCm3 = 0;
        foreach ($items as $it) {
            $aktual += (int) $it['berat_gram'] * (int) $it['qty'];
            $p = (int) ($it['p'] ?? 0);
            $l = (int) ($it['l'] ?? 0);
            $t = (int) ($it['t'] ?? 0);
            if ($p > 0 && $l > 0 && $t > 0) {
                $volCm3 += $p * $l * $t * (int) $it['qty'];
            }
        }
        $volumetrik = (int) ceil($volCm3 / self::pembagi($kurir) * 1000);

        return ['aktual' => $aktual, 'volumetrik' => $volumetrik, 'tagih' => max($aktual, $volumetrik)];
    }

    public static function listKurir(): array
    {
        return [
            'JNE' => [
                ['kode' => 'REG', 'label' => 'REG (2-3 hari)', 'ongkir_per_kg' => 11000, 'etd' => '2-3 hari'],
                ['kode' => 'YES', 'label' => 'YES (1 hari)', 'ongkir_per_kg' => 22000, 'etd' => '1 hari'],
            ],
            'JNT' => [
                ['kode' => 'REG', 'label' => 'REG (2-3 hari)', 'ongkir_per_kg' => 10000, 'etd' => '2-3 hari'],
                ['kode' => 'ECO', 'label' => 'ECO (3-5 hari)', 'ongkir_per_kg' => 8000, 'etd' => '3-5 hari'],
            ],
            'SPX' => [
                ['kode' => 'Standard', 'label' => 'Standard (2-4 hari)', 'ongkir_per_kg' => 9000, 'etd' => '2-4 hari'],
                ['kode' => 'SameDay', 'label' => 'SameDay (1 hari)', 'ongkir_per_kg' => 18000, 'etd' => '1 hari'],
            ],
            'SICEPAT' => [
                ['kode' => 'REG', 'label' => 'REG (2-3 hari)', 'ongkir_per_kg' => 10000, 'etd' => '2-3 hari'],
                ['kode' => 'BEST', 'label' => 'BEST (1-2 hari)', 'ongkir_per_kg' => 14000, 'etd' => '1-2 hari'],
            ],
            'LION' => [
                ['kode' => 'REGPACK', 'label' => 'REGPACK (2-3 hari)', 'ongkir_per_kg' => 11000, 'etd' => '2-3 hari'],
                ['kode' => 'JAGOPACK', 'label' => 'JAGOPACK (1 hari)', 'ongkir_per_kg' => 16000, 'etd' => '1 hari'],
            ],
            'SENTRAL' => [
                ['kode' => 'DARAT', 'label' => 'DARAT Cargo', 'ongkir_per_kg' => 8000, 'etd' => '3-7 hari'],
                ['kode' => 'UDARA', 'label' => 'UDARA Cargo', 'ongkir_per_kg' => 15000, 'etd' => '1-2 hari'],
            ],
        ];
    }

    public static function hitung(string $kurir, string $layanan, int $beratGram): int
    {
        $kg = (int) ceil($beratGram / 1000);
        if ($kg < 1) {
            $kg = 1;
        }
        foreach (self::listKurir()[$kurir] ?? [] as $l) {
            if ($l['kode'] === $layanan) {
                return $l['ongkir_per_kg'] * $kg;
            }
        }

        return 10000 * $kg;
    }
}
