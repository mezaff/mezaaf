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
            // Ubah tipe data menjadi bigInteger
            $table->bigInteger('regular_price')->change();
            $table->bigInteger('sale_price')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Kembalikan ke tipe data decimal(8, 2) jika perlu rollback
            $table->decimal('regular_price', 8, 2)->change();
            $table->decimal('sale_price', 8, 2)->nullable()->change();
        });
    }
};
