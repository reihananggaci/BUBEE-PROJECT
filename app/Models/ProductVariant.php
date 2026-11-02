<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'ukuran', 'warna', 'stok', 'sku', 'harga_enduser', 'harga_reseller', 'berat_gram'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function hargaEnduser(): int
    {
        return (int) ($this->attributes['harga_enduser'] ?? null ?: ($this->product?->harga_enduser ?? 0));
    }

    public function hargaReseller(): int
    {
        return (int) ($this->attributes['harga_reseller'] ?? null ?: ($this->product?->harga_reseller ?? 0));
    }

    public function hargaUntuk($user): int
    {
        $isReseller = $user && ($user->role ?? '') === 'reseller'
            && ($user->status_reseller ?? '') === 'approved';

        return $isReseller ? $this->hargaReseller() : $this->hargaEnduser();
    }

    public function beratGram(): int
    {
        return (int) ($this->attributes['berat_gram'] ?? null ?: ($this->product?->berat_gram ?? 0));
    }

    public function label(): string
    {
        if ($this->ukuran === 'All Size' && ($this->warna === '-' || ! $this->warna)) {
            return 'All Size';
        }

        return trim($this->ukuran.($this->warna && $this->warna !== '-' ? ' / '.$this->warna : ''));
    }

    protected static function booted()
    {
        static::saved(function ($variant) {
            $variant->product?->syncStok();
        });

        static::deleted(function ($variant) {
            $variant->product?->syncStok();
        });
    }
}
