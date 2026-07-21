<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $fillable = [
        'user_id',
        'matricula',
        'carrera_id',
        'curp',
        'sexo',
        'fecha_nacimiento',
        'telefono',
    ];
    
    //funcion que permite obtener el usuario del alumno en la BD con la id correspondiente.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    //funcion que permite obtener las carreras en tabla pivote con los id eperados en la BD que encuentre.
    public function carrera() {
        return $this->belongsTo(Carrera::class);
    }

    public function grupos() {
        return $this->belongsToMany(Grupo::class, 'alumnos_grupos')
                    ->withPivot('periodo') // <- Esto te permite hacer $grupo->pivot->periodo
                    ->withTimestamps();   // Solo si tu migración pivote tiene timestamps
    }
}
