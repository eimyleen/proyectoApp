<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Maestro extends Model
{
    protected $table = 'maestros';

    protected $fillable = [ 
        'num_empleado', 
        'rfc',
        'sexo', 
        'fecha_nacimiento', 
        'telefono', 
        'es_tutor',
        'user_id'
    ];

    /**
    * Obtiene el id correspondiente de la tabla users.
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carreras(): BelongsToMany
    {
        return $this->belongsToMany(Carrera::class, 'maestros_carreras');
    }

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
}
