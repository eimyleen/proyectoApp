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
            
            <div class="filtro-periodo">
                <div class="periodo-select">
                    <form method="GET" action="{{ route('alumno.calificaciones') }}">
                        <label for="periodoSelect">{{ __('messages.label_period') }}</label>
                        <select name="periodo" id="periodoSelect" onchange="this.form.submit()">
                            <option value="" {{ $periodoSeleccionado ? 'selected' : '' }}>
                                {{ __('messages.select_period') }}
                            </option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo }}" {{ $periodoSeleccionado == $periodo ? 'selected' : '' }}>
                                    {{ $periodo }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            

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
                                <th rowspan="2">{{ __('messages.th_subject') }}</th>
                                <th colspan="4">{{ __('messages.first_period') }}</th>
                                <th colspan="4">{{ __('messages.second_period') }}</th>
                                <th rowspan="2">{{ __('messages.final_grade') }}</th>
                            </tr>
                            <tr>
                                <th>{{ __('messages.first_ordinal_grade') }}</th><th>{{ __('messages.first_remedial_grade') }}</th><th>{{ __('messages.first_extraordinary_grade') }}</th><th>{{ __('messages.first_final_grade') }}</th>
                                <th>{{ __('messages.second_ordinal_grade') }}</th><th>{{ __('messages.second_remedial_grade') }}</th><th>{{ __('messages.second_extraordinary_grade') }}</th><th>{{ __('messages.second_final_grade') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($calificacionesCalculadas as $cal)
                                <tr>
                                    <td>{{ $cal->materia->nombre ?? __('messages.not_assigned') }}</td>
                                    @for($p=1; $p<=2; $p++)
                                        <td>{{ $cal->parciales[$p]['co'] ?? '-' }}</td>
                                        <td>{{ $cal->parciales[$p]['cr'] ?? '-' }}</td>
                                        <td>{{ $cal->parciales[$p]['ce'] ?? '-' }}</td>
                                        <td class="calificacion {{ ($cal->parciales[$p]['cf'] ?? 0) >= 8 ? 'aprobado' : 'reprobado' }}">
                                            {{ $cal->parciales[$p]['cf'] !== null ? number_format($cal->parciales[$p]['cf'], 1) : '-' }}
                                        </td>
                                    @endfor
                                    <td class="calificacion {{ ($cal->nota_final ?? 0) >= 8 ? 'aprobado' : 'reprobado' }}">
                                        {{ $cal->nota_final !== null ? number_format($cal->nota_final, 1) : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align: center;">{{ __('messages.table_empty_grades') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="9" style="text-align: right;"><strong>{{ __('messages.period_average') . ':' }}</strong></td>
                                <td class="calificacion {{ $promedioPeriodo >= 8 ? 'aprobado' : 'reprobado' }}">
                                    {{ number_format($promedioPeriodo, 1) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection