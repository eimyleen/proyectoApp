<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Ruta de login (breeze lo maneja)
require __DIR__.'/auth.php';

// Grupo protegido por autenticación y nuestro redireccionador
Route::middleware(['auth', 'role.redirect'])->group(function () {
    
    // Si alguien entra mediante ruta, se redireccionara a esto
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');

    // Rutas específicas
    Route::get('/dashboard/admin', function () { 
        return view('dashboard.admin.admin');
    })->name('admin.dashboard');

    Route::get('/dashboard/maestro', function () { 
        return view('dashboard.maestro.maestro'); 
    })->name('maestro.dashboard');

    Route::get('/dashboard/alumno', function () { 
        return view('dashboard.alumno.alumno'); 
    })->name('alumno.dashboard');
});

/*
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

*/
