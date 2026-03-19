<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_return_id')->constrained('purchase_returns')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('purchase_transaction_detail_id')->constrained('purchase_transaction_details');
            
            $table->string('unit')->nullable(); // Satuan
            $table->decimal('price', 15, 2); // Harga Beli
            $table->decimal('quantity', 10, 2); // Qty Retur
            
            // Diskon Retur (Biasanya ikut faktur beli)
            $table->decimal('disc_1', 5, 2)->default(0); // % 1
            $table->decimal('disc_2', 5, 2)->default(0); // % 2
            $table->decimal('disc_rp', 15, 2)->default(0); // Disc Rp
            
            $table->decimal('subtotal', 15, 2); // Subtotal Baris
            $table->decimal('capital_price', 15, 2)->default(0); // Modal (Info saja)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_return_details');
    }
};