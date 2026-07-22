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
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('carrera_id')->constrained('carreras')->onDelete('cascade');
            $table->timestamps();

            // Restriccion que evita duplicidad de materias
            $table->unique(['nombre', 'carrera_id'], 'materia_carrera_unica');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
