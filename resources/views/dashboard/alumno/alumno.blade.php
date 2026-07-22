{{-- 
    ============================================================
    ALUMNO - DASHBOARD PRINCIPAL
    ============================================================
    Esta es la vista principal del módulo de alumno.
    Muestra:
    - Botones laterales para navegar a Expediente y Calificaciones
    - Tabla de materias (con docente)
    - Tabla de horario (días de la semana)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_alumno.css
    - Comparte botones con: alumno_expediente y alumno_calificaciones
    ============================================================ 
--}}

{{-- 
    Extiende el layout base del dashboard.
    Esto significa que esta vista se "inyecta" dentro del layout
    en la sección @yield('content')
--}}
@extends('layouts.dashboard')

@section('title', __('messages.student_panel'))

@section('subtitle', __('messages.student_subtitle'))

@section('title', 'Mi Panel - Alumno')

@section('subtitle', 'Aquí puedes consultar tu información académica')

{{-- ======================================================
     CSS ADICIONAL
     ====================================================== 
     Aquí se agrega el CSS específico para el módulo de alumno.
     Este archivo contiene los estilos de:
     - Botones laterales
     - Tablas (materias y horario)
     - Diseño responsive
--}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

{{-- ======================================================
     CONTENIDO PRINCIPAL
     ====================================================== 
     Todo lo que esté dentro de @section('content') se
     inyectará en el layout, en el lugar donde está @yield('content')
--}}
@section('content')
    {{-- 
        CONTENEDOR PRINCIPAL CON FLEX
        Organiza en dos columnas:
        - Izquierda: Botones laterales
        - Derecha: Tablas (materias y horario)
    --}}
    <div class="contenido-con-botones">
        
        {{-- ======================================================
             BOTONES LATERALES (Columna izquierda)
             ====================================================== 
             Estos botones permiten navegar entre las secciones
             del módulo de alumno:
             - "Expediente" → redirige a alumno_expediente
             - "Calificaciones" → redirige a alumno_calificaciones
             
             El botón "Expediente" tiene la clase 'active' porque
             estamos en la vista principal del alumno.
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
        </div>

        {{-- ======================================================
             TABLAS (Columna derecha)
             ====================================================== --}}
        <div class="tablas-contenedor">
            
            @php
                $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
            @endphp

            <div>
                <h2>Horario de Clases</h2>

                {{-- Información general del alumno --}}
                @if($grupo)
                    <p><strong>Grupo:</strong> {{ $grupo->nombre }}</p>
                @endif

                {{-- @if($carrera)
                    <p><strong>Carrera:</strong> {{ $carrera->nombre }}</p>
                @endif  no sé si mostrarlo o no--}}

                <hr>

                {{-- Tablas de horarios por día --}}
                @foreach($diasSemana as $dia)
                    <section>
                        <h3>{{ $dia }}</h3>

                        <table>
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Materia</th>
                                    <th>Docente</th>
                                    <th>Aula</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($horarios->get($dia, []) as $clase)
                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($clase->hora_inicio)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($clase->hora_fin)->format('H:i') }}
                                        </td>
                                        <td>{{ $clase->materia->nombre }}</td>
                                        <td>
                                            {{ $clase->maestro->user->name }} {{ $clase->maestro->user->apellido }}
                                        </td>
                                        <td>{{ $clase->aula }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Sin clases programadas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </section>
                    <br>
                @endforeach
            </div>
        </div>
    </div>
@endsection