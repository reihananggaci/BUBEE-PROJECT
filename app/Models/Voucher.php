<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['kode', 'nama', 'tipe', 'nilai', 'maks_diskon', 'min_belanja', 'kuota', 'terpakai', 'khusus', 'is_active', 'berlaku_sampai'];

    protected $casts = ['is_active' => 'boolean', 'berlaku_sampai' => 'date'];

    public function label(): string
    {
        return $this->tipe === 'persen' ? $this->nilai.'%' : 'Rp '.number_format($this->nilai);
    }

    /** @return array{ok:bool, pesan:string, diskon:int} */
    public function cek($user, int $totalProduk): array
    {
        if (! $this->is_active) {
            return ['ok' => false, 'pesan' => 'Voucher nonaktif', 'diskon' => 0];
        }
        if ($this->berlaku_sampai && now()->toDateString() > $this->berlaku_sampai->toDateString()) {
            return ['ok' => false, 'pesan' => 'Voucher kedaluwarsa', 'diskon' => 0];
        }
        if ($this->terpakai >= $this->kuota) {
            return ['ok' => false, 'pesan' => 'Kuota voucher habis', 'diskon' => 0];
        }
        if ($totalProduk < $this->min_belanja) {
            return ['ok' => false, 'pesan' => 'Min. belanja Rp '.number_format($this->min_belanja), 'diskon' => 0];
        }
        $role = $user->role ?? 'enduser';
        if ($this->khusus !== 'semua' && $this->khusus !== $role) {
            return ['ok' => false, 'pesan' => 'Voucher khusus '.$this->khusus, 'diskon' => 0];
        }
        if ($this->khusus === 'reseller' && ($user->status_reseller ?? '') !== 'approved') {
            return ['ok' => false, 'pesan' => 'Khusus reseller approved', 'diskon' => 0];
        }
        $diskon = $this->tipe === 'persen' ? (int) round($totalProduk * $this->nilai / 100) : (int) $this->nilai;
        if ($this->maks_diskon > 0) {
            $diskon = min($diskon, $this->maks_diskon);
        }
        $diskon = min($diskon, $totalProduk);

        return ['ok' => true, 'pesan' => 'Voucher '.$this->kode.' (-Rp '.number_format($diskon).')', 'diskon' => $diskon];
    }
}
