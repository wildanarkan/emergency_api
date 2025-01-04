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


            // kolom baru
            $table->timestamp('time_incident')->useCurrent();
            $table->text('mechanism');
            $table->text('injury');
            $table->text('photo_injury');
            $table->text('treatment');
            // --
            
            $table->string('desc');
            $table->timestamp('arrival')->useCurrent();
            $table->unsignedBigInteger('hospital_id')->nullable()->comment('empty if no hospital care is required');
            $table->unsignedBigInteger('user_id');
            $table->integer('status')->comment('1:Menuju RS / 2:Selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient');
    }
};
