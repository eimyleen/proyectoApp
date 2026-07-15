@extends('layouts.dashboard')
@section('title', __('messages.groups_title'))
@section('subtitle', __('messages.groups_subtitle'))
@section('title', 'Grupos - Maestro')
@section('user-role', 'Maestro')
@section('avatar-iniciales', 'CS')
@section('nombre-completo', 'Carlos Sánchez')
@section('welcome-message', '[Nombre de la Carrera]')
@section('subtitle', 'Selecciona un grupo para ver sus alumnos')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/maestro')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

@section('content')
    <div class="grupos-container">
        <!-- Header de la carrera -->
        <div class="carrera-header-grupos">
            <div class="carrera-logo-grupos">
                <div class="logo-circular-grupos" style="width: 120px; height: 120px; overflow: hidden;">
                    @if($carrera->logo)
                        <img src="{{ asset($carrera->logo) }}" 
                            alt="{{ $carrera->nombre }}"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <img src="{{ asset('img/jaguar.png') }}" alt="Sin logo">
                    @endif
                </div>
            </div>
            <div class="carrera-info-grupos">
                <h2>{{ $carrera->nombre }}</h2>
                <p class="carrera-clave">{{ __('messages.groups_id_card') }}: {{ $carrera->clave }}</p>
               <p>{{ __('messages.groups_management') }}</p>
            </div>
        </div>

        <!-- Filtro de grupos -->
        <div class="filtro-grupos">
            <select class="grupo-select" id="grupoSelect">
                <option value="">{{ __('messages.groups_select') }}</option>
                <!-- Los grupos se cargarán dinámicamente desde el backend -->
            </select>
        </div>

        <!-- Panel de información del tutor -->
        <div class="tutor-info-panel">
            <div class="tutor-info">
                <span class="tutor-label">{{ __('messages.groups_tutor') }}:</span>
                <span class="tutor-nombre" id="tutorNombre"></span>
            </div>
            <button class="btn-descargar-grupo" id="btnDescargarGrupo">
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga">
                {{ __('messages.groups_download_list') }}
            </button>
        </div>

        <!-- Tabla de alumnos -->
        <div class="tabla-container">
            <table class="tabla-alumnos" id="tablaAlumnos">
                <thead>
                    <tr>
                        <th>{{ __('messages.groups_no') }}</th>
                        <th>{{ __('messages.groups_id_card') }}</th>
                        <th>{{ __('messages.groups_name') }}</th>
                        <th>{{ __('messages.groups_last_name') }}</th>
                        <th>{{ __('messages.groups_actions') }}</th>
                    </tr>
                </thead>
                <tbody id="alumnosBody">
                    @foreach($alumnos as $i => $alumno)
                        <tr>
                            <td class="col-numero">{{ $i+1 }}</td>
                            <td class="col-matricula">{{ $alumno->matricula }}</td>
                            <td class="col-nombre">{{ $alumno->user?->name }}</td>
                            <td class="col-nombre">{{ $alumno->user?->apellido }}</td>
                            <td class="col-acciones">
                                <a href="{{ route('maestro.alumno.expediente', $alumno->id) }}" style="text-decoration: none;">
                                    <button class="btn-ver-expediente">{{ __('messages.groups_view_record') }}</button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flecha de regreso
        const backButton = document.getElementById('backButton');
        if (backButton) {
            backButton.addEventListener('click', function() {
                window.location.href = '/dashboard/maestro';
            });
        }

        // Tutor (backend llenará)
        const tutorNombreSpan = document.getElementById('tutorNombre');
        const grupoSelect = document.getElementById('grupoSelect');

        function cargarTutor(grupo) {
            if (tutorNombreSpan) {
                tutorNombreSpan.textContent = '';
            }
        }

        if (grupoSelect) {
            grupoSelect.addEventListener('change', function() {
                cargarTutor(this.value);
            });
        }

        // Botón descargar grupo
        const btnDescargarGrupo = document.getElementById('btnDescargarGrupo');
        if (btnDescargarGrupo) {
            btnDescargarGrupo.addEventListener('click', function() {
                alert('Descargar lista del grupo');
            });
        }
    });
</script>
@endpush