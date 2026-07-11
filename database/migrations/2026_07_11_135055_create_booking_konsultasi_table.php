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
        Schema::create('booking_konsultasi', function (Blueprint $table) {
            $table->id('id_booking');
            // Relasi ke tabel pasien dan dokter
            $table->foreignId('id_pasien')->constrained('pasien', 'id_pasien')->onDelete('cascade');
            $table->foreignId('id_dokter')->constrained('dokter', 'id_dokter')->onDelete('cascade');
            $table->dateTime('jadwal_konsultasi');
            $table->enum('status_booking', ['pending', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('keluhan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_konsultasi');
    }
};
