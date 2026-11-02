<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedInteger('harga_enduser')->nullable()->after('stok');
            $table->unsignedInteger('harga_reseller')->nullable()->after('harga_enduser');
            $table->unsignedInteger('berat_gram')->nullable()->after('harga_reseller');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['harga_enduser', 'harga_reseller', 'berat_gram']);
        });
    }
};
