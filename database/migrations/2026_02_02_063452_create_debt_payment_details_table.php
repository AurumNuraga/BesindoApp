<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // php artisan make:migration create_debt_payment_details_table
public function up(): void
{
    Schema::create('debt_payment_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('debt_payment_id')->constrained('debt_payments')->onDelete('cascade');
        
        // Referensi ke Faktur Pembelian
        $table->foreignId('purchase_transaction_id')->constrained('purchase_transactions'); 
        
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
        Schema::dropIfExists('debt_payment_details');
    }
};
