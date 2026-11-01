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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('panjang_cm')->default(0)->after('berat_gram');
            $table->integer('lebar_cm')->default(0)->after('panjang_cm');
            $table->integer('tinggi_cm')->default(0)->after('lebar_cm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['panjang_cm', 'lebar_cm', 'tinggi_cm']);
        });
    }
};
