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
        Schema::create('ecommerce_orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); 
            $table->unsignedBigInteger('product_id'); 
            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2); 
            $table->string('customer_name'); 
            $table->string('customer_phone'); 
            $table->string('customer_address'); 
            $table->string('payment_method'); 
            $table->string('trx_id'); 
            $table->string('ip_address'); 
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('ecommerce_products')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_orders');
    }
};