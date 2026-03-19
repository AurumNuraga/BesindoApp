<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            
            // Header Info
            $table->string('return_number')->unique(); // No Retur
            $table->date('return_date');
            
            // Relasi
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('sale_transaction_id')->nullable()->constrained('sale_transactions');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->foreignId('user_id')->constrained('users'); // Admin Input
            
            // Info Tambahan sesuai Gambar
            $table->string('city')->nullable(); // Kota
            $table->string('return_type')->default('invoice'); // Jenis Retur: Dengan Nomor Faktur
            $table->string('item_condition')->default('good'); // Kondisi Barang: Layak Jual / Rusak
            $table->text('notes')->nullable(); // Keterangan Retur
            
            $table->string('ttg_number')->nullable(); // No. TTG
            $table->date('ttg_date')->nullable(); // Tgl. TTG
            
            // Keuangan Footer
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('global_discount_pct', 5, 2)->default(0); // Diskon %
            $table->decimal('global_discount_amount', 15, 2)->default(0); // Diskon Rp
            $table->decimal('tax_pct', 5, 2)->default(0); // PPN %
            $table->decimal('tax_amount', 15, 2)->default(0); // Nilai PPN
            
            $table->decimal('shipping_cost', 15, 2)->default(0); // Biaya Ekspedisi
            $table->decimal('other_cost', 15, 2)->default(0); // Biaya Lain
            
            $table->decimal('grand_total', 15, 2)->default(0); // Saldo Terhutang (Credit Note)
            $table->decimal('cash_refund', 15, 2)->default(0); // Dibayar/Uang Muka
            $table->decimal('balance', 15, 2)->default(0); // Sisa
            
            $table->string('status')->default('approved'); // approved/draft
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};