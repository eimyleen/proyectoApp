<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Maestro extends Model
{
    protected $fillable = ['user_id', 'num_empleado', 'rfc', 'edad', 'sexo', 'fecha_nacimiento', 'telefono'];
}
