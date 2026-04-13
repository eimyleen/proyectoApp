<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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
    });

    Route::get('/dashboard/admin/alumno/expediente', function () {
        return view('dashboard.admin.admin_alumno_expediente');
    });

    Route::get('/dashboard/admin/maestro/perfil', function () {
        return view('dashboard.admin.admin_maestro_perfil');
    });

    Route::get('/dashboard/admin/perfil', function () {
        return view('dashboard.admin.admin_perfil');
    });

    // --- Rutas para el Maestro ---

    Route::get('/maestro/dashboard', function () {
        return view('dashboard.maestro.maestro');
    })->middleware('role:maestro')->name('maestro.index');

    Route::get('/dashboard/maestro/perfil', function () {
        return view('dashboard.maestro.perfil_maestro');
    });

    Route::get('/dashboard/maestro/grupos', function () {
        return view('dashboard.maestro.grupos');
    });

    Route::get('/dashboard/maestro/expediente/alumno', function () {
        return view('dashboard.maestro.expediente_alumno_maestro');
    });


    // --- Rutas para el Alumno ---

    Route::get('/alumno/dashboard', function () {
        return view('dashboard.alumno.alumno');
    })->middleware('role:alumno')->name('alumno.index');

    Route::get('/dashboard/alumno/expediente', function () {
        return view('dashboard.alumno.alumno_expediente');
    });

    Route::get('/dashboard/alumno/calificaciones', function () {
        return view('dashboard.alumno.alumno_calificaciones');
    });
});



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('layouts.dashboard');
});
