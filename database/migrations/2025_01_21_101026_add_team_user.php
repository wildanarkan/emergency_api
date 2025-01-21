<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user', function ($table) {
            $table->string('team')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('user', function ($table) {
            $table->dropColumn('team');
        });
    }
};
