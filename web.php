<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarreraController;

// Ruta de login (breeze lo maneja)
require __DIR__.'/auth.php';
Route::get('/', function () {
    return redirect('/login');
});

// Grupo protegido por autenticación y nuestro redireccionador
Route::middleware(['auth', 'role.redirect'])->group(function () {
    
    // Si alguien entra mediante ruta, se redireccionara a esto
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');

    // Rutas específicas
    Route::get('/dashboard/maestro', function () { 
        return view('dashboard.maestro.maestro'); 
    })->name('maestro.dashboard');

    Route::get('/dashboard/alumno', function () { 
        return view('dashboard.alumno.alumno'); 
    })->name('alumno.dashboard');

    //Rutas del perfil de alumno (expediente y calificaciones)
    Route::get('/dashboard/alumno/calificaciones', function () {
    return view('dashboard.alumno.alumno_calificaciones');
    })->name('alumno.calificaciones');

    Route::get('/dashboard/alumno/expediente', function () {
        return view('dashboard.alumno.alumno_expediente');
    })->name('alumno.expediente');

    // Ruta para ver el panel de administración (Lista de carreras)
    Route::get('/dashboard/admin', [CarreraController::class, 'index'])->name('admin.dashboard');

    // Ruta para GUARDAR una nueva carrera (Método POST)
    Route::post('/carreras', [CarreraController::class, 'store'])->name('carreras.store');

    // Ruta para VER el detalle de una carrera específica (Método GET)
    Route::get('/carreras/{id}', [CarreraController::class, 'show'])->name('carreras.show');

    
});

/*
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

*/
