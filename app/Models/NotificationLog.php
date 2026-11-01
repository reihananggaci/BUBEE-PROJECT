<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = ['tipe', 'tujuan', 'pesan', 'status', 'response', 'order_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
