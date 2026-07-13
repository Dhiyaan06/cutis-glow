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
        Schema::create('riwayat_layanan', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->foreignId('id_booking')->nullable()->constrained('booking_konsultasi', 'id_booking')->onDelete('set null');
            $table->foreignId('id_pasien')->constrained('pasien', 'id_pasien')->onDelete('cascade');
            $table->foreignId('id_dokter')->constrained('dokter', 'id_dokter')->onDelete('cascade');
            $table->foreignId('id_layanan')->constrained('master_layanan', 'id_layanan')->onDelete('cascade');
            $table->date('tanggal_treatment');
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->text('catatan')->nullable();
            $table->decimal('harga', 15, 2);
            $table->integer('qty')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_layanan');
    }
};
