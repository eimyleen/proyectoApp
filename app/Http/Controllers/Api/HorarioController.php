<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Horario::with(['grupo', 'maestro.user', 'materia']);

        if ($request->has('grupo_id')) {
            $query->where('grupo_id', $request->input('grupo_id'));
        }

        if ($request->has('maestro_id')) {
            $query->where('maestro_id', $request->input('maestro_id'));
        }

        if ($request->has('materia_id')) {
            $query->where('materia_id', $request->input('materia_id'));
        }

        if ($request->has('dia')) {
            $query->where('dia', $request->input('dia'));
        }

        $horarios = $query->get();

        return response()->json([
            'status' => true,
            'data'   => $horarios
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dia'         => 'nullable|in:Lunes,Martes,Miércoles,Jueves,Viernes',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin'    => 'nullable|date_format:H:i|after:hora_inicio',
            'aula'        => 'nullable|string|max:255',
            'grupo_id'    => 'required|exists:grupos,id',
            'maestro_id'  => 'required|exists:maestros,id',
            'materia_id'  => 'required|exists:materias,id',
        ]);

        $horario = Horario::create($validated);
        $horario->load(['grupo', 'maestro.user', 'materia']);

        return response()->json([
            'status'  => true,
            'message' => 'Horario creado correctamente',
            'data'    => $horario
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        $horario->load(['grupo', 'maestro.user', 'materia']);

        return response()->json([
            'status' => true,
            'data'   => $horario
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'dia'         => 'nullable|in:Lunes,Martes,Miércoles,Jueves,Viernes',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin'    => 'nullable|date_format:H:i|after:hora_inicio',
            'aula'        => 'nullable|string|max:255',
            'grupo_id'    => 'sometimes|required|exists:grupos,id',
            'maestro_id'  => 'sometimes|required|exists:maestros,id',
            'materia_id'  => 'sometimes|required|exists:materias,id',
        ]);

        $horario->update($validated);
        $horario->load(['grupo', 'maestro.user', 'materia']);

        return response()->json([
            'status'  => true,
            'message' => 'Horario actualizado correctamente',
            'data'    => $horario
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Horario eliminado correctamente'
        ], 200);
    }
}