<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'ukuran', 'warna', 'stok', 'sku'];

    public function product()
    {
        return $this->belongsTo(Product::class);
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
