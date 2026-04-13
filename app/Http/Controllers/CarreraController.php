<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;

class CarreraController extends Controller
{
    public function index()
    {
        // Obtenemos todas las carreras de la BD
        $carreras = Carrera::all(); 
        return view('dashboard.admin.admin', compact('carreras'));
    }
    
    public function show($id) {
        $carrera = Carrera::findOrFail($id);
        return view('dashboard.admin.admin_carrera', compact('carrera'));
    }

