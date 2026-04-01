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
