<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'dia', 
        'hora_inicio', 
        'hora_fin', 
        'aula',
        'grupo_id',
        'maestro_id',
        'materia_id'
    ];

    /**
     * Relación con el grupo a la que pertenece el horario.
     */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * Relación con la materia a la que pertenece el horario.
     */
    public function maestro(): BelongsTo
    {
        return $this->belongsTo(Maestro::class);
    }

    /**
     * Relación con la materia a la que pertenece el horario.
     */
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }
}
