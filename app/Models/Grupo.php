<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $fillable = [
        'nombre',
        'grado',
        'carrera_id'
    ];

    /**
     * Relación con la carrera a la que pertenece el grupo.
     */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumnos_grupos');
    }
}
