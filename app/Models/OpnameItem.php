<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpnameItem extends Model
{
    protected $table = 'opname_items';

    protected $fillable = ['sesi_id', 'variant_id', 'snapshot', 'fisik', 'foto'];

    public function sesi()
    {
        return $this->belongsTo(OpnameSesi::class, 'sesi_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function selisih(): ?int
    {
        return $this->fisik === null ? null : ((int) $this->fisik - (int) $this->snapshot);
    }

    /** Selisih besar: >5 pcs atau >20% dari snapshot. */
    public function besar(): bool
    {
        $d = $this->selisih();
        if ($d === null || $d === 0) {
            return false;
        }
        if (abs($d) > 5) {
            return true;
        }

        return $this->snapshot > 0 && abs($d) / $this->snapshot > 0.2;
    }
}
