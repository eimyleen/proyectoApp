<!-- Dashboard Alumno - Módulo de materias y horarios -->
@extends('layouts.dashboard')

@section('title', __('messages.student_panel'))

@section('subtitle', __('messages.student_subtitle'))

@section('title', 'Mi Panel - Alumno')

@section('subtitle', 'Aquí puedes consultar tu información académica')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

@section('content')
    <div class="contenido-con-botones">
        <!-- Botones laterales -->
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

        <!-- Tablas -->
        <div class="tablas-contenedor">
            <!-- Tabla de materias -->
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

            <!-- Tabla de horario -->
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
                        
                        {{-- Fines de semana bloqueados como ya los tenías --}}
                        <tr><td>{{ __('messages.day_saturday') }}</td><td class="dia-bloqueado">{{ __('messages.no_classes') }}</td></tr>
                        <tr><td>{{ __('messages.day_sunday') }}</td><td class="dia-bloqueado">{{ __('messages.no_classes') }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection