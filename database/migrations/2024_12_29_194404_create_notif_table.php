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
        Schema::create('notif', function (Blueprint $table) {
            $table->id();
            $table->string('desc');
            $table->unsignedBigInteger('user_id');
            $table->integer('status')->comment('1:unread / 2:read');
            $table->timestamp('created_at')->useCurrent();
    
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('notif');
    }
    
};
