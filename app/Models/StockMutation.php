<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    protected $fillable = ['product_id', 'variant_id', 'tipe', 'qty', 'stok_sebelum', 'stok_sesudah', 'keterangan'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
