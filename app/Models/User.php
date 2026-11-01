<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'status_reseller',
        'daftar_coba',
        'hp',
        'alamat',
        'ktp_foto',
        'nik',
        'toko_nama',
        'notif_wa',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Syarat approve reseller. Kembali: daftar yang MASIH KURANG (kosong = lolos).
     */
    public function kekuranganReseller(): array
    {
        $kurang = [];
        if (! $this->email_verified_at) {
            $kurang[] = 'email belum verifikasi';
        }
        if (! trim((string) $this->toko_nama)) {
            $kurang[] = 'nama toko kosong';
        }
        if (! preg_match('/^[0-9]{16}$/', (string) $this->nik)) {
            $kurang[] = 'NIK tidak valid (16 digit)';
        }
        if (! trim((string) $this->hp)) {
            $kurang[] = 'HP kosong';
        }
        if (! trim((string) $this->alamat)) {
            $kurang[] = 'alamat kosong';
        }

        return $kurang;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notif_wa' => 'boolean',
        ];
    }
}
