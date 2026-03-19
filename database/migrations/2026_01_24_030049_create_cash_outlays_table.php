<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('cash_outlays', function (Blueprint $table) {
        $table->id();
        $table->string('outlay_code')->unique(); // No Bukti (BKK)
        $table->date('transaction_date');
        $table->foreignId('cash_account_id')->constrained('cash_accounts'); // Sumber Kas (Kredit)
        $table->string('receiver')->nullable(); // Penerima Uang
        
        $table->string('outlay_type')->default('KANTOR'); // KANTOR / SALES
        $table->foreignId('sales_id')->nullable()->constrained('users');
        $table->boolean('is_giro_cek')->default(false);
        
        $table->decimal('total_amount', 15, 2)->default(0); // Total Nominal Transaksi
        $table->text('global_note')->nullable(); // Keterangan Umum
        
        $table->foreignId('user_id')->constrained('users'); // Admin Input
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('cash_outlays');
    }
};