<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('debt_payments', function (Blueprint $table) {
        $table->id();
        $table->string('payment_number')->unique(); // No Bukti Bayar
        $table->date('payment_date');
        $table->foreignId('supplier_id')->constrained('suppliers');
        $table->foreignId('cash_account_id')->constrained('cash_accounts'); // Sumber Kas
        $table->boolean('is_giro_cek')->default(false);
        
        $table->decimal('total_amount', 15, 2)->default(0); // Total Bayar
        $table->text('global_note')->nullable();
        
        $table->foreignId('user_id')->constrained('users');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('debt_payments');
    }
};