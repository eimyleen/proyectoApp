{{-- 
    ============================================================
    ALUMNO - CALIFICACIONES
    ============================================================
    Esta vista muestra las calificaciones del alumno con:
    - Botones laterales (Expediente, Calificaciones activo, Logo carrera)
    - Filtro de período para seleccionar el período académico
    - Tabla de calificaciones (Materia + Calificación)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_alumno.css
    - Botón de regreso: visible (back-button)
    - Comparte botones con: alumno y alumno_expediente
    ============================================================ 
--}}

@extends('layouts.dashboard')

@section('title', __('messages.title_my_grades'))

@section('subtitle', __('messages.subtitle_grades'))

@section('title', 'Mis Calificaciones - Alumno')

@section('subtitle', 'Aquí puedes consultar tus calificaciones por período')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
--}}
@section('back-button')
    <!-- Botón de regreso visible -->
@endsection

{{-- ======================================================
     CSS ADICIONAL
     ====================================================== --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

{{-- ======================================================
     CONTENIDO PRINCIPAL
     ====================================================== --}}
@section('content')
    {{-- 
        CONTENEDOR PRINCIPAL CON FLEX
        Organiza en dos columnas:
        - Izquierda: Botones laterales (Expediente, Calificaciones activo, Logo carrera)
        - Derecha: Contenido de calificaciones (filtro + tabla)
    --}}
    <div class="contenido-con-botones">
        
        {{-- ======================================================
             BOTONES LATERALES (Columna izquierda)
             ====================================================== 
             - "Expediente" → redirige a alumno_expediente
             - "Calificaciones" tiene la clase 'active' porque estamos en esta sección
             - "Logo de carrera" muestra el logo circular de la carrera del alumno
        --}}
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
            
            {{-- ==================================================
                 LOGO CIRCULAR DE LA CARRERA
                 ================================================== 
                 Muestra el logo de la carrera del alumno.
                 Si no hay logo, muestra el logo de UTNay por defecto.
            --}}
            <div class="carrera-logo">
                <div class="logo-circular">
                    @if($carrera?->logo)
                        <img src="{{ asset($carrera->logo) }}" 
                            alt="Logo {{ $carrera->nombre }}"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <img src="{{ asset('img/jaguar.png') }}" alt="UTNay">
                    @endif
                </div>
                
            </div>
        </div>

        {{-- ======================================================
             CONTENIDO PRINCIPAL DE CALIFICACIONES (Columna derecha)
             ====================================================== --}}
        <div class="contenido-principal">
            
            {{-- ==================================================
                 FILTRO DE PERÍODO
                 ================================================== 
                 Permite al alumno seleccionar el período académico
                 para filtrar sus calificaciones.
                 
                 ESTRUCTURA:
                 - Label: "Período:"
                 - Select: Desplegable con opciones de períodos
                 - Botón: "Buscar" con ícono de lupa
                 
                 NOTA: Las opciones del select se llenan desde
                 el controlador con la variable $periodos.
            --}}
            <form method="GET" action="{{ route('alumno.calificaciones') }}">
                <div class="filtro-periodo">
                    <div class="periodo-select">
                        <label for="periodoSelect">{{ __('messages.label_period') }}</label>
                        <select name="periodo" id="periodoSelect">
                            <option value="" {{ empty($periodoSeleccionado) ? 'selected' : '' }}>
                                {{ __('messages.select_period') }}
                            </option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo }}" {{ $periodoSeleccionado == $periodo ? 'selected' : '' }}>
                                    {{ $periodo }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-buscar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            Buscar
                        </button>
                    </div>
                </div>
            </form>

            {{-- ==================================================
                 TABLA DE CALIFICACIONES
                 ================================================== 
                 Muestra las calificaciones del alumno por materia.
                 
                 ESTRUCTURA DE LA TABLA:
                 - Header: "Materia" y "Calificación"
                 - Cuerpo: Filas con materia y calificación
                 - Columna "Calificación": Tiene la clase 'calificacion'
                   que la centra y le da un ancho fijo (120px)
                 
                 NOTA: 
                 - Los datos se llenan dinámicamente desde el controlador con $calificaciones
                 - Cada calificación tiene colores según su valor:
                   - >= 8: 'aprobado' (verde)
                   - < 8: 'reprobado' (rojo)
                 - La tabla solo se muestra si el usuario seleccionó un período
            --}}
            @if($periodoSeleccionado)
                <div class="tabla-calificaciones">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('messages.th_subject') }}</th>
                                <th>{{ __('messages.th_grade') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($calificaciones as $cal)
                                <tr>
                                    <td>{{ $cal->materia->nombre ?? 'N/A' }}</td>
                                    <td class="calificacion {{ $cal->calificacion >= 8 ? 'aprobado' : 'reprobado' }}">
                                        {{ number_format($cal->calificacion, 1) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center;">No hay calificaciones registradas en este período.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection