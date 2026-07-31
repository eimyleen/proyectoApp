<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maestro;
use Illuminate\Http\Request;

class MaestroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Maestro::with('user');

        if ($request->boolean('with_carreras')) {
            $query->with('carreras');
        }

        $maestros = $query->get();

        return response()->json([
            'status' => true,
            'data'   => $maestros
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'num_empleado'     => 'required|string|max:20|unique:maestros,num_empleado',
            'rfc'              => 'required|string|max:13|unique:maestros,rfc',
            'sexo'             => 'required|in:M,F,Otro',
            'fecha_nacimiento' => 'required|date',
            'telefono'         => 'nullable|string|max:20',
            'es_tutor'         => 'boolean',
            'user_id'          => 'required|exists:users,id',
            'carrera_ids'      => 'nullable|array',
            'carrera_ids.*'    => 'exists:carreras,id'
        ]);

        $maestro = Maestro::create($validated);

        if (!empty($validated['carrera_ids'])) {
            $maestro->carreras()->sync($validated['carrera_ids']);
        }

        $maestro->load(['user', 'carreras']);

        return response()->json([
            'status'  => true,
            'message' => 'Maestro creado correctamente',
            'data'    => $maestro
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Maestro $maestro)
    {
        $relations = ['user'];

        if ($request->boolean('with_carreras')) {
            $relations[] = 'carreras';
        }

        $maestro->load($relations);

        return response()->json([
            'status' => true,
            'data'   => $maestro
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maestro $maestro)
    {
        $validated = $request->validate([
            'num_empleado'     => 'sometimes|required|string|max:20|unique:maestros,num_empleado,' . $maestro->id,
            'rfc'              => 'sometimes|required|string|max:13|unique:maestros,rfc,' . $maestro->id,
            'sexo'             => 'sometimes|required|in:M,F,Otro',
            'fecha_nacimiento' => 'sometimes|required|date',
            'telefono'         => 'nullable|string|max:20',
            'es_tutor'         => 'boolean',
            'user_id'          => 'sometimes|required|exists:users,id',
            'carrera_ids'      => 'nullable|array',
            'carrera_ids.*'    => 'exists:carreras,id'
        ]);

        $maestro->update($validated);

        if (array_key_exists('carrera_ids', $validated)) {
            $maestro->carreras()->sync($validated['carrera_ids'] ?? []);
        }

        $maestro->load(['user', 'carreras']);

        return response()->json([
            'status'  => true,
            'message' => 'Maestro actualizado correctamente',
            'data'    => $maestro
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maestro $maestro)
    {
        $maestro->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Maestro eliminado correctamente'
        ], 200);
    }
}