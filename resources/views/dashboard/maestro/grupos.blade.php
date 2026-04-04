@extends('layouts.dashboard')

@section('title', 'Grupos - Maestro')
@section('user-role', 'Maestro')
@section('avatar-iniciales', 'CS')
@section('nombre-completo', 'Carlos Sánchez')
@section('welcome-message', 'Grupos de [Nombre de la Carrera]')
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
        <!-- Header de la carrera con logo -->
        <div class="carrera-header-grupos">
            <div class="carrera-logo-grupos">
                <div class="logo-circular-grupos">
                    <img src="{{ asset('img/carreras/ing_alimentos.png') }}" alt="Logo de la carrera">
                </div>
                <span class="logo-texto-grupos">Logo de la carrera</span>
            </div>
            <div class="carrera-info-grupos">
                <h2>[Nombre de la Carrera]</h2>
                <p>Gestión de grupos y alumnos</p>
            </div>
        </div>

        <!-- Filtro de grupos -->
        <div class="filtro-grupos">
            <label>Seleccionar grupo</label>
            <select class="grupo-select" id="grupoSelect">
                <option value="">Seleccionar grupo</option>
                <!-- Los grupos se cargarán dinámicamente desde el backend -->
            </select>
        </div>

        <!-- Panel de información -->
        <div class="carrera-info-panel">
            <div class="tutor-info">
                <span class="tutor-label">Tutor:</span>
                <span class="tutor-nombre" id="tutorNombre"></span>
            </div>
            <button class="btn-descargar-grupo" id="btnDescargarGrupo">
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga">
                Descargar lista del grupo
            </button>
        </div>

        <!-- Tabla de alumnos con columna No. -->
        <div class="tabla-container">
            <table class="tabla-alumnos" id="tablaAlumnos">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Matrícula</th>
                        <th>Nombre</th>
                        <th>Carrera</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaBody">
                    @for($i = 1; $i <= 2; $i++)
                        <tr>
                            <td class="numero-lista">{{ $i }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn-ver-expediente">Ver expediente</button></td>
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
        const grupoSelect = document.getElementById('grupoSelect');
        const tablaBody = document.getElementById('tablaBody');
        const tutorNombre = document.getElementById('tutorNombre');

        // Función para mantener las filas en blanco
        function mantenerFilasBlanco() {
            tablaBody.innerHTML = '';
            for (let i = 1; i <= 2; i++) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="numero-lista">${i}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><button class="btn-ver-expediente">Ver expediente</button></td>
                `;
                tablaBody.appendChild(row);
            }
        }

        // Cuando se selecciona un grupo, solo se actualiza el tutor (si el backend envía datos)
        if (grupoSelect) {
            grupoSelect.addEventListener('change', function() {
                const grupo = this.value;
                if (grupo) {
                    // Aquí el backend cargaría el tutor del grupo seleccionado
                    tutorNombre.textContent = '';
                    mantenerFilasBlanco();
                } else {
                    tutorNombre.textContent = '';
                    mantenerFilasBlanco();
                }
            });
        }
        
        // Inicializar con filas en blanco
        mantenerFilasBlanco();
    });
</script>
@endpush