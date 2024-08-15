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
        Schema::create('list_services', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->integer('jumlah_pengajuan')->default(0);
            $table->timestamps();
        });
        
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')->constrained('list_services')->cascadeOnDelete();
            $table->string('judul');
            $table->string('file_sop')->nullable();
            $table->string('file_permohonan')->nullable();
            $table->timestamps();
        });

        Schema::create('service_applicants', function (Blueprint $table) {
            $table->id();
            $table->string('kode_layanan')->unique();
            $table->foreignId('list_id')->constrained('list_services')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('nama');
            $table->string('email');  
            $table->string('pesan_pengirim');
            $table->string('pesan_balasan')->nullable();
            $table->string('diproses_oleh')->nullable();
            $table->string('file_persyaratan')->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->integer('rating')->default(0);
            $table->timestamps();
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('tipe_feedback');
            $table->string('url');
            $table->string('pesan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_applicants');
        Schema::dropIfExists('list_services');
        Schema::dropIfExists('feedback');
    }
};
