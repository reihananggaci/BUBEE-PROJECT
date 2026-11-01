<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['topik', 'pertanyaan', 'jawaban', 'urutan', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public static function topiks(): array
    {
        return ['Order', 'Bayar', 'Ongkir', 'Reseller', 'Retur', 'Akun'];
    }
}
