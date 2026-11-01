<?php

namespace App\Services;

use App\Models\Product;

class PriceService
{
    /**
     * Tentukan harga 1 produk untuk user + qty.
     * Aturan: ambil tier terbesar yang terpenuhi di level akun
     * (reseller approved -> level reseller, selain itu user).
     * Kalau produk belum punya tier -> fallback kolom lama.
     *
     * @return array{harga:int, tipe:string} tipe = reseller|enduser
     */
    public static function untuk(Product $product, $user, int $qty): array
    {
        $isReseller = $user && ($user->role ?? '') === 'reseller'
            && ($user->status_reseller ?? '') === 'approved';
        $level = $isReseller ? 'reseller' : 'user';

        $tiers = $product->relationLoaded('prices')
            ? $product->prices->where('level', $level)->sortByDesc('min_qty')
            : $product->prices()->where('level', $level)->reorder()->orderByDesc('min_qty')->get();

        foreach ($tiers as $t) {
            if ($qty >= (int) $t->min_qty) {
                return ['harga' => (int) $t->price, 'tipe' => $level === 'reseller' ? 'reseller' : 'enduser'];
            }
        }

        // Fallback kolom lama (produk tanpa tier)
        if ($level === 'reseller' && $qty >= (int) $product->min_reseller) {
            return ['harga' => (int) $product->harga_reseller, 'tipe' => 'reseller'];
        }

        return ['harga' => (int) $product->harga_enduser, 'tipe' => 'enduser'];
    }

    /** Harga reseller termurah (untuk label "mulai dari"). */
    public static function resellerTermurah(Product $product): int
    {
        $min = $product->relationLoaded('prices')
            ? $product->prices->where('level', 'reseller')->min('price')
            : $product->prices()->where('level', 'reseller')->min('price');

        return (int) ($min ?? $product->harga_reseller);
    }

    /** MOQ agar dapat harga reseller (tier terkecil). */
    public static function minReseller(Product $product): int
    {
        $min = $product->relationLoaded('prices')
            ? $product->prices->where('level', 'reseller')->min('min_qty')
            : $product->prices()->where('level', 'reseller')->min('min_qty');

        return (int) ($min ?? $product->min_reseller);
    }
}
