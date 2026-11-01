<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'order_id', 'rating', 'ulasan', 'foto'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function bintang(float $avg): string
    {
        $penuh = (int) floor($avg);
        $setengah = ($avg - $penuh) >= 0.5 ? 1 : 0;

        return str_repeat('★', $penuh).($setengah ? '☆' : '').str_repeat('☆', 5 - $penuh - $setengah);
    }
}
