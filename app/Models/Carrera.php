<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alumno;

class Carrera extends Model
{
    protected $fillable = ['nombre', 'clave', 'logo'];
    protected  $table = 'carreras';
    
    public function alumnos() {
        return $this->hasOne(Alumno::class);
    }
}
