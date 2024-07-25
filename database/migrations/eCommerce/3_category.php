<?php
    
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
    
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create('ecommerce_category', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->longText('category_description');
            $table->string('category_image');
            $table->timestamps();
        });

        Schema::create('ecommerce_sub_category', function (Blueprint $table) {
            $table->id();
            $table->string('sub_category_name'); // Adjusted field name to differentiate from main category
            $table->longText('sub_category_description');
            $table->string('sub_category_image');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('ecommerce_category')->onDelete('cascade');
    
        });
        
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_category');
    }
};