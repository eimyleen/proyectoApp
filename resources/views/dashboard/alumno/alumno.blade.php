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

@section('content')
    <!-- Tabla de materias -->
    <div class="materias-titulo">
        <h3>Mis materias</h3>
    </div>
    <div class="tabla-materias">
         <table>
            <thead>
                 <tr>
                    <th>Materia</th>
                    <th>Docente</th>
                 </tr>
            </thead>
            <tbody>
                 <tr><td></td><td></td></tr>
                 <tr><td></td><td></td></tr>
                 <tr><td></td><td></td></tr>
                 <tr><td></td><td></td></tr>
                 <tr><td></td><td></td></tr>
            </tbody>
         </table>
    </div>

    <!-- Tabla de horario -->
    <div class="horario-titulo">
        <h3>Mi horario</h3>
    </div>
    <div class="tabla-horario">
         <table>
            <thead>
                 <tr>
                    <th>Día</th>
                    <th>Horario</th>
                 </tr>
            </thead>
            <tbody>
                 <tr><td>Lunes</td><td></td></tr>
                 <tr><td>Martes</td><td></td></tr>
                 <tr><td>Miércoles</td><td></td></tr>
                 <tr><td>Jueves</td><td></td></tr>
                 <tr><td>Viernes</td><td></td></tr>
                 <tr><td>Sábado</td><td class="dia-bloqueado">Sin clases</td></tr>
                 <tr><td>Domingo</td><td class="dia-bloqueado">Sin clases</td></tr>
            </tbody>
         </table>
    </div>
@endsection