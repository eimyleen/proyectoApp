<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
class Expediente extends Model
{
    protected $fillable = ['user_id', 'carrera_id', 'grupo_id', 'matricula', 'curp', 'edad', 'sexo', 'fecha_nacimiento', 'telefono', 'promedio_general'];
}
