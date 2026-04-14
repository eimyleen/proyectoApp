<?php

namespace App\Http\Controllers;

use App\Models\Maestro;
use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\Alumno;

class AdminCarreraController extends Controller {
    public function index() {
        // Obtenemos todas las carreras de la BD
        $var_carreras = Carrera::all();
        // Y Alumnos con el nombre de su carrera y nombre de usuario*
        $alumnos = Alumno::with(['carrera:id,nombre','user:id,name'])->get();
        return view('dashboard.admin.admin', compact('var_carreras', 'alumnos'));
    }
    
    public function show($id) {
        $var_carrera = Carrera::findOrFail($id);
        $alumnos = Alumno::with('user:id,name')->get();
        $maestros = Maestro::with('user:id,name,email')->get();
        return view('dashboard.admin.admin_carrera', compact('var_carrera', 'alumnos', 'maestros'));
    }

    public function update(Request $request, string $id) {
        $carrera = Carrera::findOrFail($id);
        $carrera -> nombre = $request -> inNombre;
        $carrera -> clave = $request -> inClave;
        $carrera -> logo = $request -> inLogo;
        $carrera -> save();
        return redirect() -> route('admin.show', $carrera);
    }

    public function store(Request $request) {
        request()->validate(
            [
                'nombre'=>'required|alpha',
                'clave'=>'required|alpha',
                'logo'=>''
            ]
        );
        Carrera::create([
            //BD ATRIBB NAME - INPUT NAME
            'nombre'=>request('nombre'),
            'clave'=>request('clave'),
            'logo'=>request('logo')
        ]);
        return redirect()->route('admin.index');
    }

    public function delete(string $id) {
        $Item = Carrera::findOrFail($id);
        $Item -> delete();
        return redirect() -> route("admin.index");
    }
}