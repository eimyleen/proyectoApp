@extends('layouts.dashboard')

@section('title', 'Mi Panel - Alumno')
@section('user-role', 'Alumno')
@section('welcome-message', '¡Bienvenido, Carlos!')
@section('subtitle', 'Aquí puedes consultar tu información académica')
@section('sticky-avatar', 'CM')
@section('sticky-name', 'Carlos Martínez')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

@section('role-options')
    <div class="alumno-buttons">
        <button class="btn-expediente">Expediente</button>
        <button class="btn-calificaciones">Calificaciones</button>
    </div>
@endsection

@section('content')
    <div class="alumno-content">
        <p class="placeholder-text">Aquí se mostrará el contenido según la opción seleccionada</p>
    </div>
@endsection