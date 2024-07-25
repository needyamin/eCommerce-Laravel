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
        Schema::create('core_status', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->integer('code');
            $table->string('type');
            $table->timestamps();

            $table->unique(['code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_status_table');
    }
};
