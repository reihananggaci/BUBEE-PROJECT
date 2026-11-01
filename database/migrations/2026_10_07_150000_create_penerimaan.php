<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('penerimaan')) {
            Schema::create('penerimaan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('no_surat_jalan', 50);
                $table->string('supplier', 100)->nullable();
                $table->string('status', 20)->default('dicatat'); // dicatat|diverifikasi
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('penerimaan_items')) {
            Schema::create('penerimaan_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penerimaan_id')->constrained('penerimaan')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
                $table->integer('qty_klaim')->default(0);
                $table->integer('qty_lolos')->default(0);
                $table->integer('qty_rusak')->default(0);
                $table->string('catatan', 200)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_items');
        Schema::dropIfExists('penerimaan');
    }
};
