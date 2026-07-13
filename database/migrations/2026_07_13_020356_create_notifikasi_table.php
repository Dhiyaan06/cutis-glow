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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_pengguna')->constrained('users', 'id_pengguna')->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['booking', 'sistem', 'promo'])->default('sistem');
            $table->enum('status_baca', ['belum_dibaca', 'dibaca'])->default('belum_dibaca');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
