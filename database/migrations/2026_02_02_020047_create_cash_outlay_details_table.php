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
    Schema::create('cash_outlay_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cash_outlay_id')->constrained('cash_outlays')->onDelete('cascade');
        
        $table->foreignId('outlay_account_id')->constrained('outlay_accounts'); // Akun Biaya (Debet)
        $table->decimal('amount', 15, 2); // Nominal per item
        $table->string('notes')->nullable(); // Keterangan per item
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_outlay_details');
    }
};
