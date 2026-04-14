<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminCarreraController;
use App\Http\Controllers\MaestroCarreraController;

// Redirección raíz al login
Route::redirect('/', '/login');

// --- Rutas de autenticación ---

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Grupos de rutas protegidas por autenticación ---

Route::middleware(['auth'])->group(function () {
    
    // --- Rutas para el Admin ---

    // --- Rutas referentes a la gestión de carreras ---

    // Ruta para el Admin - AÑADIDO ->name('admin.index')
    Route::get('/admin/dashboard', [AdminCarreraController::class, 'index'])->middleware('role:admin')->name('admin.index');

    // Ruta para GUARDAR una nueva carrera (Método POST)
    Route::post('/admin/dashboard', [AdminCarreraController::class, 'store'])->middleware('role:admin')->name('admin.store');

    // Ruta para VER el detalle-información de una carrera específica (Método GET)
    Route::get('/admin/carrera/{id}', [AdminCarreraController::class, 'show'])->middleware('role:admin')->name('admin.show');

    // Ruta para EDITAR el detalle de una carrera específica (Método PATCH)
    Route::patch('/admin/carrera/{id}', [AdminCarreraController::class, 'update'])->middleware('role:admin')->name('admin.update');

    // Ruta para BORRAR una carrera específica y REDIRIGIR a la principal (Método DELETE)
    Route::delete('/admin/carrera/{id}', [AdminCarreraController::class, 'delete'])->middleware('role:admin')->name('admin.delete');

    Route::get('/dashboard/admin/alumno/expediente', function () {
        return view('dashboard.admin.admin_alumno_expediente');
    })->middleware('role:admin')->name('admin.alumno.expediente');

    Route::get('/dashboard/admin/maestro/perfil', function () {
        return view('dashboard.admin.admin_maestro_perfil');
    })->middleware('role:admin')->name('admin.maestro.perfil');

    Route::get('/dashboard/admin/perfil', function () {
        return view('dashboard.admin.admin_perfil');
    })->middleware('role:admin')->name('admin.perfil');

    // --- Rutas para el Maestro ---

    Route::get('/maestro/dashboard', [MaestroCarreraController::class, 'index'])->middleware('role:maestro')->name('maestro.index');

    Route::get('/maestro/carrera/{id}', [MaestroCarreraController::class, 'show'])->middleware('role:maestro')->name('maestro.show');

    // Ruta para el Alumno - AÑADIDO ->name('alumno.index')
    Route::get('/alumno/dashboard', function () {
        return view('dashboard.alumno.alumno');
    })->middleware('role:alumno')->name('alumno.index');

    Route::get('/dashboard/alumno', function () { 
        return view('dashboard.alumno.alumno');
    })->name('alumno.dashboard');



    Route::get('/dashboard/maestro/perfil', function () {
        return view('dashboard.maestro.perfil_maestro');
    })->middleware('role:maestro')->name('maestro.perfil');

    Route::get('/dashboard/maestro/grupos', function () {
        return view('dashboard.maestro.grupos');
    })->middleware('role:maestro')->name('maestro.grupos');

    Route::get('/dashboard/maestro/expediente/alumno', function () {
        return view('dashboard.maestro.expediente_alumno_maestro');
    })->middleware('role:maestro')->name('maestro.alumno.expediente');


    // --- Rutas para el Alumno ---

    Route::get('/alumno/dashboard', function () {
        return view('dashboard.alumno.alumno');
    })->middleware('role:alumno')->name('alumno.index');

    Route::get('/dashboard/alumno/expediente', function () {
        return view('dashboard.alumno.alumno_expediente');
    })->middleware('role:alumno')->name('alumno.expediente');

    Route::get('/dashboard/alumno/calificaciones', function () {
        return view('dashboard.alumno.alumno_calificaciones');
    })->middleware('role:alumno')->name('alumno.calificaciones');
});



Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('layouts.dashboard');
});
