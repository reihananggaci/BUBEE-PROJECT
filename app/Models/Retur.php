<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $fillable = ['order_id', 'user_id', 'alasan', 'kategori', 'foto', 'status', 'catatan_admin'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ReturItem::class);
    }
}
