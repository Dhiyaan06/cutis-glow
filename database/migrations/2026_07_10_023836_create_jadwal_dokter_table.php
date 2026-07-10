<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_dokter', function (Blueprint $table) {
            $table->id('id_jadwal');

            $table->foreignId('id_dokter')
                  ->constrained('dokters', 'id_dokter')
                  ->cascadeOnDelete();

            $table->string('hari');

            $table->time('jam_mulai');

            $table->time('jam_selesai');

            $table->enum('status', [
                'tersedia',
                'tidak tersedia'
            ])->default('tersedia');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_dokter');
    }
};
