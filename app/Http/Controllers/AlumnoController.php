<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Materia;
use App\Models\Horario;
use App\Models\Grupo;
use App\Models\Calificacion;

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

        // Ejecutar al cargar la página
        $dataCienciaDatos = null; 
        try { 
            $result = Process::timeout(10)->run("python3 " . base_path('scripts/analisis_alumno.py') . " " . $alumno->id); 
            if ($result->successful()) { 
                $dataCienciaDatos = json_decode($result->output(), true); 
            } 
        } catch (\Exception $e) { 
            // Fallback en caso de error 
        } 

        // CALCULO GLOBAL PARA GRÁFICA (SIEMPRE)
        $promedioP1 = Calificacion::where('alumno_id', $alumno->id)->where('parcial', 1)->avg('calificacion') ?? 0;
        $promedioP2 = Calificacion::where('alumno_id', $alumno->id)->where('parcial', 2)->avg('calificacion') ?? 0;

        // Filtro y Calificaciones por Período
        $periodoSeleccionado = $request->query('periodo'); 

        $periodos = Calificacion::where('alumno_id', $alumno->id) 
            ->distinct() 
            ->pluck('periodo'); 

        $calificacionesCalculadas = collect(); 
        $promedioPeriodo = 0; 

        if ($periodoSeleccionado) { 
            $materiasIds = Calificacion::where('alumno_id', $alumno->id) 
                ->where('periodo', $periodoSeleccionado) 
                ->pluck('materia_id') 
                ->unique(); 

            foreach ($materiasIds as $id) { 
                $materia = Materia::find($id); 
                
                $parciales = []; 
                for ($p = 1; $p <= 2; $p++) { 
                    $co = $alumno->getCalificacionParcialTipo($id, $periodoSeleccionado, $p, 'ordinario'); 
                    $cr = $alumno->getCalificacionParcialTipo($id, $periodoSeleccionado, $p, 'remedial'); 
                    $ce = $alumno->getCalificacionParcialTipo($id, $periodoSeleccionado, $p, 'extraordinario'); 
                    
                    // Prioridad: Extraordinario > Remedial > Ordinario
                    $cf = $ce ?? $cr ?? $co ?? null; 

                    $parciales[$p] = [ 
                        'co' => $co, 
                        'cr' => $cr, 
                        'ce' => $ce, 
                        'cf' => $cf 
                    ]; 
                } 

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

        return view('dashboard.alumno.alumno_calificaciones', compact('calificacionesCalculadas', 'promedioPeriodo', 'promedioP1', 'promedioP2', 'periodos', 'grupo', 'carrera', 'periodoSeleccionado', 'dataCienciaDatos')); 
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

    public function descargarPrediccionPDF()
    {
        $user = Auth::user();
        $alumno = $user->alumno;

        // Ejecutar el script de Python
        $data = null;
        try {
            $result = Process::timeout(10)->run("python3 " . base_path('scripts/analisis_alumno.py') . " " . $alumno->id);
            if ($result->successful()) {
                $data = json_decode($result->output(), true);
            }
        } catch (\Exception $e) {
            // Fallback en caso de error
        }
        
        if (!$data) {
            return back()->with('error', 'No se pudo generar el reporte.');
        }

        $pdf = Pdf::loadView('pdf.reporte_prediccion', compact('alumno', 'data'));
        return $pdf->download('reporte_prediccion_' . $alumno->user->name . '.pdf');
    }
}
