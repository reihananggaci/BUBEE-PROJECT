<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('activity_logs', 'kategori')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('kategori', 20)->default('sistem')->after('aksi');
                $table->string('level', 10)->default('info')->after('kategori');
                $table->string('ip', 45)->nullable()->after('keterangan');
                $table->string('tautan', 200)->nullable()->after('ip');
                $table->index(['created_at', 'kategori', 'level'], 'activity_cari_idx');
            });
        }

        // Backfill dari pola aksi lama
        $peta = [
            'auth' => ['login_', 'register', 'otp', 'verifikasi', 'user_reset', 'password'],
            'order' => ['order_', 'bayar', 'midtrans', 'checkout', 'batal'],
            'stok' => ['stok', 'mutasi', 'opname', 'rekonsiliasi', 'varian', 'tier'],
            'produk' => ['produk', 'product'],
            'user' => ['user_', 'reseller', 'role'],
        ];
        foreach (DB::table('activity_logs')->select('id', 'aksi')->get() as $log) {
            $kat = 'sistem';
            foreach ($peta as $k => $awalan) {
                foreach ($awalan as $a) {
                    if (str_starts_with($log->aksi, $a)) {
                        $kat = $k;
                        break 2;
                    }
                }
            }
            $lvl = str_contains($log->aksi, 'gagal') || str_contains($log->aksi, 'honeypot') || str_contains($log->aksi, 'tolak')
                ? 'waspada' : 'info';
            DB::table('activity_logs')->where('id', $log->id)->update(['kategori' => $kat, 'level' => $lvl]);
        }
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_cari_idx');
            $table->dropColumn(['kategori', 'level', 'ip', 'tautan']);
        });
    }
};
