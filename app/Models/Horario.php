<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    protected $fillable = [
        'grupo', 
        'dia', 
        'hora_inicio', 
        'hora_fin', 
        'materia_id', 
        'aula'
    ];

    /**
     * Relación con la materia a la que pertenece este horario.
     */
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }
}
