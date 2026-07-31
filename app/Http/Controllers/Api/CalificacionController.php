<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Calificacion::with(['alumno.user', 'materia']);

        if ($request->has('alumno_id')) {
            $query->where('alumno_id', $request->input('alumno_id'));
        }

        if ($request->has('materia_id')) {
            $query->where('materia_id', $request->input('materia_id'));
        }

        if ($request->has('periodo')) {
            $query->where('periodo', $request->input('periodo'));
        }

        if ($request->has('parcial')) {
            $query->where('parcial', $request->input('parcial'));
        }

        if ($request->has('tipo_evaluacion')) {
            $query->where('tipo_evaluacion', $request->input('tipo_evaluacion'));
        }

        $calificaciones = $query->get();

        return response()->json([
            'status' => true,
            'data'   => $calificaciones
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'periodo'         => 'required|string|max:30',
            'parcial'         => 'required|integer|min:1|max:10',
            'tipo_evaluacion' => 'required|in:ordinario,remedial,extraordinario',
            'calificacion'    => 'required|numeric|min:0|max:10',
            'alumno_id'       => 'required|exists:alumnos,id',
            'materia_id'      => [
                'required',
                'exists:materias,id',
                Rule::unique('calificaciones')->where(function ($query) use ($request) {
                    return $query->where('alumno_id', $request->alumno_id)
                                 ->where('periodo', $request->periodo)
                                 ->where('parcial', $request->parcial)
                                 ->where('tipo_evaluacion', $request->tipo_evaluacion);
                }),
            ],
        ]);

        $calificacion = Calificacion::create($validated);
        $calificacion->load(['alumno.user', 'materia']);

        return response()->json([
            'status'  => true,
            'message' => 'Calificación registrada correctamente',
            'data'    => $calificacion
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Calificacion $calificacion)
    {
        $calificacion->load(['alumno.user', 'materia']);

        return response()->json([
            'status' => true,
            'data'   => $calificacion
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calificacion $calificacion)
    {
        $alumnoId       = $request->input('alumno_id', $calificacion->alumno_id);
        $periodo        = $request->input('periodo', $calificacion->periodo);
        $parcial        = $request->input('parcial', $calificacion->parcial);
        $tipoEvaluacion = $request->input('tipo_evaluacion', $calificacion->tipo_evaluacion);

        $validated = $request->validate([
            'periodo'         => 'sometimes|required|string|max:30',
            'parcial'         => 'sometimes|required|integer|min:1|max:10',
            'tipo_evaluacion' => 'sometimes|required|in:ordinario,remedial,extraordinario',
            'calificacion'    => 'sometimes|required|numeric|min:0|max:10',
            'alumno_id'       => 'sometimes|required|exists:alumnos,id',
            'materia_id'      => [
                'sometimes',
                'required',
                'exists:materias,id',
                Rule::unique('calificaciones')->where(function ($query) use ($alumnoId, $periodo, $parcial, $tipoEvaluacion) {
                    return $query->where('alumno_id', $alumnoId)
                                 ->where('periodo', $periodo)
                                 ->where('parcial', $parcial)
                                 ->where('tipo_evaluacion', $tipoEvaluacion);
                })->ignore($calificacion->id),
            ],
        ]);

        $calificacion->update($validated);
        $calificacion->load(['alumno.user', 'materia']);

        return response()->json([
            'status'  => true,
            'message' => 'Calificación actualizada correctamente',
            'data'    => $calificacion
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calificacion $calificacion)
    {
        $calificacion->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Calificación eliminada correctamente'
        ], 200);
    }
}