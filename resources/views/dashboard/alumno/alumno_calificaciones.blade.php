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
            
            {{-- ============================================================ --}}
            {{-- SECCIÓN GLOBAL: ANALÍTICA E INTELIGENCIA ARTIFICIAL          --}}
            {{-- ============================================================ --}}
            @if(isset($dataCienciaDatos))
                <!-- DIAGNÓSTICO ACADÉMICO EN TEXTO -->
                <div class="diagnostico-card" style="
                    margin-bottom: 25px; 
                    padding: 20px; 
                    background: #ffffff; 
                    border-radius: 12px; 
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); 
                    border-left: 6px solid #20B2AA;
                    font-family: inherit;
                ">
                    <h3 style="
                        margin: 0 0 15px 0; 
                        color: #1a252f; 
                        font-size: 1.15rem; 
                        font-weight: 700; 
                        display: flex; 
                        align-items: center; 
                        gap: 8px;
                    ">
                        📊 Diagnóstico Académico Proyectado
                    </h3>

                    <div style="
                        display: grid; 
                        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
                        gap: 12px; 
                        margin-bottom: 15px;
                    ">
                        <!-- Calificación Estimada -->
                        <div style="background: #f8f9fa; padding: 10px 14px; border-radius: 8px; border: 1px solid #e9ecef;">
                            <span style="font-size: 0.8rem; color: #6c757d; display: block; font-weight: 600; text-transform: uppercase;">Calificación Estimada</span>
                            <strong style="font-size: 1.2rem; color: #2b3a4a;">{{ $dataCienciaDatos['prediccion_nota'] }}</strong>
                        </div>

                        <!-- Estatus de Riesgo -->
                        <div style="background: #f8f9fa; padding: 10px 14px; border-radius: 8px; border: 1px solid #e9ecef;">
                            <span style="font-size: 0.8rem; color: #6c757d; display: block; font-weight: 600; text-transform: uppercase;">Estatus de Riesgo</span>
                            <strong style="font-size: 1rem; color: #2b3a4a;">{{ $dataCienciaDatos['estatus_riesgo'] }}</strong>
                        </div>

                        <!-- Perfil -->
                        <div style="background: #f8f9fa; padding: 10px 14px; border-radius: 8px; border: 1px solid #e9ecef;">
                            <span style="font-size: 0.8rem; color: #6c757d; display: block; font-weight: 600; text-transform: uppercase;">Perfil Académico</span>
                            <strong style="font-size: 1rem; color: #008080;">{{ $dataCienciaDatos['cluster_nombre'] }}</strong>
                        </div>
                    </div>

                    <!-- Recomendación -->
                    <div style="
                        background: #eef9f8; 
                        padding: 12px 15px; 
                        border-radius: 8px; 
                        color: #0f5132; 
                        font-size: 0.9rem; 
                        line-height: 1.4;
                    ">
                        💡 <strong>Recomendación:</strong> <em>{{ $dataCienciaDatos['recomendacion'] }}</em>
                    </div>
                </div>

                <!-- CONTENEDOR DEL GRÁFICO GLOBAL -->
                <div style="margin-bottom: 25px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <h3 style="margin-bottom: 5px; color: #333; font-weight: bold;">Trayectoria Académica y Proyección</h3>
                    <p style="font-size: 0.9em; color: #666; margin-bottom: 15px;">Evolución del desempeño histórico y tendencia proyectada.</p>
                    
                    <div style="position: relative; height:320px; width: 100%;">
                        <canvas id="graficaDesempenio"></canvas>
                    </div>
                </div>

                <!-- Carga directa de Chart.js y Script del Gráfico -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
                <script>
                    (function() {
                        function initChart() {
                            const ctx = document.getElementById('graficaDesempenio');
                            if (!ctx) return;

                            const p1 = {{ $promedioP1 ?? 0 }};
                            const p2 = {{ $promedioP2 ?? 0 }};
                            const prediccionFinal = {{ $dataCienciaDatos['prediccion_nota'] ?? 0 }};

                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: ['Parcial 1', 'Parcial 2', 'Proyección Final'],
                                    datasets: [{
                                        label: 'Desempeño y Tendencia',
                                        data: [p1, p2, prediccionFinal],
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        backgroundColor: 'rgba(54, 162, 235, 0.15)',
                                        borderWidth: 3,
                                        pointRadius: 6,
                                        pointBackgroundColor: ['#36A2EB', '#36A2EB', '#FF6384'],
                                        pointBorderColor: '#fff',
                                        pointHoverRadius: 8,
                                        fill: true,
                                        tension: 0.3
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top'
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return ` Calificación: ${context.parsed.y}`;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: false,
                                            min: 0,
                                            max: 10,
                                            ticks: {
                                                stepSize: 1
                                            }
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

            <hr style="border: 0; height: 1px; background: #e0e0e0; margin: 30px 0;">

            {{-- ============================================================ --}}
            {{-- SECCIÓN DETALLE: DESGLOSE POR PERÍODO                       --}}
            {{-- ============================================================ --}}
            <div class="filtro-periodo">
                <div class="periodo-select">
                    <form method="GET" action="{{ route('alumno.calificaciones') }}">
                        <label for="periodoSelect">{{ __('messages.label_period') }}</label>
                        <select name="periodo" id="periodoSelect" onchange="this.form.submit()">
                            <option value="" {{ !$periodoSeleccionado ? 'selected' : '' }}>
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

            {{-- TABLA DE CALIFICACIONES --}}
            @if($periodoSeleccionado)
                <div class="tabla-calificaciones">
                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2">{{ __('messages.th_subject') }}</th>
                                <th colspan="4">{{ __('messages.first_partial') }}</th>
                                <th colspan="4">{{ __('messages.second_partial') }}</th>
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
            @endif

        </div>
    </div>
@endsection