<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';

    protected $fillable = [
        'periodo',
        'parcial',
        'tipo_evaluacion',
        'calificacion',
        'alumno_id',
        'materia_id'
    ];

    const PRIORIDAD_EVALUACION = [
        'extraordinario' => 3,
        'remedial' => 2,
        'ordinario' => 1,
    ];

    /**
     * Relación con el alumno al que pertenece la calificacion.
     */
    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }
    /**
     * Relación con la materia a la que pertenece la calificacion.
     */
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }
}
