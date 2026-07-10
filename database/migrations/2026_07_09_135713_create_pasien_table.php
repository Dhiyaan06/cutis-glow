<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {

            $table->id('id_pasien');

            $table->unsignedBigInteger('id_pengguna');

            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin',['Laki-laki','Perempuan']);
            $table->string('no_hp',20);
            $table->text('alamat');

            $table->timestamps();

           $table->foreign('id_pengguna')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
