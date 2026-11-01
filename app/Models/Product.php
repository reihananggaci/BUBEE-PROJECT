<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['nama', 'deskripsi', 'kategori', 'foto', 'berat_gram', 'panjang_cm', 'lebar_cm', 'tinggi_cm', 'stok', 'harga_enduser', 'harga_reseller', 'min_reseller', 'is_active'];

    public function hargaUntuk($user): int
    {
        if ($user && ($user->role ?? '') === 'reseller' && ($user->status_reseller ?? '') === 'approved') {
            return (int) $this->harga_reseller;
        }

        return (int) $this->harga_enduser;
    }

    public function statusStok(): string
    {
        if ($this->stok <= 0) {
            return 'habis';
        }
        if ($this->stok <= 10) {
            return 'menipis';
        }

        return 'tersedia';
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class)->orderBy('level')->orderBy('min_qty');
    }

    public function ratingRata(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function syncStok(): void
    {
        $this->update(['stok' => (int) $this->variants()->sum('stok')]);
    }
}
