<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['actor_id', 'aksi', 'kategori', 'level', 'subjek', 'keterangan', 'ip', 'tautan'];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Kategori otomatis dari awalan aksi bila tidak disebut eksplisit.
     */
    public static function kategoriUntuk(string $aksi): string
    {
        $peta = [
            'auth' => ['login_', 'register', 'otp', 'verifikasi', 'user_reset', 'password'],
            'order' => ['order_', 'bayar', 'midtrans', 'checkout', 'batal'],
            'stok' => ['stok', 'mutasi', 'opname', 'rekonsiliasi', 'varian', 'tier'],
            'produk' => ['produk', 'product'],
            'user' => ['user_', 'reseller', 'role'],
        ];
        foreach ($peta as $kat => $awalan) {
            foreach ($awalan as $a) {
                if (str_starts_with($aksi, $a)) {
                    return $kat;
                }
            }
        }

        return 'sistem';
    }

    public static function levelUntuk(string $aksi): string
    {
        if (str_contains($aksi, 'honeypot') || str_contains($aksi, 'gagal')) {
            return 'bahaya';
        }
        if (str_contains($aksi, 'tolak') || str_contains($aksi, 'hapus') || str_contains($aksi, 'batal')) {
            return 'waspada';
        }

        return 'info';
    }

    public static function catat(
        string $aksi,
        ?string $subjek = null,
        ?string $keterangan = null,
        ?string $kategori = null,
        ?string $level = null,
        ?string $tautan = null
    ): void {
        try {
            $ip = request()->ip();
        } catch (\Throwable $e) {
            $ip = null;
        }
        static::create([
            'actor_id' => auth()->id(), 'aksi' => $aksi,
            'kategori' => $kategori ?? static::kategoriUntuk($aksi),
            'level' => $level ?? static::levelUntuk($aksi),
            'subjek' => $subjek, 'keterangan' => $keterangan,
            'ip' => $ip, 'tautan' => $tautan,
        ]);
    }

    /** Hapus log lebih tua dari N hari. Kembali: jumlah terhapus. */
    public static function prune(int $hari = 90): int
    {
        return static::where('created_at', '<', now()->subDays($hari))->delete();
    }
}
