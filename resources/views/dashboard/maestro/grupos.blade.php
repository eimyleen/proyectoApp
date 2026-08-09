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
             MÓDULO DE ANÁLISIS DE GRUPO & DOUGHNUT CHART
             ====================================================== --}}
            @if(isset($analisisGrupo))
                <div style="margin-bottom: 25px; padding: 20px 24px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 12px; border: 1px solid #334155; display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);">
                    
                    {{-- LADO IZQUIERDO: Promedio General Proyectado --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; border-right: 1px solid #334155; padding-right: 25px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="background: rgba(59, 130, 246, 0.15); padding: 12px; border-radius: 10px; border: 1px solid rgba(59, 130, 246, 0.3);">
                                <span style="font-size: 1.8rem;">📊</span>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: 600;">Análisis Académico Predictivo</span>
                                <h3 style="margin: 2px 0 0; color: #f8fafc; font-size: 1.15rem; font-weight: 600;">Promedio General Proyectado</h3>
                            </div>
                        </div>
                        
                        <div style="text-align: right; background: #0f172a; padding: 10px 18px; border-radius: 10px; border: 1px solid #1e293b;">
                            <span style="font-size: 1.8rem; font-weight: 800; color: #38bdf8; font-family: monospace;">
                                {{ number_format($analisisGrupo['promedio_grupo_proyectado'], 1) }}
                            </span>
                            <span style="display: block; font-size: 0.75rem; color: #64748b;">/ 10.0 pts</span>
                        </div>
                    </div>

                    {{-- LADO DERECHO: Gráfico de Distribución de Riesgo --}}
                    <div style="display: flex; align-items: center; gap: 15px; width: 280px;">
                        <div style="position: relative; width: 110px; height: 110px;">
                            <canvas id="chartRiesgoGrupo"></canvas>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 6px;">
                                Distribución de Riesgo
                            </span>
                            @php
                                $dist = $analisisGrupo['distribucion_riesgo'] ?? ['Alto' => 0, 'Medio' => 0, 'Bajo' => 0];
                            @endphp
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; color: #e2e8f0; display: flex; flex-direction: column; gap: 3px;">
                                <li style="display: flex; align-items: center; gap: 6px;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #ef4444; display: inline-block;"></span>
                                    Alto: <strong>{{ $dist['Alto'] ?? 0 }}</strong>
                                </li>
                                <li style="display: flex; align-items: center; gap: 6px;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #f59e0b; display: inline-block;"></span>
                                    Medio: <strong>{{ $dist['Medio'] ?? 0 }}</strong>
                                </li>
                                <li style="display: flex; align-items: center; gap: 6px;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #22c55e; display: inline-block;"></span>
                                    Bajo: <strong>{{ $dist['Bajo'] ?? 0 }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            @endif

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

        @if(request()->filled('grupo_id'))
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
            <a href="{{ request('grupo_id') ? route('maestro.grupo.analisis.pdf', request('grupo_id')) : '#' }}" 
               class="btn-descargar-grupo" 
               style="margin-left: 10px; text-decoration: none; display: inline-flex; align-items: center;"
               @if(!request('grupo_id')) onclick="alertaInfo('Sin selección', 'Por favor, selecciona un grupo primero.'); return false;" @endif>
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga">
                Descargar Análisis PDF
            </a>
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
                        <th>Riesgo Académico</th>
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
                            <td class="col-riesgo">
                                @php
                                    $riesgo = 'Bajo';
                                    $color = 'green';
                                    if ($analisisGrupo) {
                                        foreach($analisisGrupo['alumnos_riesgo'] as $a) {
                                            if ($a['alumno_id'] == $alumno->id) {
                                                $riesgo = $a['riesgo'];
                                                $color = $riesgo == 'Alto' ? 'red' : ($riesgo == 'Medio' ? 'orange' : 'green');
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <span style="background: {{ $color }}; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;">
                                    {{ $riesgo }}
                                </span>
                            </td>
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
        @endif
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
{{-- CDN de Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ==============================================
        // INICIALIZACIÓN DE CHART.JS (DOUGHNUT CHART)
        // ==============================================
        const ctxRiesgo = document.getElementById('chartRiesgoGrupo');
        if (ctxRiesgo) {
            @php
                $dist = $analisisGrupo['distribucion_riesgo'] ?? ['Alto' => 0, 'Medio' => 0, 'Bajo' => 0];
            @endphp
            
            const distribucionData = {
                alto: {{ $dist['Alto'] ?? 0 }},
                medio: {{ $dist['Medio'] ?? 0 }},
                bajo: {{ $dist['Bajo'] ?? 0 }}
            };

            new Chart(ctxRiesgo, {
                type: 'doughnut',
                data: {
                    labels: ['Alto', 'Medio', 'Bajo'],
                    datasets: [{
                        data: [distribucionData.alto, distribucionData.medio, distribucionData.bajo],
                        backgroundColor: [
                            '#ef4444', // Rojo (Alto)
                            '#f59e0b', // Naranja/Amarillo (Medio)
                            '#22c55e'  // Verde (Bajo)
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Ocultamos la leyenda nativa porque la hicimos personalizada en HTML
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` Alumnos: ${context.raw}`;
                                }
                            }
                        }
                    },
                    cutout: '70%' // Hace el anillo más delgado y elegante
                }
            });
        }

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
        const btnDescargarGrupo = document.getElementById('btnDescargarGrupo');
        if (btnDescargarGrupo) {
            btnDescargarGrupo.addEventListener('click', function(e) {
                e.preventDefault();
                
                const select = document.querySelector('.grupo-select');
                const grupoId = select ? select.value : null;
                
                if (!grupoId) {
                    alertaInfo('Sin selección', 'Por favor, selecciona un grupo primero.');
                    return;
                }

                const nombreGrupo = select.options[select.selectedIndex].textContent;
                
                confirmarAccion(
                    'Descargar lista del grupo',
                    `Se generará un archivo PDF con la lista de alumnos del grupo "${nombreGrupo}". ¿Deseas continuar?`,
                    'Descargar',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("maestro.grupo.alumnos.pdf", "__ID__") }}'.replace('__ID__', grupoId);
                    }
                });
            });
        }

        // ==============================================
        // 4. DESCARGAR ANÁLISIS DE GRUPO PDF
        // ==============================================
        const btnDescargarAnalisis = document.getElementById('btnDescargarAnalisis');
        if (btnDescargarAnalisis) {
            btnDescargarAnalisis.addEventListener('click', function(e) {
                e.preventDefault();

                const select = document.querySelector('.grupo-select');
                const grupoId = select ? select.value : null;

                if (!grupoId) {
                    alertaInfo('Sin selección', 'Por favor, selecciona un grupo primero.');
                    return;
                }

                window.location.href = '{{ route("maestro.grupo.analisis.pdf", "__ID__") }}'.replace('__ID__', grupoId);
            });
        }
    });
</script>

@endpush