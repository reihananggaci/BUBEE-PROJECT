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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('enduser')->after('email'); // admin,reseller,enduser
            $table->string('status_reseller')->default('none')->after('role'); // none,pending,approved,rejected
            $table->string('hp')->nullable()->after('status_reseller');
            $table->text('alamat')->nullable()->after('hp');
            $table->string('ktp_foto')->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status_reseller', 'hp', 'alamat', 'ktp_foto']);
        });
    }
};
