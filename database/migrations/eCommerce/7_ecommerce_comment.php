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
        Schema::create('ecommerce_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('product_id'); 
            $table->integer('rating')->unsigned();
            $table->text('comment');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('ecommerce_users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('ecommerce_products')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecommerce_comment');
    }
};
