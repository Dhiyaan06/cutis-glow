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
        Schema::create('service_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('booking_id')
                  ->constrained('booking_consultations')
                  ->cascadeOnDelete();

            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                  ->constrained('doctors')
                  ->cascadeOnDelete();

            $table->foreignId('service_id')
                  ->constrained('master_services')
                  ->cascadeOnDelete();

            $table->date('treatment_date');

            $table->enum('status', [
                'process',
                'completed',
                'cancelled'
            ]);

            $table->text('notes')->nullable();

            $table->decimal('price', 10, 2);

            $table->integer('qty')->default(1);

            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_histories');
    }
};
