<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'daftar_coba')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('daftar_coba')->default(1)->after('email_verified_at');
            });
        }

        // Normalisasi HP ke format 62… agar 0812/62812/+62 dianggap sama.
        // Duplikat hasil normalisasi: akun tertua dipertahankan, sisanya dikosongkan.
        $dilihat = [];
        foreach (DB::table('users')->select('id', 'hp')->orderBy('id')->get() as $u) {
            if (! $u->hp) {
                continue;
            }
            $n = preg_replace('/[^0-9]/', '', (string) $u->hp);
            if (str_starts_with($n, '0')) {
                $n = '62'.substr($n, 1);
            }
            if ($n === '' || $n === null) {
                DB::table('users')->where('id', $u->id)->update(['hp' => null]);

                continue;
            }
            if (isset($dilihat[$n])) {
                DB::table('users')->where('id', $u->id)->update(['hp' => null]);

                continue;
            }
            $dilihat[$n] = true;
            if ($u->hp !== $n) {
                DB::table('users')->where('id', $u->id)->update(['hp' => $n]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('daftar_coba');
        });
    }
};
