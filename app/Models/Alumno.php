<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Relación con las calificaciones del alumno.
     */
    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    /**
     * Obtiene la calificación de un tipo de evaluación específico para un parcial.
     */
    public function getCalificacionParcialTipo($materiaId, $periodo, $parcial, $tipo)
    {
        return $this->calificaciones()
            ->where('materia_id', $materiaId)
            ->where('periodo', $periodo)
            ->where('parcial', $parcial)
            ->where('tipo_evaluacion', $tipo)
            ->value('calificacion');
    }

    /**
     * Obtiene la calificación definitiva de un parcial (mejor tipo de evaluación).
     */
    public function getCalificacionDefinitivaParcial($materiaId, $periodo, $parcial)
    {
        $calificaciones = $this->calificaciones()
            ->where('materia_id', $materiaId)
            ->where('periodo', $periodo)
            ->where('parcial', $parcial)
            ->get();

        if ($calificaciones->isEmpty()) {
            return 0; // O null, dependiendo de la política de notas faltantes
        }

        // Ordenar por prioridad definida en el modelo Calificacion
        return $calificaciones->sortByDesc(function ($cal) {
            return Calificacion::PRIORIDAD_EVALUACION[$cal->tipo_evaluacion] ?? 0;
        })->first()->calificacion;
    }

    /**
     * Calcula la calificación final de una materia en un período.
     */
    public function getCalificacionFinalMateria($materiaId, $periodo)
    {
        $p1 = $this->getCalificacionDefinitivaParcial($materiaId, $periodo, 1);
        $p2 = $this->getCalificacionDefinitivaParcial($materiaId, $periodo, 2);

        return ($p1 + $p2) / 2;
    }

    /**
     * Calcula el promedio del cuatrimestre filtrado por período.
     */
    public function getPromedioPeriodo($periodo)
    {
        $materiasIds = $this->calificaciones()
            ->where('periodo', $periodo)
            ->pluck('materia_id')
            ->unique();

        if ($materiasIds->isEmpty()) {
            return 0;
        }

        $sumaCalificaciones = 0;
        foreach ($materiasIds as $materiaId) {
            $sumaCalificaciones += $this->getCalificacionFinalMateria($materiaId, $periodo);
        }

        return $sumaCalificaciones / $materiasIds->count();
    }

    /**
     *  Accesor para completar el sexo 
     */
    public function getSexoTextoAttribute()
    {
        $sexos = [
            'M' => __('messages.gender_male'),
            'F' => __('messages.gender_female'),
            'Otro' => __('messages.gender_other'),
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
