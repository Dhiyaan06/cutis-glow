<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokters', function (Blueprint $table) {
            $table->id('id_dokter');

            $table->foreignId('id_pengguna')
                ->constrained('penggunas', 'id_pengguna')
                ->cascadeOnDelete();

            $table->string('spesialis');
            $table->string('no_str')->nullable();
            $table->string('no_hp');
            $table->text('alamat')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokters');
    }
};
