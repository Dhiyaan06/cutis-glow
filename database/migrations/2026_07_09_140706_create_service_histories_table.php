<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_layanans', function (Blueprint $table) {

            $table->id('id_riwayat');

            $table->unsignedBigInteger('id_booking');
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_dokter');
            $table->unsignedBigInteger('id_masterlayanan');

            $table->date('tanggal_treatment');

            $table->enum('status', [
                'proses',
                'selesai',
                'batal'
            ]);

            $table->text('catatan')->nullable();

            $table->decimal('price',10,2);

            $table->integer('qty')->default(1);

            $table->decimal('total',10,2);

            $table->timestamps();

            $table->foreign('id_booking')
                  ->references('id_booking')
                  ->on('booking_konsultasis')
                  ->onDelete('cascade');

            $table->foreign('id_pasien')
                  ->references('id_pasien')
                  ->on('pasiens')
                  ->onDelete('cascade');

            $table->foreign('id_dokter')
                  ->references('id_dokter')
                  ->on('dokters')
                  ->onDelete('cascade');

            $table->foreign('id_masterlayanan')
                  ->references('id')
                  ->on('master_services')
                  ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_layanans');
    }
};
