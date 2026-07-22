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
        Schema::create('maestros_carreras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maestro_id')->constrained('maestros')->onDelete('cascade'); 
            $table->foreignId('carrera_id')->constrained('carreras')->onDelete('cascade');
            $table->timestamps();

            // Restriccion que evita duplicidad de registro
            $table->unique(['maestro_id', 'carrera_id'], 'maestro_carrera_unico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maestros_carreras');
    }
};
