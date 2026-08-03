<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CarreraController;
use App\Http\Controllers\Api\MaestroController;
use App\Http\Controllers\Api\GrupoController;
use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\MateriaController;
use App\Http\Controllers\Api\HorarioController;
use App\Http\Controllers\Api\CalificacionController;
use App\Http\Middleware\ApiKeyMiddleware;

// Ruta pública de login
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con API Key y Sanctum
Route::middleware([ApiKeyMiddleware::class, 'auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Api Users
    Route::apiResource('users', UserController::class);
    Route::get('/api/users/{id}/maestro/carreras', [UserController::class, 'getUserWithMaestroAndCarreras']);
    // Api Carreras
    Route::apiResource('carreras', CarreraController::class);
    // Api Maestros
    Route::apiResource('maestros', MaestroController::class);
    // Api Grupos
    Route::apiResource('grupos', GrupoController::class);
    // Api Alumnos
    Route::apiResource('alumnos', AlumnoController::class);
    // Api Materias
    Route::apiResource('materias', MateriaController::class);
    // Api Horarios
    Route::apiResource('horarios', HorarioController::class);
    // Api Calificaciones
    Route::apiResource('calificaciones', CalificacionController::class);
});
