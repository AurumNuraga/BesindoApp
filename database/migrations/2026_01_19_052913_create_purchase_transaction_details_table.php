<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchase_transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            
            $table->string('unit')->nullable(); // Satuan
            $table->decimal('quantity', 10, 2); // Qty
            $table->decimal('price', 15, 2); // Harga
            
            // Diskon Bertingkat per Item
            $table->decimal('discount_1', 5, 2)->default(0); // % 1
            $table->decimal('discount_2', 5, 2)->default(0); // % 2
            $table->decimal('discount_rp', 15, 2)->default(0); // Disc Rp
            
            $table->decimal('subtotal', 15, 2); // Subtotal Baris
            $table->decimal('capital_price', 15, 2)->default(0); // Modal (Opsional/Info)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_transaction_details');
    }
};