{{-- 
    ============================================================
    MAESTRO - GRUPOS DE UNA CARRERA
    ============================================================
    Esta vista muestra los grupos de una carrera específica.
    Muestra:
    - Header con el logo y nombre de la carrera
    - Filtro para seleccionar un grupo
    - Panel con el tutor del grupo seleccionado
    - Tabla de alumnos con sus datos y botón para ver expediente
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_maestro.css
    - Botón de regreso: visible (back-button)
    - Se conecta con: maestro.alumno.expediente (ver expediente del alumno)
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
    En este caso: "Grupos - Maestro"
--}}
@section('title', __('messages.groups_title'))
@section('subtitle', __('messages.groups_subtitle'))

@section('title', 'Grupos - Maestro')
@section('user-role', 'Maestro')
@section('avatar-iniciales', 'CS')
@section('nombre-completo', 'Carlos Sánchez')
@section('welcome-message', '[Nombre de la Carrera]')
@section('subtitle', 'Selecciona un grupo para ver sus alumnos')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
--}}
@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

{{-- 
    URL DE REGRESO
    Define a dónde redirige el botón de regreso.
    En este caso, al dashboard de maestro.
--}}
@section('back-url', '/dashboard/maestro')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    
    {{-- 
        CONTENEDOR PRINCIPAL DE GRUPOS
        Fondo blanco con sombra y bordes redondeados.
    --}}
    <div class="grupos-container">
        
        {{-- ======================================================
             HEADER DE LA CARRERA
             ====================================================== 
             Muestra el logo, nombre y clave de la carrera.
             También un mensaje descriptivo sobre la gestión de grupos.
        --}}
        <div class="carrera-header-grupos">
            
            {{-- Logo circular de la carrera --}}
            <div class="carrera-logo-grupos">
                <div class="logo-circular-grupos">
                    @if($carrera->logo)
                        <img src="{{ asset($carrera->logo) }}" 
                            alt="{{ $carrera->nombre }}"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <img src="{{ asset('img/jaguar.png') }}" alt="Sin logo">
                    @endif
                </div>
            </div>
            
            {{-- Información de la carrera --}}
            <div class="carrera-info-grupos">
                <h2>{{ $carrera->nombre }}</h2>
                <p class="carrera-clave">{{ __('messages.groups_id_card') }}: {{ $carrera->clave }}</p>
                <p>{{ __('messages.groups_management') }}</p>
            </div>
        </div>

        {{-- ======================================================
             FILTRO DE GRUPOS
             ====================================================== 
             Select para elegir el grupo a visualizar.
             Los grupos se cargarán dinámicamente desde el backend.
        --}}
        <div class="filtro-grupos">
            <form method="GET">
                <select name="grupo_id" class="grupo-select" onchange="this.form.submit()">
                    <option value="">{{ __('messages.select_group') }}</option>
                    @foreach ($grupos as $grupo)
                        <option value="{{ $grupo->id }}"
                            {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>
                            {{ $grupo->nombre }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- ======================================================
             PANEL DEL TUTOR
             ====================================================== 
             Muestra el tutor del grupo seleccionado.
             El nombre se actualiza dinámicamente con JavaScript.
        --}}
        <div class="tutor-info-panel">
            <div class="tutor-info">
                <span class="tutor-label">{{ __('messages.groups_tutor') }}:</span>
                <span class="tutor-nombre" id="tutorNombre">{{ __('messages.groups_no_tutor') }}</span>
            </div>
            {{-- 
                ======================================================
                NOTA: CAMBIO REALIZADO - DESCARGA DE LISTA DEL GRUPO
                ======================================================
                Se reemplazó el alert() original por una alerta de
                confirmación con SweetAlert.
                
                Originalmente solo mostraba un mensaje con alert().
                ======================================================
            --}}
            <button class="btn-descargar-grupo" id="btnDescargarGrupo">
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga">
                {{ __('messages.groups_download_list') }}
            </button>
        </div>

        {{-- ======================================================
             TABLA DE ALUMNOS
             ====================================================== 
             Muestra la lista de alumnos del grupo seleccionado.
             Columnas:
             - Número (consecutivo)
             - Matrícula
             - Nombre
             - Apellido
             - Acciones (botón para ver expediente)
        --}}
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
                    {{-- 
                        BUCLE PARA MOSTRAR ALUMNOS
                        Solo muestra los alumnos que pertenecen a la carrera actual.
                        El número de fila se genera con $loop->iteration o $i+1.
                    --}}
                    @foreach($alumnos as $i => $alumno)
                        <tr>
                            <td class="col-numero">{{ $i+1 }}</td>
                            <td class="col-matricula">{{ $alumno->matricula }}</td>
                            <td class="col-nombre">{{ $alumno->user?->name }}</td>
                            <td class="col-nombre">{{ $alumno->user?->apellido }}</td>
                            <td class="col-acciones">
                                {{-- 
                                    BOTÓN VER EXPEDIENTE
                                    Redirige a la vista del expediente del alumno
                                    desde la perspectiva del maestro.
                                --}}
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

