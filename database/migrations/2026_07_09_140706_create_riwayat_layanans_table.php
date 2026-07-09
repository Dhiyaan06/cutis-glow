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

            $table->foreignId('id_booking')
                ->constrained('booking_konsultasis', 'id_booking')
                ->cascadeOnDelete();

            $table->foreignId('id_pasien')
                ->constrained('pasiens', 'id_pasien')
                ->cascadeOnDelete();

            $table->foreignId('id_dokter')
                ->constrained('dokters', 'id_dokter')
                ->cascadeOnDelete();

            $table->foreignId('id_masterlayanan')
                ->constrained('master_layanans', 'id_masterlayanan')
                ->cascadeOnDelete();

            $table->date('tanggal_treatment');

            $table->enum('status', [
                'proses',
                'selesai',
                'batal'
            ]);

            $table->text('catatan')->nullable();

            $table->decimal('harga', 10, 2);

            $table->integer('qty')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_layanans');
    }
};
