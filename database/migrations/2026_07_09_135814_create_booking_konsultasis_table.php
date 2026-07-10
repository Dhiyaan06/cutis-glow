<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('booking_konsultasi', function (Blueprint $table) {

            $table->id('id_booking');

            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_dokter');
            $table->unsignedBigInteger('id_jadwal');

            $table->date('tanggal_booking');
            $table->time('jam_booking');

            $table->enum('status',[
                'Menunggu',
                'Dikonfirmasi',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu');

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->foreign('id_pasien')
                ->references('id_pasien')
                ->on('pasien')
                ->cascadeOnDelete();

            $table->foreign('id_dokter')
                ->references('id_dokter')
                ->on('dokter')
                ->cascadeOnDelete();

            $table->foreign('id_jadwal')
                ->references('id_jadwal')
                ->on('jadwal_dokter')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_konsultasi');
    }
};
