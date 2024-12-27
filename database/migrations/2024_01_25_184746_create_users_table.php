<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('usertype')->comment('1 = Operator Ambulance, 2 = Operator Hospital, 3 = Operator System');
            $table->unsignedBigInteger('hospital_id')->nullable()->comment('Only for Operator Hospital'); // Relasi ke tabel hospitals
            $table->timestamps();

            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
