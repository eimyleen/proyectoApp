<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alumno;
use App\Models\Materia;

class Carrera extends Model
{
    protected $fillable = ['nombre', 'clave', 'logo'];
    protected  $table = 'carreras';
    
    public function alumnos() {
        return $this->hasOne(Alumno::class);
    }

    public function materias() {
        return $this->hasMany(Materia::class);
    }

    public function grupos() {
        return $this->hasMany(Grupo::class);
    }
}
