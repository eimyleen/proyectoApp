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
        $user = Auth::user();
        $alumno = $user->alumno;
        $grupo = $alumno->where('grupos', $alumno->id);

        // Obtener las materias de la carrera del alumno
        $materias = Materia::where('carrera_id', $alumno->carrera_id)->get();

        // Obtener el horario filtrado por el grupo del alumno
        $horarios = Horario::with('materia')
            ->where('grupo', $alumno->grupo)
            ->get()
            ->groupBy('dia');

        return view('dashboard.alumno.alumno', compact('alumno', 'materias', 'horarios', 'grupo'));
    }

    public function calificaciones()
    {
        $user = Auth::user();
        $alumno = $user->alumno;
        $grupo = $alumno->grupos->first();
        $carrera = $grupo?->carrera;

        $calificaciones = \App\Models\Calificacion::with('materia')
            ->where('alumno_id', $alumno->id) 
            ->get();

        $periodos = \App\Models\Calificacion::where('alumno_id', $alumno->id)
            ->distinct()
            ->pluck('periodo');

        return view('dashboard.alumno.alumno_calificaciones', compact('calificaciones', 'periodos', 'grupo', 'carrera'));
    }

    public function expediente() {
        $user = Auth::user();
        $alumno = $user->alumno;
        $grupo = $alumno->grupos->first();
        $carrera = $grupo?->carrera;

        return view('dashboard.alumno.alumno_expediente', compact('alumno','grupo','carrera'));
    }
}
