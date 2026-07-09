<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_konsultasis', function (Blueprint $table) {

            $table->id('id_booking');

            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_dokter');

            $table->date('tanggal_booking');
            $table->time('jam_booking');

            $table->enum('status', [
                'menunggu',
                'diproses',
                'selesai',
                'dibatalkan'
            ])->default('menunggu');

            $table->string('catatan')->nullable();

            $table->timestamps();

            $table->foreign('id_pasien')
                  ->references('id_pasien')
                  ->on('pasiens')
                  ->onDelete('cascade');

            $table->foreign('id_dokter')
                  ->references('id_dokter')
                  ->on('dokters')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_konsultasis');
    }
};
