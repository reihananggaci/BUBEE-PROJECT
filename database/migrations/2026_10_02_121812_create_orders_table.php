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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kode')->unique();
            $table->integer('total_produk')->default(0);
            $table->integer('ongkir')->default(0);
            $table->integer('total_bayar')->default(0);
            $table->string('kurir')->default('JNE'); // JNE, JNT, SPX
            $table->string('layanan')->default('REG');
            $table->string('resi')->nullable();
            $table->string('status')->default('menunggu_bayar'); // menunggu_bayar,diproses,dikirim,selesai,batal
            $table->text('alamat')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('hp_penerima')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
