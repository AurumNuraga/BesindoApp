<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. MASTER AKUN (Tetap Ada)
        Schema::create('journal_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); 
            $table->string('name');
            $table->string('type')->default('UMUM');
            $table->timestamps();
        });

        // 2. TRANSAKSI JURNAL (Single Table)
        Schema::create('general_journals', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique(); // No Bukti (PEB)
            $table->date('transaction_date');
            
            $table->decimal('total_amount', 15, 2)->default(0); // Total Nilai Transaksi
            $table->text('description')->nullable(); // Keterangan Umum
            
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_journals');
        Schema::dropIfExists('journal_accounts');
    }
};