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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('periodo', 30); // Ej: Mayo - Agosto 2026
            $table->unsignedTinyInteger('parcial')->default(1); // parcial 1 o 2
            $table->enum('tipo_evaluacion',['ordinario', 'remedial', 'extraordinario'])->default('ordinario');
            $table->decimal('calificacion', 4, 2);
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Restricción que evita la combinacion exacta de las 3 cosas
            $table->unique(['alumno_id', 'materia_id', 'periodo', 'parcial', 'tipo_evaluacion'], 'calificacion_unica_periodo_parcial_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
