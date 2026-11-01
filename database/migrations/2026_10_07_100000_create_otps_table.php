<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('otps')) {
            Schema::create('otps', function (Blueprint $table) {
                $table->id();
                $table->string('email')->index();
                $table->string('kode_hash');
                $table->unsignedTinyInteger('percobaan')->default(0);
                $table->timestamp('kedaluwarsa_at');
                $table->timestamp('dipakai_at')->nullable();
                $table->timestamps();
            });
        }

        // HP wajib-unik (NULL ganda tetap boleh di SQLite & MySQL).
        if (! Schema::hasIndex('users', 'users_hp_unique')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('hp', 'users_hp_unique');
            });
        }

        // Anti-lockout: akun yang sudah ada dianggap terverifikasi.
        DB::table('users')->whereNull('email_verified_at')->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_hp_unique');
        });
        Schema::dropIfExists('otps');
    }
};
