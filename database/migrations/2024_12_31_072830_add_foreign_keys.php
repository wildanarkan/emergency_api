<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hospital', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onDelete('cascade');
        });

        Schema::table('notif', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onDelete('cascade');
        });

        Schema::table('patient', function (Blueprint $table) {
            $table->foreign('hospital_id')
                  ->references('id')
                  ->on('hospital')
                  ->onDelete('cascade');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('user')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('patient', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('notif', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('hospital', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};