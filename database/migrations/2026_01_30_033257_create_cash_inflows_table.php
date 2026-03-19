<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('cash_inflows', function (Blueprint $table) {
        $table->id();
        $table->string('inflow_number')->unique(); // No BKM
        $table->date('inflow_date');
        $table->foreignId('cash_account_id')->constrained('cash_accounts'); // Masuk ke Kas Mana
        $table->string('depositor_name')->nullable(); // Penyetor
        
        $table->string('inflow_type')->default('KANTOR'); // KANTOR / SALES / PROJECT
        $table->foreignId('sales_id')->nullable()->constrained('users');
        $table->boolean('is_giro_cek')->default(false);
        
        $table->decimal('total_amount', 15, 2)->default(0); // Total nominal semua detail
        $table->text('global_note')->nullable(); // Catatan umum
        
        $table->foreignId('user_id')->constrained('users'); // Admin Input
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('cash_inflows');
    }
};