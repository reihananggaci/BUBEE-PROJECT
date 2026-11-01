<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'variant_id', 'qty', 'harga_satuan', 'subtotal', 'tipe_harga'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
