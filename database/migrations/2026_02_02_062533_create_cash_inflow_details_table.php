<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Buat migration baru: php artisan make:migration create_cash_inflow_details_table
public function up(): void
{
    Schema::create('cash_inflow_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cash_inflow_id')->constrained('cash_inflows')->onDelete('cascade');
        
        $table->foreignId('income_account_id')->constrained('income_accounts'); // Akun Pendapatan (Kredit)
        $table->decimal('amount', 15, 2); // Nominal per item
        $table->string('description')->nullable(); // Keterangan per item
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_inflow_details');
    }
};
