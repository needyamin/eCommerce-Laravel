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
        Schema::create('ecommerce_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('name');
            $table->string('name');
            $table->longtext('detail');
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('actual_price')->nullable();
            $table->string('offer_percentage')->nullable();
            $table->unsignedInteger('stock_quantity');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('brand')->nullable();
            $table->timestamps();
            //$table->softDeletes();

            // Define foreign key relationship
            $table->foreign('category_id')->references('id')->on('ecommerce_category')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('ecommerce_vendors')->onDelete('cascade');
        });
        
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_products');
    }
};