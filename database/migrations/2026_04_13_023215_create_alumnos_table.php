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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('matricula', 10)->unique();
            $table->string('curp', 18)->unique();
            $table->enum('sexo', ['M', 'F', 'Otro']);
            $table->date('fecha_nacimiento');
            $table->string('telefono', 20);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('carrera_id')->constrained('carreras')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
