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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('kota_tujuan')->nullable()->after('alamat');
            $table->string('city_id')->nullable()->after('kota_tujuan');
            $table->string('ongkir_sumber')->default('dummy')->after('ongkir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kota_tujuan', 'city_id', 'ongkir_sumber']);
        });
    }
};
