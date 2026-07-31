<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Materia::with('carrera');

        if ($request->boolean('with_horarios')) {
            $query->with('horarios');
        }

        $materias = $query->get();

        return response()->json([
            'status' => true,
            'data'   => $materias
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'carrera_id' => 'required|exists:carreras,id',
            'nombre'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('materias')->where(function ($query) use ($request) {
                    return $query->where('carrera_id', $request->carrera_id);
                }),
            ],
        ]);

        $materia = Materia::create($validated);
        $materia->load('carrera');

        return response()->json([
            'status'  => true,
            'message' => 'Materia creada correctamente',
            'data'    => $materia
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Materia $materia)
    {
        $relations = ['carrera'];

        if ($request->boolean('with_horarios')) {
            $relations[] = 'horarios';
        }

        $materia->load($relations);

        return response()->json([
            'status' => true,
            'data'   => $materia
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materia $materia)
    {
        $carreraId = $request->input('carrera_id', $materia->carrera_id);

        $validated = $request->validate([
            'carrera_id' => 'sometimes|required|exists:carreras,id',
            'nombre'     => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('materias')->where(function ($query) use ($carreraId) {
                    return $query->where('carrera_id', $carreraId);
                })->ignore($materia->id),
            ],
        ]);

        $materia->update($validated);
        $materia->load('carrera');

        return response()->json([
            'status'  => true,
            'message' => 'Materia actualizada correctamente',
            'data'    => $materia
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materia $materia)
    {
        $materia->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Materia eliminada correctamente'
        ], 200);
    }
}