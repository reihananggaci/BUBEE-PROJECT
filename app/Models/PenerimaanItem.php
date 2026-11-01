<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanItem extends Model
{
    protected $table = 'penerimaan_items';

    protected $fillable = ['penerimaan_id', 'product_id', 'variant_id', 'qty_klaim', 'qty_lolos', 'qty_rusak', 'catatan'];

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function selisih(): int
    {
        return (int) $this->qty_klaim - (int) $this->qty_lolos - (int) $this->qty_rusak;
    }
}
