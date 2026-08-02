<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Alumno::with(['user', 'carrera']);

        if ($request->boolean('with_grupos')) {
            $query->with('grupos');
        }

        if ($request->boolean('with_calificaciones')) {
            $query->with('calificaciones');
        }

        $alumnos = $query->get();

        return response()->json([
            'status' => true,
            'data'   => $alumnos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricula'                    => 'required|string|max:12|unique:alumnos,matricula',
            'curp'                         => 'required|string|max:18|unique:alumnos,curp',
            'sexo'                         => 'required|in:M,F,Otro',
            'fecha_nacimiento'             => 'required|date',
            'telefono'                     => 'nullable|string|max:20',
            'doc_acta_nacimiento'          => 'nullable|string|max:255',
            'doc_curp'                     => 'nullable|string|max:255',
            'doc_certificado_bachillerato' => 'nullable|string|max:255',
            'doc_constancia_estudios'      => 'nullable|string|max:255',
            'user_id'                      => 'required|exists:users,id',
            'carrera_id'                   => 'required|exists:carreras,id',
            'grupo_ids'                    => 'nullable|array',
            'grupo_ids.*'                  => 'exists:grupos,id',
        ]);

        $alumno = Alumno::create($validated);

        if (!empty($validated['grupo_ids'])) {
            $alumno->grupos()->sync($validated['grupo_ids']);
        }

        $alumno->load(['user', 'carrera', 'grupos']);

        return response()->json([
            'status'  => true,
            'message' => 'Alumno creado correctamente',
            'data'    => $alumno
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Alumno $alumno)
    {
        $relations = ['user', 'carrera'];

        if ($request->boolean('with_grupos')) {
            $relations[] = 'grupos';
        }

        if ($request->boolean('with_calificaciones')) {
            $relations[] = 'calificaciones';
        }

        $alumno->load($relations);

        if ($request->has('periodo')) {
            $periodo = $request->input('periodo');
            $alumno->promedio_periodo = $alumno->getPromedioPeriodo($periodo);
        }

        return response()->json([
            'status' => true,
            'data'   => $alumno
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumno $alumno)
    {
        $validated = $request->validate([
            'matricula'                    => 'sometimes|required|string|max:12|unique:alumnos,matricula,' . $alumno->id,
            'curp'                         => 'sometimes|required|string|max:18|unique:alumnos,curp,' . $alumno->id,
            'sexo'                         => 'sometimes|required|in:M,F,Otro',
            'fecha_nacimiento'             => 'sometimes|required|date',
            'telefono'                     => 'nullable|string|max:20',
            'doc_acta_nacimiento'          => 'nullable|string|max:255',
            'doc_curp'                     => 'nullable|string|max:255',
            'doc_certificado_bachillerato' => 'nullable|string|max:255',
            'doc_constancia_estudios'      => 'nullable|string|max:255',
            'user_id'                      => 'sometimes|required|exists:users,id',
            'carrera_id'                   => 'sometimes|required|exists:carreras,id',
            'grupo_ids'                    => 'nullable|array',
            'grupo_ids.*'                  => 'exists:grupos,id',
        ]);

        $alumno->update($validated);

        if (array_key_exists('grupo_ids', $validated)) {
            $alumno->grupos()->sync($validated['grupo_ids'] ?? []);
        }

        $alumno->load(['user', 'carrera', 'grupos']);

        return response()->json([
            'status'  => true,
            'message' => 'Alumno actualizado correctamente',
            'data'    => $alumno
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {
        $alumno->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Alumno eliminado correctamente'
        ], 200);
    }

    public function getUserWithAlumno($id) {
        $alumno = Alumno::with('user')->find($id);

        if (!$alumno) {
            return response()->json([
                'status' => false,
                'message' => 'Alumno no encontrado'
                ],
                404);
        }

        return response()->json([
            'data'=>$alumno,
            'status'=>false
        ],
        202);
    }
}