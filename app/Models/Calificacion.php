<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';

    protected $fillable = [
        'alumno_id',
        'materia_id',
        'periodo',
        'calificacion'
    ];

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }
}
