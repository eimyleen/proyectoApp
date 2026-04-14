<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    //
    protected $fillable = ['name', 'apellido', 'email', 'role', 'foto', 'password'];
    protected  $table = 'usuario';

    public function alumno() {
        return $this->hasOne(Alumno::class);
    }
}
