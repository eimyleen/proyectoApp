/* ============================================================
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
*/

/*
    Extiende el layout base del dashboard.
    Esto significa que esta vista se "inyecta" dentro del layout
    en la sección @yield('content')
*/
@extends('layouts.dashboard')

/* Título de la página (aparece en la pestaña del navegador) */
@section('title', __('messages.student_panel'))

/* Subtítulo que aparece debajo del mensaje de bienvenida en el header */
@section('subtitle', __('messages.student_subtitle'))

/* 
    NOTA: Hay dos @section('title') y dos @section('subtitle')
    La primera usa traducción (__()), la segunda es texto fijo.
    El que prevalece es el último definido.
    En este caso, "Mi Panel - Alumno" y "Aquí puedes consultar tu información académica".
*/

@section('title', 'Mi Panel - Alumno')

@section('subtitle', 'Aquí puedes consultar tu información académica')

/* ======================================================
   CSS ADICIONAL
   ====================================================== 
   Aquí se agrega el CSS específico para el módulo de alumno.
   Este archivo contiene los estilos de:
   - Botones laterales
   - Tablas (materias y horario)
   - Diseño responsive
*/
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

/* ======================================================
   CONTENIDO PRINCIPAL
   ====================================================== 
   Todo lo que esté dentro de @section('content') se
   inyectará en el layout, en el lugar donde está @yield('content')
*/
@section('content')

    /*
        CONTENEDOR PRINCIPAL CON FLEX
        Organiza en dos columnas:
        - Izquierda: Botones laterales
        - Derecha: Tablas (materias y horario)
    */
    <div class="contenido-con-botones">
        
        /* ======================================================
             BOTONES LATERALES (Columna izquierda)
             ====================================================== 
             Estos botones permiten navegar entre las secciones
             del módulo de alumno:
             - "Expediente" → redirige a alumno_expediente
             - "Calificaciones" → redirige a alumno_calificaciones
             
             NOTA: El botón activo se determina con Request::routeIs()
        */
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

        /* ======================================================
             TABLAS (Columna derecha)
             ====================================================== */
        <div class="tablas-contenedor">
            
            /* ==================================================
                 TABLA DE MATERIAS
                 ================================================== 
                 Muestra las materias que el alumno está cursando
                 y el docente que las imparte.
                 
                 ESTRUCTURA DE LA TABLA:
                 - Header: "Materia" y "Docente"
                 - Cuerpo: Filas con los datos usando @forelse
                 
                 NOTA: Si no hay materias, muestra un mensaje de "No tienes materias asignadas."
            */
            <div class="materias-titulo">
                <h3>{{ __('messages.title_my_subjects') }}</h3>
            </div>
            <div class="tabla-materias">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.th_subject') }}</th>
                            <th>{{ __('messages.th_teacher') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materias as $materia)
                            <tr>
                                <td>{{ $materia->nombre }}</td>
                                <td>{{ $materia->docente }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No tienes materias asignadas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            /* ==================================================
                 TABLA DE HORARIO
                 ================================================== 
                 Muestra los días de la semana con el horario de clases.
                 
                 ESTRUCTURA DE LA TABLA:
                 - Header: "Día" y "Horario"
                 - Cuerpo: Lunes a Viernes (con horarios dinámicos)
                 - Sábado y Domingo: "Sin clases" con clase "dia-bloqueado"
                 
                 NOTA: Los horarios se agrupan por día usando $horarios[$diaNombre].
                 Si no hay horario para un día, muestra "Sin clases".
            */
            <div class="horario-titulo">
                <h3>{{ __('messages.title_my_schedule') }}</h3>
            </div>
            <div class="tabla-horario">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.th_day') }}</th>
                            <th>{{ __('messages.th_schedule') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $diasSemana = [
                                'Lunes' => __('messages.day_monday'),
                                'Martes' => __('messages.day_tuesday'),
                                'Miércoles' => __('messages.day_wednesday'),
                                'Jueves' => __('messages.day_thursday'),
                                'Viernes' => __('messages.day_friday')
                            ];
                        @endphp

                        @foreach($diasSemana as $diaNombre => $diaTraduccion)
                            <tr>
                                <td>{{ $diaTraduccion }}</td>
                                <td>
                                    @if(isset($horarios[$diaNombre]))
                                        @foreach($horarios[$diaNombre] as $clase)
                                            <div class="clase-item">
                                                <strong>{{ $clase->materia->nombre }}</strong><br>
                                                <small>{{ \Carbon\Carbon::parse($clase->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($clase->hora_fin)->format('H:i') }} | {{ $clase->aula }}</small>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="sin-clase">Sin clases</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        
                        /* Fines de semana bloqueados */
                        <tr><td>{{ __('messages.day_saturday') }}</td><td class="dia-bloqueado">{{ __('messages.no_classes') }}</td></tr>
                        <tr><td>{{ __('messages.day_sunday') }}</td><td class="dia-bloqueado">{{ __('messages.no_classes') }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection