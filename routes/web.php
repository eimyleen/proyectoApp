<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Redirección raíz al login
Route::redirect('/', '/login');

// --- Rutas de autenticación ---

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Grupos de rutas protegidas por autenticación ---

Route::middleware(['auth'])->group(function () {
    
    // --- Rutas para el Admin ---

    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin.admin');
    })->middleware('role:admin')->name('admin.index');

    Route::get('/dashboard/admin/carrera', function () {
        return view('dashboard.admin.admin_carrera');
    })->middleware('role:admin')->name('admin.carrera');

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

    Route::get('/maestro/dashboard', function () {
        return view('dashboard.maestro.maestro');
    })->middleware('role:maestro')->name('maestro.index');

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
