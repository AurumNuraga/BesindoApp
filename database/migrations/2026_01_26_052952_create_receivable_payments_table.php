<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('receivable_payments', function (Blueprint $table) {
        $table->id();
        $table->string('payment_number')->unique(); // No BKM
        $table->date('payment_date');
        $table->foreignId('customer_id')->constrained('customers');
        
        $table->foreignId('collector_id')->nullable()->constrained('users'); // Kolektor
        $table->foreignId('sales_id')->nullable()->constrained('users');     // Sales
        $table->foreignId('cash_account_id')->constrained('cash_accounts'); // Masuk Kas Mana
        $table->boolean('is_giro_cek')->default(false);
        
        $table->decimal('total_amount', 15, 2)->default(0); // Total Terima
        $table->text('global_note')->nullable();
        
        $table->foreignId('user_id')->constrained('users');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('receivable_payments');
    }
};