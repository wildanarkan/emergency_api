<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->text('symptom')->after('photo_injury');
            $table->text('request')->after('hospital_id');
            
            // Pindahkan kolom
            $table->integer('case')->comment('1:non trauma / 2:trauma')->after('user_id')->change();

            // Hapus kolom jika diperlukan
            $table->dropColumn('desc');
        });
    }

    public function down(): void
    {
        Schema::table('patient', function (Blueprint $table) {
            // Balikkan perubahan jika diperlukan
            $table->dropColumn('symptom');
            $table->dropColumn('request');
            $table->integer('case')->comment('1:non trauma / 2:trauma')->after('age')->change();
            $table->string('desc')->after('treatment');
        });
    }
};
