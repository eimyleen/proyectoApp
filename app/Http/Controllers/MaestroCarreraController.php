<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Alumno;
use App\Models\Maestro;
use App\Models\Materia;
use App\Models\Calificacion;
use App\Models\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;

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
        $materias = Materia::all()->where('carrera_id', $carrera->id);

        $periodoSeleccionado = $request->query('periodo');

        $periodos = Calificacion::where('alumno_id', $alumno->id)
        ->distinct()
        ->pluck('periodo');
        
        $calificacionesCalculadas = collect();
        $promedioPeriodo = 0;

        $materiasPorPeriodo = Calificacion::with('materia')
            ->get()
            ->groupBy('periodo')
            ->map(function ($items) {
                return $items->pluck('materia')->unique('id')->values();
        });

        $calificacionesPorMateriaYPeriodo = Calificacion::all()->where('alumno_id', $alumno->id)->groupBy(['periodo', 'materia_id'])->map(function ($items) {
                return $items->pluck('calificacion')->values();
        });

        if ($periodoSeleccionado) {
            $materiasIds = Calificacion::where('alumno_id', $alumno->id)
                ->where('periodo', $periodoSeleccionado)
                ->pluck('materia_id')
                ->unique();

            foreach ($materiasIds as $materiaId) {
                $materia = Materia::find($materiaId);
                
                $parciales = [];
                for ($p = 1; $p <= 2; $p++) {
                    $co = $alumno->getCalificacionParcialTipo($materiaId, $periodoSeleccionado, $p, 'ordinario');
                    $cr = $alumno->getCalificacionParcialTipo($materiaId, $periodoSeleccionado, $p, 'remedial');
                    $ce = $alumno->getCalificacionParcialTipo($materiaId, $periodoSeleccionado, $p, 'extraordinario');
                    
                    // Calcular CF parcial (prioridad: extraordinario > remedial > ordinario)
                    $cf = $ce ?? $cr ?? $co ?? null;

                    $parciales[$p] = [
                        'co' => $co,
                        'cr' => $cr,
                        'ce' => $ce,
                        'cf' => $cf
                    ];
                }

                // CF Materia: promedio de CF parciales
                $cf1 = $parciales[1]['cf'];
                $cf2 = $parciales[2]['cf'];
                $notaFinalMateria = ($cf1 !== null && $cf2 !== null) ? ($cf1 + $cf2) / 2 : null;
                
                $calificacionesCalculadas->push((object)[
                    'materia' => $materia,
                    'parciales' => $parciales,
                    'nota_final' => $notaFinalMateria
                ]);
            }
            
            $promedioPeriodo = $alumno->getPromedioPeriodo($periodoSeleccionado);
        }

        return view('dashboard.maestro.expediente_alumno_maestro', compact('alumno', 'grupo', 'carrera', 'materias', 'periodoSeleccionado', 'periodos', 'calificacionesCalculadas', 'promedioPeriodo', 'materiasPorPeriodo'));
    }

    public function guardarCalificacion($alumnoId) {
        $data = request()->validate([
            'periodo' => ['required', 'string'],
            'materia' => ['required', 'exists:materias,id'],
            'parcial' => ['required', 'integer', 'min:1', 'max:2'],
            'evaluacion' => ['required', 'string'],
            'calificacion' => ['required', 'numeric', 'min:0', 'max:10']
        ]);

        $existeParcialAnterior = Calificacion::where('alumno_id', $alumnoId)
            ->where('materia_id', $data['materia'])
            ->where('periodo', $data['periodo'])
            ->where('parcial', $data['parcial'] - 1)
        ->exists();
        
        if ($data['parcial'] > 1 && !$existeParcialAnterior) {
            throw ValidationException::withMessages([
                'parcial' => 'Debes registrar primero la calificación del parcial anterior.'
            ]);
        }

        $tieneOrdinario = Calificacion::where([
            'alumno_id' => $alumnoId,
            'materia_id' => $data['materia'],
            'periodo' => $data['periodo'],
            'tipo_evaluacion' => 'ordinario'
        ])->exists();

        $tieneRemedial = Calificacion::where([
            'alumno_id' => $alumnoId,
            'materia_id' => $data['materia'],
            'periodo' => $data['periodo'],
            'tipo_evaluacion' => 'remedial'
        ])->exists();

        $yaExiste = Calificacion::where([
            'alumno_id' => $alumnoId,
            'materia_id' => $data['materia'],
            'periodo' => $data['periodo'],
            'tipo_evaluacion' => $data['evaluacion']
        ])->exists();

        if ($yaExiste) {
            throw ValidationException::withMessages([
                'evaluacion' => 'Este tipo de evaluación ya fue registrado.'
            ]);
        }

        if ($data['evaluacion'] === 'remedial' && !$tieneOrdinario) {
            throw ValidationException::withMessages([
                'evaluacion' => 'No puedes registrar remedial sin ordinario.'
            ]);
        }

        if ($data['evaluacion'] === 'extraordinario' && !$tieneRemedial) {
            throw ValidationException::withMessages([
                'evaluacion' => 'No puedes registrar extraordinario sin remedial.'
            ]);
        }

        $noExisteNinguna = !$tieneOrdinario && !$tieneRemedial;

        if ($noExisteNinguna && $data['evaluacion'] !== 'ordinario') {
            throw ValidationException::withMessages([
                'evaluacion' => 'La primera evaluación debe ser ordinario.'
            ]);
        }

        Calificacion::create([
            'periodo' => $data['periodo'],
            'parcial' => $data['parcial'],
            'tipo_evaluacion' => $data['evaluacion'],
            'calificacion' => $data['calificacion'],
            'alumno_id' => $alumnoId,
            'materia_id' => $data['materia']
        ]);

        return redirect()->route('maestro.alumno.expediente', $alumnoId)->with('success', 'se añadio una nueva calificación');
    }

    public function editarCalificacion($alumnoId) {
        $data = request()->validate([
            'periodo' => ['required', 'string'],
            'materia' => ['required', 'exists:materias,id'],
            'parcial' => ['required', 'integer', 'min:1', 'max:2'],
            'evaluacion' => ['required', 'string'],
            'calificacion' => ['required', 'numeric', 'min:0', 'max:10']
        ]);

        $cal = Calificacion::where('alumno_id', $alumnoId)
            ->where('periodo', $data['periodo'])
            ->where('materia_id', $data['materia'])
            ->where('parcial', $data['parcial'])
            ->where('tipo_evaluacion', $data['evaluacion'])
            ;
        $cal->update(['calificacion' => $data['calificacion']]);

        return redirect()->route('maestro.alumno.expediente', $alumnoId)->with('success', 'se modifico una calificación');
    }

    public function maestroPerfil() {
        $user = Auth::user();
        $maestro = $user->maestro;
        $carreras = $maestro->carreras;
        return view('dashboard.maestro.perfil_maestro', compact('user', 'maestro', 'carreras'));
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
