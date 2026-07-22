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
        Schema::create('configuracion_backup_auto', function (Blueprint $table) {
            $table->id();
            $table->boolean('activo');
            $table->date('fecha_inicio');
            $table->integer('intervalo_minutos');
            $table->integer('intervalo_horas');
            $table->integer('intervalo_dias');
            $table->timestamp('ultimo_backup')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_backup_auto');
    }
};
