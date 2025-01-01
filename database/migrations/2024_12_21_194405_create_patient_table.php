<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
            $table->integer('gender')->comment('1:male / 2:female');
            $table->integer('case')->comment('1:non trauma / 2:trauma');
            $table->string('desc');
            $table->timestamp('arrival')->useCurrent();
            $table->unsignedBigInteger('hospital_id')->nullable()->comment('empty if no hospital care is required');
            $table->unsignedBigInteger('user_id');
            $table->integer('status')->comment('1:menuju lokasi / 2:rujukan / 3:selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient');
    }
};
