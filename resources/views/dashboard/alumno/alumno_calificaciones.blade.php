@extends('layouts.dashboard')

@section('title', 'Mis Calificaciones - Alumno')
@section('user-role', 'Alumno')
@section('avatar-iniciales', 'CM')
@section('nombre-completo', 'Carlos Martínez')
@section('welcome-message', 'Mis Calificaciones')
@section('subtitle', 'Aquí puedes consultar tus calificaciones por período')

@section('back-button')
    <!-- Botón de regreso visible -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

@section('content')
    <div class="contenido-con-botones">
        <!-- Botones laterales -->
        <div class="botones-laterales">
            <button class="btn-expediente">Expediente</button>
            <button class="btn-calificaciones active">Calificaciones</button>
            
            <!-- Logo circular de la carrera -->
            <div class="carrera-logo">
                <div class="logo-circular">
                    @if(isset($carrera->logo))
                        <img src="{{ asset('storage/' . $carrera->logo) }}" alt="{{ $carrera->nombre }}">
                    @else

                    @endif
                </div>
                <span class="logo-texto">Logo de la carrera</span>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="contenido-principal">
            <!-- Filtro de período -->
            <div class="filtro-periodo">
                <div class="periodo-select">
                    <label>Período:</label>
                    <select>
                        <option value="">Seleccionar período</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de calificaciones -->
            <div class="tabla-calificaciones">
                <table>
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Calificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection