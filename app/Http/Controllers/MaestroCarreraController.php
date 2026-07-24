<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Alumno;
use App\Models\Maestro;
use App\Models\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MaestroCarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtenemos todas las carreras de la BD
        $carreras = Carrera::all();
        // Y Alumnos con el nombre de su carrera y nombre de usuario*
        $alumnos = Alumno::with(['carrera:id,nombre','user:id,name'])->get();
        return view("dashboard.maestro.maestro", compact('carreras', 'alumnos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $req)
    {
        //
        $carrera = Carrera::findOrFail($id);
        $grupos = Grupo::where('carrera_id', $id)->get();
        $grupoId = $req->grupo_id;

        $alumnos = Alumno::with(['user:id,name,apellido', 'grupos'])
        ->whereHas('grupos', function ($q) use ($id, $grupoId) {
            $q->where('carrera_id', $id);

            if ($grupoId) {
                $q->where('grupos.id', $grupoId);
            }
        })
        ->get();
        $maestros = Maestro::with('user:id,name,apellido,email')->get();
        return view('dashboard.maestro.grupos', compact('carrera', 'alumnos', 'maestros', 'grupos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // Funcion para ver el expediente de un alumno desde la perspectiva del maestro
    public function verExpedienteAlumno($id, Request $request)
    {
        $alumno = Alumno::findOrFail($id);
        $grupo = $alumno->grupos->first();
        $carrera = $grupo?->carrera;

        $periodoSeleccionado = $request->query('periodo');

        $periodos = \App\Models\Calificacion::where('alumno_id', $alumno->id)
        ->distinct()
        ->pluck('periodo');
        
        $calificaciones = $periodoSeleccionado 
            ? \App\Models\Calificacion::with('materia')
                ->where('alumno_id', $alumno->id)
                ->where('periodo', $periodoSeleccionado)
                ->get()
            : collect();

        return view('dashboard.maestro.expediente_alumno_maestro', compact('alumno', 'grupo', 'carrera', 'periodoSeleccionado', 'periodos', 'calificaciones'));
    }

    public function descargarAlumnosPDF()
    {
        // Obtenemos los alumnos con sus relaciones
        $alumnos = Alumno::with('user', 'carrera')->get();

        Log::registrar('Descarga PDF', 'El maestro descargó la lista global de alumnos');

        // Cargamos una vista específica para el PDF y le pasamos los datos
        $pdf = Pdf::loadView('pdf.lista_alumnos_maestro', compact('alumnos'));

        // Retornamos el archivo para descarga con un nombre dinámico
        return $pdf->download('lista_global_alumnos_' . now()->format('d-m-Y') . '.pdf');
    }
}
