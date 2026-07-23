<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Alumno extends Model
{
    protected $table = 'alumnos';

    protected $fillable = [
        'matricula',
        'curp',
        'sexo',
        'fecha_nacimiento',
        'telefono',
        'doc_acta_nacimiento',
        'doc_curp',
        'doc_certificado_bachillerato',
        'doc_constancia_estudios',
        'user_id',
        'carrera_id',
    ];

    /**
     *  Accesor para completar el sexo 
     */
    public function getSexoTextoAttribute()
    {
        $sexos = [
            'M' => 'Masculino',
            'F' => 'Femenino',
            'Otro' => 'Otro',
        ];

        return $sexos[$this->sexo] ?? $this->sexo;
    }

    /**
     *  Accesor para calcular y obtener la edad del alumno.
     */
    public function getEdadAttribute()
    {
        // Carbon parsea la fecha y la propiedad ->age calcula automaticamente los años.
        return Carbon::parse($this->fecha_nacimiento)->age;
    }
    
    /**
     * Obtiene el id correspondiente de la tabla users.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relación con la carrera a la que pertenece el alumno.
     */
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'alumnos_grupos')
                    ->withPivot('periodo') // <- Esto te permite hacer $grupo->pivot->periodo
                    ->withTimestamps();   // Solo si tu migración pivote tiene timestamps
    }
}
