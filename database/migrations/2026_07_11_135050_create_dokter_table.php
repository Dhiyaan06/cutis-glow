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
        Schema::create('dokter', function (Blueprint $table) {
            $table->id('id_dokter');
            
            $table->string('nama');
            $table->string('spesialis');
            $table->string('no_str'); // Menggunakan string agar format nomor STR aman
            $table->string('no_hp');
            $table->text('alamat');
            $table->string('jadwal_praktek');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
