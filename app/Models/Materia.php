<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $table = 'materias';

    protected $fillable = ['nombre', 'carrera_id'];

    /**
    * Relación con la carrera a la que pertenece la materia.
    */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }
}
