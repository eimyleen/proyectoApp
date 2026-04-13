<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $fillable = [
        'user_id',
        'matricula',
        'carrera_id',
        'grupo',
        'curp',
        'edad',
        'sexo',
        'fecha_nacimiento',
        'telefono',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function carrera() {
        return $this->belongsTo(Carrera::class);
    }
}
