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
        Schema::create('maestros', function (Blueprint $table) {
            $table->id();
            $table->string('num_empleado', 20)->unique();
            $table->string('rfc', 13)->unique();
            $table->enum('sexo', ['M', 'F', 'Otro']);
            $table->date('fecha_nacimiento');
            $table->string('telefono', 20);
            $table->boolean('es_tutor')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maestros');
    }
};
