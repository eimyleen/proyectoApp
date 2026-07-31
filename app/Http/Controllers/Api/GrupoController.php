<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grupo::with(['carrera', 'maestro.user']);

        if ($request->boolean('with_alumnos')) {
            $query->with('alumnos.user');
        }

        $grupos = $query->get();

        return response()->json([
            'status' => true,
            'data'   => $grupos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:10',
                Rule::unique('grupos')->where(function ($query) use ($request) {
                    return $query->where('carrera_id', $request->carrera_id);
                }),
            ],
            'grado'       => 'required|in:1,2,3,4,5,6,7,8,9,10,11',
            'carrera_id'  => 'required|exists:carreras,id',
            'maestro_id'  => 'nullable|exists:maestros,id',
            'alumno_ids'   => 'nullable|array',
            'alumno_ids.*' => 'exists:alumnos,id',
        ]);

        $grupo = Grupo::create($validated);

        if (!empty($validated['alumno_ids'])) {
            $grupo->alumnos()->sync($validated['alumno_ids']);
        }

        $grupo->load(['carrera', 'maestro.user', 'alumnos']);

        return response()->json([
            'status'  => true,
            'message' => 'Grupo creado correctamente',
            'data'    => $grupo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Grupo $grupo)
    {
        $relations = ['carrera', 'maestro.user'];

        if ($request->boolean('with_alumnos')) {
            $relations[] = 'alumnos.user';
        }

        $grupo->load($relations);

        return response()->json([
            'status' => true,
            'data'   => $grupo
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        $carreraId = $request->input('carrera_id', $grupo->carrera_id);

        $validated = $request->validate([
            'nombre' => [
                'sometimes',
                'required',
                'string',
                'max:10',
                Rule::unique('grupos')->where(function ($query) use ($carreraId) {
                    return $query->where('carrera_id', $carreraId);
                })->ignore($grupo->id),
            ],
            'grado'        => 'sometimes|required|in:1,2,3,4,5,6,7,8,9,10,11',
            'carrera_id'   => 'sometimes|required|exists:carreras,id',
            'maestro_id'   => 'nullable|exists:maestros,id',
            'alumno_ids'   => 'nullable|array',
            'alumno_ids.*' => 'exists:alumnos,id',
        ]);

        $grupo->update($validated);

        if (array_key_exists('alumno_ids', $validated)) {
            $grupo->alumnos()->sync($validated['alumno_ids'] ?? []);
        }

        $grupo->load(['carrera', 'maestro.user', 'alumnos']);

        return response()->json([
            'status'  => true,
            'message' => 'Grupo actualizado correctamente',
            'data'    => $grupo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Grupo eliminado correctamente'
        ], 200);
    }
}