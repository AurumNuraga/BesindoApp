<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained('sale_returns')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('sale_transaction_detail_id')->nullable()->constrained('sale_transaction_details');
            
            $table->string('unit')->nullable(); // Satuan
            $table->decimal('price', 15, 2); // Harga Jual
            $table->decimal('quantity', 10, 2); // Qty Retur
            
            // Diskon saat retur (biasanya mengikuti faktur asal)
            $table->decimal('disc_1', 5, 2)->default(0); // % I
            $table->decimal('disc_2', 5, 2)->default(0); // % II
            $table->decimal('disc_reg', 15, 2)->default(0); // Disc Reg
            $table->decimal('disc_trm', 15, 2)->default(0); // Disc TRM (Khusus Retur)
            
            $table->decimal('subtotal', 15, 2); // Subtotal Baris
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_return_details');
    }
};