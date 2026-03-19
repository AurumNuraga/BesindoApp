<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_transactions', function (Blueprint $table) {
            $table->id();
            
            // Header Info
            $table->string('purchase_code')->unique()->nullable(); // No Faktur Internal (Auto)
            $table->string('supplier_invoice_number')->nullable(); // No Faktur Supplier (Manual)
            $table->string('purchase_order_number')->nullable(); // No P.O
            $table->string('tax_number')->nullable(); // No Pajak
            $table->string('supplier_fax')->nullable(); // Nota Supplier (Sesuai gambar)
            
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('user_id')->constrained('users'); // Admin Input
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->foreignId('purchase_category_id')->constrained('purchase_categories'); // Jenis Transaksi
            
            // Tanggal & Kredit
            $table->date('purchase_date'); // Tgl Faktur
            $table->integer('credit_days')->default(0); // Lama Kredit
            $table->date('due_date')->nullable(); // Tgl Overdue
            
            // Keuangan Footer
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_rate', 5, 2)->default(0); // PPN (%)
            $table->decimal('tax_amount', 15, 2)->default(0); // Nilai PPN (Rp)
            
            $table->decimal('discount_percent', 5, 2)->default(0); // Diskon Bawah (%)
            $table->decimal('discount_amount', 15, 2)->default(0); // Diskon Bawah (Rp)
            
            $table->decimal('shipping_cost', 15, 2)->default(0); // Biaya Ekspedisi
            $table->decimal('other_expense', 15, 2)->default(0); // Biaya Lain
            
            $table->decimal('done_payment', 15, 2)->default(0); // Dibayar / Uang Muka
            $table->decimal('grand_total', 15, 2); // Saldo Terhutang (Total Akhir)
            
            $table->text('notes')->nullable();
            $table->string('status')->default('Received');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_transactions');
    }
};