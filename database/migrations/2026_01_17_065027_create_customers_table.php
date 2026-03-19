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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->foreignId('category_id')->constrained('customer_categories')->onDelete('cascade');
            $table->string('status')->default('Active')->nullable();

            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('telephone')->nullable();
            $table->string('hp')->nullable();
            $table->string('hp2')->nullable();
            $table->string('fax')->nullable();

            $table->string('tax_name')->nullable();
            $table->string('npw')->nullable();
            $table->string('nppkp')->nullable();
            
            $table->string('ekspedisi')->nullable();
            $table->string('account_number')->nullable();
            $table->text('information')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};