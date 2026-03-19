<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            
            // Header Info
            $table->string('return_number')->unique(); // No Retur
            $table->date('return_date'); // Tgl Retur
            
            // Relasi
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('purchase_transaction_id')->constrained('purchase_transactions'); // Faktur Beli
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->foreignId('user_id')->constrained('users');
            
            // Info Tambahan
            $table->string('city')->nullable(); // Kota (Sesuai Gambar)
            $table->string('return_type')->default('invoice'); // Jenis Retur
            $table->text('notes')->nullable(); // Keterangan Retur
            
            // Keuangan Footer
            $table->decimal('subtotal', 15, 2)->default(0);
            
            // Diskon Global
            $table->decimal('global_discount_pct', 5, 2)->default(0); // Diskon %
            $table->decimal('global_discount_amount', 15, 2)->default(0); // Diskon Rp
            
            // Pajak
            $table->decimal('tax_pct', 5, 2)->default(0); // PPN %
            $table->decimal('tax_amount', 15, 2)->default(0); // Nilai PPN
            
            // Biaya Lain
            $table->decimal('shipping_cost', 15, 2)->default(0); // Biaya Ekspedisi
            $table->decimal('other_cost', 15, 2)->default(0); // Biaya Lain
            
            // Total & Pembayaran
            $table->decimal('grand_total', 15, 2)->default(0); // Saldo Terhutang
            $table->decimal('cash_refund', 15, 2)->default(0); // Dibayar / Uang Muka
            $table->decimal('balance_due', 15, 2)->default(0); // Sisa Saldo
            
            $table->string('status')->default('approved'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};