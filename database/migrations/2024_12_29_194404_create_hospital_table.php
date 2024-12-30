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
        Schema::create('hospital', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('hospital');
    }
    
};
