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
    Schema::create('receivable_payment_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('receivable_payment_id')->constrained('receivable_payments')->onDelete('cascade');
        
        // Referensi ke Faktur Penjualan
        $table->foreignId('sale_transaction_id')->constrained('sale_transactions'); 
        
        $table->decimal('amount_paid', 15, 2); // Nominal yang dibayar untuk faktur ini
        $table->string('notes')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivable_payment_details');
    }
};
