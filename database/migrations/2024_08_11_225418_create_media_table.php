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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->string('slug')->nullable();
            $table->integer('jumlah_dibaca')->default(0);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->integer('telegram_msg_id')->nullable();
            $table->string('judul')->unique();
            $table->string('slug');
            $table->text('konten');
            $table->string('gambar')->nullable();
            $table->boolean('pilihan')->default(0);
            $table->integer('jumlah_dibaca')->default(0);
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tag')->unique();
            $table->string('slug')->nullable();
            $table->integer('jumlah_penggunaan')->default(0);
            $table->timestamps();
        });

        Schema::create('media_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->string('perangkat');
            $table->string('komentar');
            $table->boolean('spam')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('media_tags');
        Schema::dropIfExists('comments');
    }
};
