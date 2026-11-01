<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama')->nullable();
            $table->string('tipe')->default('persen'); // persen, nominal
            $table->integer('nilai')->default(10); // % atau Rp
            $table->integer('maks_diskon')->default(0); // 0 = tanpa batas
            $table->integer('min_belanja')->default(0);
            $table->integer('kuota')->default(100);
            $table->integer('terpakai')->default(0);
            $table->string('khusus')->default('semua'); // semua, reseller, enduser
            $table->boolean('is_active')->default(true);
            $table->date('berlaku_sampai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
