<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materia;
use App\Models\Horario;
use App\Models\Grupo;

class AlumnoController extends Controller
{
    public function index()
    {
        // Obtener alumno con el usuario activo
        $user = Auth::user();
        $alumno = $user->alumno;

        // Obtener grupo actual del alumno
        $grupo = $alumno?->grupos?->first();

        // Y luego la carrera como referencia auxiliar
        $carrera = $grupo?->carrera;

        // Obtener el horario filtrado por el grupo del alumno
        $horarios = collect();
        if ($grupo){
            $horarios = Horario::with('materia', 'maestro.user')
                ->where('grupo_id', $grupo->id)
                ->orderBy('hora_inicio')
                ->get()
                ->groupBy('dia');
        }

        return view('dashboard.alumno.alumno', compact('alumno', 'grupo', 'carrera', 'horarios'));
    }

    public function calificaciones(Request $request)
    {
        $user = Auth::user();
        $alumno = $user->alumno;
        $grupo = $alumno->grupos->first();
        $carrera = $grupo?->carrera;

        $periodoSeleccionado = $request->query('periodo');

        $periodos = \App\Models\Calificacion::where('alumno_id', $alumno->id)
        ->distinct()
        ->pluck('periodo');

        // Solo se traen las calificaciones si el usuario eligió un período
        $calificaciones = $periodoSeleccionado 
            ? \App\Models\Calificacion::with('materia')
                ->where('alumno_id', $alumno->id)
                ->where('periodo', $periodoSeleccionado)
                ->get()
            : collect();

        return view('dashboard.alumno.alumno_calificaciones', compact('calificaciones', 'periodos', 'grupo', 'carrera', 'periodoSeleccionado'));
    }

    public function expediente() {
        $user = Auth::user();
        $alumno = $user->alumno;
        $grupo = $alumno->grupos->first();
        $carrera = $grupo?->carrera;

        return view('dashboard.alumno.alumno_expediente', compact('alumno','grupo','carrera'));
    }
}
