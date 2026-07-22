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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 10);
            $table->enum('grado', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11']);
            $table->foreignId('carrera_id')->constrained('carreras')->onDelete('cascade');
            $table->timestamps();

            // Restriccion que evita duplicidad de grupos
            $table->unique(['nombre', 'carrera_id'], 'grupo_carrera_unico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
