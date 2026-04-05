@extends('layouts.dashboard')

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
                <div class="logo-circular-grupos">
                    <img src="{{ asset('img/carreras/ing_alimentos.png') }}" alt="Logo de la carrera">
                </div>
                <span class="logo-texto-grupos">Logo de la carrera</span>
            </div>
            <div class="carrera-info-grupos">
                <h2>[Nombre de la Carrera]</h2>
                <p class="carrera-clave">Clave: IC</p>
                <p>Gestión de grupos y alumnos</p>
            </div>
        </div>

        <!-- Filtro de grupos -->
        <div class="filtro-grupos">
            <select class="grupo-select" id="grupoSelect">
                <option value="">Seleccionar grupo</option>
                <!-- Los grupos se cargarán dinámicamente desde el backend -->
            </select>
        </div>

        <!-- Panel de información del tutor -->
        <div class="tutor-info-panel">
            <div class="tutor-info">
                <span class="tutor-label">Tutor:</span>
                <span class="tutor-nombre" id="tutorNombre"></span>
            </div>
            <button class="btn-descargar-grupo" id="btnDescargarGrupo">
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga">
                Descargar lista del grupo
            </button>
        </div>

        <!-- Tabla de alumnos -->
        <div class="tabla-container">
            <table class="tabla-alumnos" id="tablaAlumnos">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Matrícula</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="alumnosBody">
                    @for($i = 1; $i <= 4; $i++)
                        <tr>
                            <td class="col-numero">{{ $i }}</td>
                            <td class="col-matricula"></td>
                            <td class="col-nombre"></td>
                            <td class="col-acciones">
                                <button class="btn-ver-expediente">Ver expediente</button>
</td>
                        </tr>
                    @endfor
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

        // Botón ver expediente
        document.querySelectorAll('.btn-ver-expediente').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Ver expediente del alumno');
            });
        });
    });
</script>
@endpush