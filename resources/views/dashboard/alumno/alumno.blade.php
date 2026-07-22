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
     - Información del grupo
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
                {{-- Título principal con margen inferior para separar del grupo --}}
                <h2 style="margin-bottom: 1.5rem;">Horario de Clases</h2>

                {{-- 
                    INFORMACIÓN DEL GRUPO
                    ====================================================== 
                    Muestra el grupo del alumno en un contenedor estilizado
                    con las clases 'grupo-info', 'grupo-etiqueta' y 'grupo-valor'
                    definidas en dashboard_alumno.css
                --}}
                @if($grupo)
                    <div class="grupo-info">
                        <span class="grupo-etiqueta">Grupo:</span>
                        <span class="grupo-valor">{{ $grupo->nombre }}</span>
                    </div>
                @endif

                {{-- @if($carrera)
                    <p><strong>Carrera:</strong> {{ $carrera->nombre }}</p>
                @endif  no sé si mostrarlo o no--}}

                {{-- Eliminamos el <hr> que estaba aquí --}}

                {{-- 
                    TABLAS DE HORARIOS POR DÍA
                    ====================================================== 
                    Cada día de la semana tiene su propia tabla envuelta
                    en un contenedor con la clase 'tabla-horario' para
                    aplicar los estilos definidos en dashboard_alumno.css.
                    Esta clase proporciona:
                    - Fondo blanco
                    - Bordes redondeados
                    - Sombra suave
                    - Estilos de encabezado (fondo oscuro, texto blanco)
                    - Estilos de celdas (padding, bordes, hover)
                --}}
                @foreach($diasSemana as $dia)
                    <section>
                        <h3>{{ $dia }}</h3>

                        {{-- Contenedor con la clase 'tabla-horario' para aplicar los estilos CSS --}}
                        <div class="tabla-horario">
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
                        </div>
                        {{-- Fin del contenedor tabla-horario --}}
                    </section>
                    <br>
                @endforeach
            </div>
        </div>
    </div>
@endsection