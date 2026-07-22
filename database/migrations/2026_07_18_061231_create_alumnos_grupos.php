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
        Schema::create('alumnos_grupos', function (Blueprint $table) {
            $table->id();
            $table->string('periodo');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->timestamps();

            // El alumno solo puede pertenecer a UN grupo por periodo escolar
            $table->unique(['alumno_id', 'periodo'], 'alumno_grupo_unico_periodo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos_grupos');
    }
};
