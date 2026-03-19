<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_transaction_id')->constrained('sale_transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            
            $table->string('unit')->nullable(); // Satuan
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 15, 2); // Harga Jual
            
            // Diskon Bertingkat per Item
            $table->decimal('disc_1', 5, 2)->default(0); // % 1
            $table->decimal('disc_2', 5, 2)->default(0); // % 2
            $table->decimal('disc_reg', 15, 2)->default(0); // Disc Reg (Rp)
            $table->decimal('disc_promo', 15, 2)->default(0); // Disc Promo (Rp)
            
            $table->decimal('subtotal', 15, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_transaction_details');
    }
};