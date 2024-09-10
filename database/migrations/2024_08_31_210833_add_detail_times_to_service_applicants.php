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
        Schema::table('service_applicants', function (Blueprint $table) {
            $table->timestamp('waktu_respon')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_applicants', function (Blueprint $table) {
            $table->dropColumn(['waktu_respon', 'waktu_selesai']);
        });
    }
};
