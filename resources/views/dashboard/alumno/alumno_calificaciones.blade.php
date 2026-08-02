{{-- 
    ============================================================
    ALUMNO - CALIFICACIONES
    ============================================================
--}}

@extends('layouts.dashboard')

@section('title', __('messages.title_my_grades'))
@section('subtitle', __('messages.subtitle_grades'))

@section('back-button')
    <!-- Botón de regreso visible -->
@endsection

@section('content')
    <!-- Carga directa del CSS del módulo -->
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">

    <div class="contenido-con-botones">
        
        {{-- BOTONES LATERALES --}}
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

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="contenido-principal">
            
            {{-- DIAGNÓSTICO ACADÉMICO PROYECTADO --}}
            @if(isset($dataCienciaDatos))
                <div class="diagnostico-card" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9;">
                    <h3>Diagnóstico Académico Proyectado</h3>
                    <p>Calificación Estimada: <strong>{{ $dataCienciaDatos['prediccion_nota'] }}</strong></p>
                    <p>Estatus: <strong>{{ $dataCienciaDatos['estatus_riesgo'] }}</strong></p>
                    <p>Perfil: <strong>{{ $dataCienciaDatos['cluster_nombre'] }}</strong></p>
                    <p>Recomendación: <em>{{ $dataCienciaDatos['recomendacion'] }}</em></p>
                </div>
            @endif

            {{-- FILTRO DE PERÍODO --}}
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

            {{-- TABLA Y GRÁFICO --}}
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
                                <th>{{ __('messages.first_ordinal_grade') }}</th>
                                <th>{{ __('messages.first_remedial_grade') }}</th>
                                <th>{{ __('messages.first_extraordinary_grade') }}</th>
                                <th>{{ __('messages.first_final_grade') }}</th>
                                <th>{{ __('messages.second_ordinal_grade') }}</th>
                                <th>{{ __('messages.second_remedial_grade') }}</th>
                                <th>{{ __('messages.second_extraordinary_grade') }}</th>
                                <th>{{ __('messages.second_final_grade') }}</th>
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

                <!-- CONTENEDOR DEL GRÁFICO -->
                <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="margin-bottom: 15px; color: #333; font-weight: bold;">Análisis de Desempeño Escolar</h3>
                    <div style="position: relative; height:300px; width: 100%;">
                        <canvas id="graficaDesempenio"></canvas>
                    </div>
                </div>

                <!-- Carga directa de CDN y Script Inline -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
                <script>
                    (function() {
                        function initChart() {
                            const ctx = document.getElementById('graficaDesempenio');
                            if (!ctx) return;

                            const promedio = {{ $promedioPeriodo ?? 0 }};
                            const prediccion = {{ $dataCienciaDatos['prediccion_nota'] ?? 'promedio' }};

                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ['Promedio Actual', 'Proyección Ciencia de Datos'],
                                    datasets: [{
                                        label: 'Calificación',
                                        data: [promedio, prediccion],
                                        backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(75, 192, 192, 0.6)'],
                                        borderColor: ['rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'],
                                        borderWidth: 1.5,
                                        borderRadius: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            max: 10
                                        }
                                    }
                                }
                            });
                        }

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', initChart);
                        } else {
                            initChart();
                        }
                    })();
                </script>
            @endif
        </div>
    </div>
@endsection