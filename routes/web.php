<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de Navegación para los Roles

// Rutas para el modulo Alumno
Route::get('/dashboard/alumno', function () {
    return view('dashboard.alumno.alumno'); 
})->name('dashboard.alumno');

Route::get('/dashboard/alumno/expediente', function () {
    return view('dashboard.alumno.alumno_expediente'); 
})->name('alumno.expediente');

Route::get('/dashboard/alumno/calificaciones', function () {
    return view('dashboard.alumno.alumno_calificaciones'); 
})->name('alumno.calificaciones');

// Rutas para el modulo Maestro
Route::get('/dashboard/maestro', function () {
    return view('dashboard.maestro.maestro'); 
})->name('dashboard.maestro');

Route::get('/dashboard/maestro/perfil', function () {
    return view('dashboard.maestro.perfil_maestro'); 
})->name('maestro.perfil');

Route::get('/dashboard/maestro/grupos', function () {
    return view('dashboard.maestro.grupos'); 
})->name('maestro.grupos');

Route::get('/dashboard/maestro/expediente_alumno', function () {
    return view('dashboard.maestro.expediente_alumno_maestro'); 
})->name('maestro.expediente_alumno');

// Rutas para el modulo Administrador
Route::get('/dashboard/administrador', function () {
    return view('dashboard.admin.admin'); 
})->name('dashboard.admin');

Route::get('/dashboard/administrador/detalle_carrera', function () {
    return view('dashboard.admin.admin_carrera'); 
})->name('admin.carrera');

Route::get('/dashboard/administrador/perfil', function () {
    return view('dashboard.admin.admin_perfil'); 
})->name('admin.perfil');

Route::get('/dashboard/administrador/perfil_maestro', function () {
    return view('dashboard.admin.admin_maestro_perfil'); 
})->name('admin.perfil.maestro');

Route::get('/dashboard/administrador/perfil_alumno', function () {
    return view('dashboard.admin.admin_alumno_expediente'); 
})->name('admin.perfil.alumno');

Route::get('/dashboard/administrador/expediente_alumno', function () {
    return view('dashboard.admin.admin_alumno_expediente'); 
})->name('admin.expediente.alumno');

require __DIR__.'/auth.php';
 