<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminCarreraController;
use App\Http\Controllers\MaestroCarreraController;
use App\Http\Controllers\LogController;

// Redirección raíz al login
Route::redirect('/', '/login');

// ---------- Rutas de autenticación ----------

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ---------- Grupos de rutas protegidas por autenticación ----------

Route::middleware(['auth'])->group(function () {

    // --- Ruta para actualizar la foto de perfil (Método PUT) ---
    Route::put('/perfil/foto', [
        AuthController::class, 'updateFoto'
    ])->name('perfil.foto.update');
    
    // ---------- Rutas para el Admin ----------

    // -- Ruta del dashboard del Admin  ---
    Route::get('/admin/dashboard', [
        AdminCarreraController::class, 'index'
    ])->middleware('role:admin')->name('admin.index');

    Route::get('/dashboard/admin/logs', [
        LogController::class, 'index'
    ])->middleware(['auth', 'role:admin'])->name('admin.logs');

    // --- Ruta para DESCARGAR el PDF con la lista de alumnos completa ---
    Route::get('/dashboard/admin/descargar-alumnos', [
        AdminCarreraController::class, 'descargarAlumnosPDF'
    ])->middleware(['auth', 'role:admin'])->name('admin.alumnos.pdf');

    // ----- Rutas referentes a la gestión de carreras -----

    // Ruta para GUARDAR una nueva carrera (Método POST)
    Route::post('/admin/dashboard', [
        AdminCarreraController::class, 'store'
    ])->middleware('role:admin')->name('admin.store');

    // Ruta para VER el detalle-información de una carrera específica (Método GET)
    Route::get('/admin/carrera/{id}', [
        AdminCarreraController::class, 'show'
    ])->middleware('role:admin')->name('admin.show');

    // Ruta para EDITAR el detalle de una carrera específica (Método PATCH)
    Route::patch('/admin/carrera/{id}', [
        AdminCarreraController::class, 'update'
    ])->middleware('role:admin')->name('admin.update');

    // Ruta para BORRAR una carrera específica y REDIRIGIR a la principal (Método DELETE)
    Route::delete('/admin/carrera/{id}', [
        AdminCarreraController::class, 'delete'
    ])->middleware('role:admin')->name('admin.delete');

    Route::get('/admin/respaldos', function() {
        return view("dashboard.admin.admin_respaldo_auto");
    })->middleware('role:admin')->name('admin.respaldo');

    // ----- Rutas referentes a la gestión de usuarios -----

    // --- Ruta para VER el detalle-información de un Alumno específico desde la perspectiva del Admin ---
    Route::get('/dashboard/admin/alumno/expediente/{id}', [
        AdminCarreraController::class, 'verExpediente'
        ])->middleware('role:admin')->name('admin.alumno.expediente');

    // --- Ruta para VER el detalle-información de un Maestro específico desde la perspectiva del Admin ---
    Route::get('/dashboard/admin/maestro/perfil/{id}', [
        AdminCarreraController::class, 'verPerfilMaestro'
    ])->middleware('role:admin')->name('admin.maestro.perfil');

    // --- Ruta del perfil del Admin ---
    Route::get('/dashboard/admin/perfil', function () {
        return view('dashboard.admin.admin_perfil');
    })->middleware('role:admin')->name('admin.perfil');


    // ---------- Rutas para el Maestro ----------

    // --- Ruta del dashboard del Maestro ---
    Route::get('/maestro/dashboard', [
        MaestroCarreraController::class, 'index'
    ])->middleware('role:maestro')->name('maestro.index');

    // --- Ruta para DESCARGAR el PDF con la lista de alumnos completa ---
    Route::get('/dashboard/maestro/descargar-alumnos', [
        MaestroCarreraController::class, 'descargarAlumnosPDF'
    ])->middleware(['auth', 'role:maestro'])->name('maestro.alumnos.pdf');

    // --- Ruta para VER el detalle-información de una carrera específica desde la perspectiva del Maestro ---
    Route::get('/maestro/carrera/{id}', [
        MaestroCarreraController::class, 'show'
    ])->middleware('role:maestro')->name('maestro.show');

    // --- Ruta del perfil del Maestro ---
    Route::get('/dashboard/maestro/perfil', function () {
        return view('dashboard.maestro.perfil_maestro');
    })->middleware('role:maestro')->name('maestro.perfil');

    // --- Ruta de los grupos del Maestro ---
    Route::get('/dashboard/maestro/grupos', function () {
        return view('dashboard.maestro.grupos');
    })->middleware('role:maestro')->name('maestro.grupos');

    // --- Ruta del expediente de un Alumno desde la perspectiva del Maestro ---
    Route::get('/dashboard/maestro/expediente/alumno/{id}', [
        MaestroCarreraController::class, 'verExpedienteAlumno'
    ])->middleware('role:maestro')->name('maestro.alumno.expediente');


    // ---------- Rutas para el Alumno ----------

    // --- Ruta del dashboard del Alumno
    Route::get('/alumno/dashboard', function () {
        return view('dashboard.alumno.alumno');
    })->middleware('role:alumno')->name('alumno.index');

    // --- Ruta del perfil del Alumno ---
    Route::get('/dashboard/alumno/expediente', function () {
        return view('dashboard.alumno.alumno_expediente');
    })->middleware('role:alumno')->name('alumno.expediente');

    // --- Ruta de las calificaciones del Alumno ---
    Route::get('/dashboard/alumno/calificaciones', function () {
        return view('dashboard.alumno.alumno_calificaciones');
    })->middleware('role:alumno')->name('alumno.calificaciones');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('locale/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'es'])) {
        Session::put('locale', $lang);
        App::setLocale($lang);
    }
    return back(); // Esto te regresa a la página donde estabas
})->name('set_language');
