<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // php artisan make:migration create_general_journal_details_table
public function up(): void
{
    Schema::create('general_journal_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('general_journal_id')->constrained('general_journals')->onDelete('cascade');
        
        $table->foreignId('credit_account_id')->constrained('journal_accounts'); // Sumber
        $table->foreignId('debit_account_id')->constrained('journal_accounts');  // Tujuan

        $table->decimal('amount', 15, 2);
        $table->string('memo')->nullable(); // Ket per baris
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_journal_details');
    }
};
