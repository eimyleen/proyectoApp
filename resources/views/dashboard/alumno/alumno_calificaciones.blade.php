@extends('layouts.dashboard')

@section('title', __('messages.title_my_grades'))

@section('subtitle', __('messages.subtitle_grades'))

@section('title', 'Mis Calificaciones - Alumno')

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
            <a href="{{ route('alumno.expediente') }}" style="text-decoration: none;">
                <button class="btn-expediente {{ Request::routeIs('alumno.expediente') ? 'active' : '' }}">
                    {{ __('messages.btn_record') }}
                </button>
            </a>
            <a href="{{ route('alumno.calificaciones') }}" style="text-decoration: none;">
                <button class="btn-calificaciones {{ Request::routeIs('alumno.calificaciones') ? 'active' : '' }}">
                    {{ __('messages.btn_grades') }}
                </button>
            </a>
            
            <!-- Logo circular de la carrera -->
            <div class="carrera-logo">
                <div class="logo-circular">
                    @if(Auth::user()->alumno->carrera->logo)
                        <img src="{{ asset(Auth::user()->alumno->carrera->logo) }}" 
                            alt="Logo {{ Auth::user()->alumno->carrera->nombre }}"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <img src="{{ asset('img/jaguar.png') }}" alt="UTNay">
                    @endif
                </div>
                
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="contenido-principal">
            <!-- Filtro de período -->
            <div class="filtro-periodo">
                <div class="periodo-select">
                    <label>{{ __('messages.label_period') }}</label>
                    <select>
                        <option value="">{{ __('messages.select_period') }}</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de calificaciones -->
            <div class="tabla-calificaciones">
                <table>
                    <thead>
                        <tr>
                           <th>{{ __('messages.th_subject') }}</th>
                            <th>{{ __('messages.th_grade') }}</th>
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