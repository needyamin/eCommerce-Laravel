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
        Schema::create('ecommerce_cart', function (Blueprint $table) {
            $table->id();
            $table->string('session_id'); 
            $table->string('user_id')->nullable(); 
            $table->unsignedBigInteger('product_id'); 
            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2); 
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('ecommerce_products')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_cart');
    }
};