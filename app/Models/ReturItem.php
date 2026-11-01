<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturItem extends Model
{
    protected $fillable = ['retur_id', 'order_item_id', 'qty'];

    public function retur()
    {
        return $this->belongsTo(Retur::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
