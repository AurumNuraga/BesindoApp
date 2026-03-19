<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_transactions', function (Blueprint $table) {
            $table->id();
            
            // Header Info
            $table->string('invoice_code')->unique(); // No Faktur (801-JLT...)
            $table->string('transaction_type')->default('Credit'); // Cash & Carry / Credit
            $table->integer('credit_days')->default(0); // Lama Kredit (Hari)
            $table->date('transaction_date'); // Tgl Faktur
            $table->date('due_date')->nullable(); // s/d Tgl Jatuh Tempo
            $table->string('manual_invoice_number')->nullable(); // Faktur Manual
            
            // Relasi
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('user_id')->constrained('users'); // Salesman
            $table->foreignId('warehouse_id')->constrained('warehouses');
            
            // Rayon / Wilayah (Jika ada tabel rayon, pakai foreignId. Jika string, pakai string)
            $table->string('rayon_code')->nullable(); // Kode Rayon
            $table->string('rayon_name')->nullable(); // Nama Rayon
            $table->string('city')->nullable(); // Kota
            
            // Keuangan Header
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount_percent', 5, 2)->default(0); // Diskon Bawah (%)
            $table->decimal('discount_amount', 15, 2)->default(0); // Diskon Bawah (Rp)
            $table->decimal('subtotal_after_disc', 15, 2); // Subtotal - Diskon
            $table->decimal('shipping_cost', 15, 2)->default(0); // Biaya Ekspedisi
            $table->decimal('other_cost', 15, 2)->default(0); // Biaya Lain
            $table->decimal('down_payment', 15, 2)->default(0); // Dibayar / Uang Muka
            $table->decimal('grand_total', 15, 2); // Saldo Terhutang
            
            $table->text('notes')->nullable(); // Memo untuk Pelanggan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_transactions');
    }
};