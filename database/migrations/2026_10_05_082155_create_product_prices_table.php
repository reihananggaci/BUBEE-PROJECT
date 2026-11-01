<?php

use App\Models\Product;
use App\Models\ProductPrice;
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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('level', 20); // user, reseller
            $table->integer('min_qty')->default(1);
            $table->integer('price')->default(0);
            $table->timestamps();
            $table->unique(['product_id', 'level', 'min_qty']);
        });

        // Backfill: ubah 2 kolom lama jadi 2 tier awal per produk
        foreach (Product::all() as $p) {
            ProductPrice::firstOrCreate(
                ['product_id' => $p->id, 'level' => 'user', 'min_qty' => 1],
                ['price' => $p->harga_enduser]
            );
            ProductPrice::firstOrCreate(
                ['product_id' => $p->id, 'level' => 'reseller', 'min_qty' => max(1, (int) $p->min_reseller)],
                ['price' => $p->harga_reseller]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
