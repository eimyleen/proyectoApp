<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alumno;
use App\Models\Materia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Carrera extends Model
{
    protected  $table = 'carreras';
    
    protected $fillable = ['nombre', 'clave', 'logo'];
    
    public function alumno(): HasOne
    {
        return $this->hasOne(Alumno::class);
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class);
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class);
    }
}
