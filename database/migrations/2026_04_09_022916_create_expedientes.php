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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('carrera_id')->constrained('carreras');
            $table->foreignId('grupo_id')->nullable()->constrained('grupos');
            $table->string('matricula', 20)->unique();
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('curp', 18)->unique();
            $table->integer('edad');
            $table->string('sexo');
            $table->date('fecha_nacimiento');
            $table->string('telefono');
            $table->decimal('promedio_general', 4, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
