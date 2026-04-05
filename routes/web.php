<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('layouts.dashboard');
});

Route::get('/dashboard/alumno', function () {
    return view('dashboard.alumno.alumno');
});

Route::get('/dashboard/alumno/expediente', function () {
    return view('dashboard.alumno.alumno_expediente');
});

Route::get('/dashboard/alumno/calificaciones', function () {
    return view('dashboard.alumno.alumno_calificaciones');
});

Route::get('/dashboard/maestro', function () {
    return view('dashboard.maestro.maestro');
});

Route::get('/dashboard/maestro/perfil', function () {
    return view('dashboard.maestro.perfil_maestro');
});

Route::get('/dashboard/maestro/grupos', function () {
    return view('dashboard.maestro.grupos');
});

Route::get('/dashboard/maestro/expediente/alumno', function () {
    return view('dashboard.maestro.expediente_alumno_maestro');
});

Route::get('/dashboard/admin/carrera', function () {
    return view('dashboard.admin.admin_carrera');
});

Route::get('/dashboard/admin', function () {
    return view('dashboard.admin.admin');
});

Route::get('/dashboard/admin/alumno/expediente', function () {
    return view('dashboard.admin.admin_alumno_expediente');
});

Route::get('/dashboard/admin/maestro/perfil', function () {
    return view('dashboard.admin.admin_maestro_perfil');
});