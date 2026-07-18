<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    //
    protected $fillable = [
        'nombre',
        'grado',
        'carrera_id'
    ];

    public function carrera() {
        return $this->belongsTo(Carrera::class);
    }

    public function alumnos() {
        return $this->belongsToMany(Alumno::class, 'alumnos_grupos');
    }
}
