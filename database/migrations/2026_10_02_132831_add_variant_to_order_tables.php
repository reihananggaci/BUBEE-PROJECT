<?php

use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMutation;
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
        if (! Schema::hasColumn('carts', 'variant_id')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        } else {
            // Kolom sudah ada dari percobaan gagal -> pastikan FK-nya ada
            try {
                Schema::table('carts', function (Blueprint $table) {
                    $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
                });
            } catch (Throwable $e) {
            }
        }
        // Unique lama per produk -> per varian
        try {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropUnique(['user_id', 'product_id']);
            });
        } catch (Throwable $e) {
        }
        try {
            Schema::table('carts', function (Blueprint $table) {
                $table->unique(['user_id', 'product_id', 'variant_id'], 'carts_user_product_variant_unique');
            });
        } catch (Throwable $e) {
        }
        if (! Schema::hasColumn('order_items', 'variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        }
        if (! Schema::hasColumn('stock_mutations', 'variant_id')) {
            Schema::table('stock_mutations', function (Blueprint $table) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        }

        // Pindah stok lama -> varian default per produk
        $products = Product::all();
        foreach ($products as $p) {
            $v = ProductVariant::firstOrCreate(
                ['product_id' => $p->id, 'ukuran' => 'All Size', 'warna' => '-'],
                ['stok' => $p->stok, 'sku' => 'BB-'.$p->id.'-OS']
            );
            Cart::where('product_id', $p->id)->whereNull('variant_id')->update(['variant_id' => $v->id]);
            OrderItem::where('product_id', $p->id)->whereNull('variant_id')->update(['variant_id' => $v->id]);
            StockMutation::where('product_id', $p->id)->whereNull('variant_id')->update(['variant_id' => $v->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_mutations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('variant_id');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('variant_id');
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('variant_id');
        });
    }
};
