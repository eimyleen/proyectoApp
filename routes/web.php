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