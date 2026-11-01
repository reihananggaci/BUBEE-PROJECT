<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $table = 'penerimaan';

    protected $fillable = ['user_id', 'no_surat_jalan', 'supplier', 'status'];

    public function items()
    {
        return $this->hasMany(PenerimaanItem::class, 'penerimaan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
