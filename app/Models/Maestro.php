<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maestro extends Model
{
    protected $fillable = [
        'user_id', 
        'num_empleado', 
        'rfc', 
        'edad', 
        'sexo', 
        'fecha_nacimiento', 
        'telefono', 
        'es_tutor'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
