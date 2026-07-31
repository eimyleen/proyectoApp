<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carreras = Carrera::all();

        return response()->json([
            'status' => true,
            'data' => $carreras
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'clave'  => 'required|string|max:10|unique:carreras,clave',
            'logo'   => 'nullable|string|max:255',
        ]);

        $carrera = Carrera::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Carrera creada correctamente',
            'data'    => $carrera
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Carrera $carrera)
    {
        if ($request->boolean('with_relations')) {
            $carrera->load(['materias', 'grupos']);
        }

        return response()->json([
            'status' => true,
            'data'   => $carrera
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carrera $carrera)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'clave'  => 'sometimes|required|string|max:10|unique:carreras,clave,' . $carrera->id,
            'logo'   => 'nullable|string|max:255',
        ]);

        $carrera->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Carrera actualizada correctamente',
            'data'    => $carrera
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carrera $carrera)
    {
        $carrera->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Carrera eliminada correctamente'
        ], 200);
    }
}
