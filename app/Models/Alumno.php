<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alumno extends Model
{
    protected $table = 'alumnos';

    protected $fillable = [
        'matricula',
        'curp',
        'sexo',
        'fecha_nacimiento',
        'telefono',
        'user_id',
        'carrera_id',
    ];
    
    /**
     * Obtiene el id correspondiente de la tabla users.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relación con la carrera a la que pertenece el alumno.
     */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'alumnos_grupos')
                    ->withPivot('periodo') // <- Esto te permite hacer $grupo->pivot->periodo
                    ->withTimestamps();   // Solo si tu migración pivote tiene timestamps
    }
}
