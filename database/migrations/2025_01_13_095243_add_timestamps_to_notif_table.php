<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notif', function (Blueprint $table) {
            if (!Schema::hasColumn('notif', 'updated_at')) {
                $table->timestamp('updated_at')->nullable(); // Tambahkan kolom updated_at
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notif', function (Blueprint $table) {
            $table->dropColumn('updated_at'); // Hapus kolom updated_at jika rollback
        });
    }
};
