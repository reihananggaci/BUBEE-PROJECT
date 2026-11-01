<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['email', 'kode_hash', 'percobaan', 'kedaluwarsa_at', 'dipakai_at'];

    protected $casts = ['kedaluwarsa_at' => 'datetime', 'dipakai_at' => 'datetime'];

    public function kedaluwarsa(): bool
    {
        return $this->dipakai_at !== null || $this->kedaluwarsa_at->isPast();
    }
}
