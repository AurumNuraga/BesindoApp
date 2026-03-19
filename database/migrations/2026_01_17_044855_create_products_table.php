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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('package_categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brand_categories')->onDelete('cascade');
            
            $table->string('group')->nullable();
            $table->string('barcode')->nullable();
            $table->string('tax')->nullable();
            
            $table->integer('unit_per_product')->nullable();
            $table->integer('unit_per_koli')->nullable();
            
            $table->decimal('sell_price', 15, 2)->nullable();
            $table->decimal('capital_price', 15, 2)->nullable();
            $table->decimal('expedition_price', 15, 2)->nullable();

            $table->string('color')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            
            $table->string('location')->nullable();
            $table->string('status')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
