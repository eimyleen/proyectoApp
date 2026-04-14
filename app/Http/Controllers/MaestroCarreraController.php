<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\Alumno;
use App\Models\Maestro;

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
    public function show(string $id)
    {
        //
        $carrera = Carrera::findOrFail($id);
        $alumnos = Alumno::with('user:id,name')->get();
        $maestros = Maestro::with('user:id,name,email')->get();
        return view('dashboard.maestro.maestro_carrera', compact('carrera', 'alumnos', 'maestros'));
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
}
