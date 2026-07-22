<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';

    protected $fillable = [
        'periodo',
        'calificacion',
        'alumno_id',
        'materia_id'
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