{{-- 
    ======================================================
    SCRIPTS ADICIONALES
    ======================================================
    NOTA: FUNCIONALIDAD AGREGADA - CONFIRMACIÓN DE DESCARGA
    ======================================================
    Se reemplazó el alert() original por una alerta de
    confirmación con SweetAlert.
    
    El flujo es:
    1. Usuario hace clic en "Descargar lista"
    2. Aparece alerta de confirmación
    3. Si confirma → muestra mensaje informativo
    4. Si cancela → no pasa nada
    ======================================================
--}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        1. Botón de regreso
        2. Carga de tutor al seleccionar grupo
        3. Confirmación antes de descargar lista del grupo (NUEVO)
    --}}

    document.addEventListener('DOMContentLoaded', function() {
        
        // ==============================================
        // 1. BOTÓN DE REGRESO
        // ==============================================
        const backButton = document.getElementById('backButton');
        if (backButton) {
            backButton.addEventListener('click', function() {
                window.location.href = '/dashboard/maestro';
            });
        }

        // ==============================================
        // 2. CARGA DE TUTOR AL SELECCIONAR GRUPO
        // ==============================================
        const tutorNombreSpan = document.getElementById('tutorNombre');
        const grupoSelect = document.getElementById('grupoSelect');

        function cargarTutor(grupo) {
            // Esta función se llenará con datos del backend
            // Por ahora, muestra un mensaje por defecto
            if (tutorNombreSpan) {
                tutorNombreSpan.textContent = '{{ __('messages.groups_no_tutor') }}';
            }
        }

        if (grupoSelect) {
            grupoSelect.addEventListener('change', function() {
                cargarTutor(this.value);
            });
        }

        // ==============================================
        // 3. CONFIRMAR DESCARGA DE LISTA DEL GRUPO
        // ==============================================
        // Originalmente: alert('{{ __('messages.groups_download_alert') }}');
        const btnDescargarGrupo = document.getElementById('btnDescargarGrupo');
        if (btnDescargarGrupo) {
            btnDescargarGrupo.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Obtener el nombre del grupo seleccionado
                const select = document.querySelector('.grupo-select');
                const optionSelected = select ? select.options[select.selectedIndex] : null;
                const nombreGrupo = optionSelected ? optionSelected.textContent : 'seleccionado';
                
                // Mostrar alerta de confirmación
                confirmarAccion(
                    'Descargar lista del grupo',
                    `Se generará un archivo PDF con la lista de alumnos del grupo "${nombreGrupo}". ¿Deseas continuar?`,
                    'Descargar',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        // NOTA: La descarga real se integrará cuando la ruta esté definida
                        alertaInfo(
                            'Descarga de lista',
                            'La funcionalidad de descarga se integrará próximamente.'
                        );
                    }
                });
            });
        }
    });
</script>
@endpush