<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('opname_sesi')) {
            Schema::create('opname_sesi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('cakupan', 50)->default('semua');
                $table->string('status', 20)->default('jalan'); // jalan|selesai|batal
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('opname_items')) {
            Schema::create('opname_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sesi_id')->constrained('opname_sesi')->cascadeOnDelete();
                $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
                $table->integer('snapshot')->default(0);
                $table->integer('fisik')->nullable();
                $table->string('foto')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('opname_items');
        Schema::dropIfExists('opname_sesi');
    }
};
