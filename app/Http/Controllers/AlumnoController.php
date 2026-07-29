<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $calificacionesCalculadas = collect();
        $promedioPeriodo = 0;

        if ($periodoSeleccionado) {
            $materiasIds = \App\Models\Calificacion::where('alumno_id', $alumno->id)
                ->where('periodo', $periodoSeleccionado)
                ->pluck('materia_id')
                ->unique();

            foreach ($materiasIds as $id) {
                $materia = \App\Models\Materia::find($id);
                
                $parciales = [];
                for ($p = 1; $p <= 2; $p++) {
                    $co = $alumno->getCalificacionParcialTipo($id, $periodoSeleccionado, $p, 'ordinario');
                    $cr = $alumno->getCalificacionParcialTipo($id, $periodoSeleccionado, $p, 'remedial');
                    $ce = $alumno->getCalificacionParcialTipo($id, $periodoSeleccionado, $p, 'extraordinario');
                    
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

        return view('dashboard.alumno.alumno_calificaciones', compact('calificacionesCalculadas', 'promedioPeriodo', 'periodos', 'grupo', 'carrera', 'periodoSeleccionado'));
    }

    public function expediente() {
        $user = Auth::user();
        $alumno = $user->alumno;
        $grupo = $alumno->grupos->first();
        $carrera = $grupo?->carrera;

        return view('dashboard.alumno.alumno_expediente', compact('alumno','grupo','carrera'));
    }

    public function subirDocumentos(Request $request)
    {
        // Obtener al alumno logueado
        $user = Auth::user();
        $alumno = $user->alumno;

        // Validar los archivos
        $request->validate([
            'doc_acta_nacimiento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_curp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_certificado_bachillerato' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_constancia_estudios' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Array con los campos exacta y correctamente nombrados
        $camposDocumentos = [
            'doc_acta_nacimiento',
            'doc_curp',
            'doc_certificado_bachillerato',
            'doc_constancia_estudios'
        ];

        // Procesar solo los archivos que hayan sido seleccionados
        foreach ($camposDocumentos as $campo) {
            if ($request->hasFile($campo)) {
                
                // Si el alumno ya tenía un archivo en este campo, eliminar el viejo
                if ($alumno->$campo) {
                    Storage::disk('public')->delete($alumno->$campo);
                }

                // Guardar en la carpeta "documentos_alumnos" dentro de public storage
                $path = $request->file($campo)->store('documentos_alumnos', 'public');

                // Asignar la nueva ruta a la columna del alumno
                $alumno->$campo = $path;
            }
        }

        // Guardar cambios en la Base de Datos
        $alumno->save();

        return back()->with('success', 'Documento(s) actualizado(s) con éxito.');
    }
}
