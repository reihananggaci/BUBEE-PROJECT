<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpnameSesi extends Model
{
    protected $table = 'opname_sesi';

    protected $fillable = ['user_id', 'cakupan', 'status'];

    public function items()
    {
        return $this->hasMany(OpnameItem::class, 'sesi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function terisi(): int
    {
        return $this->items()->whereNotNull('fisik')->count();
    }

    public function total(): int
    {
        return $this->items()->count();
    }
}
