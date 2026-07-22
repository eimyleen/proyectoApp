{{-- 
    ============================================================
    MAESTRO - DASHBOARD PRINCIPAL
    ============================================================
    Esta es la vista principal del módulo de maestro.
    Muestra:
    - Botón "Lista global" para ver todos los alumnos
    - Grid de carreras que imparte el maestro (con logos circulares)
    - Modal para ver la lista global de alumnos con filtro y descarga PDF
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_maestro.css
    - Se conecta con: maestro.show (detalle de carrera)
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
    En este caso: "Panel Maestro"
--}}
@section('title', __('messages.teacher_panel_title'))
@section('subtitle', __('messages.teacher_panel_subtitle'))

@section('title', 'Panel Maestro')
@section('subtitle', 'Selecciona una carrera para gestionar sus grupos')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')

    {{-- 
        BOTÓN SUPERIOR - LISTA GLOBAL
        Abre un modal con la lista de todos los alumnos
        y opción para descargar PDF.
    --}}
    <div class="maestro-buttons">
        <button class="btn-lista-global" id="btnListaGlobal">
            {{ __('messages.btn_global_list') }}
        </button>
    </div>

    {{-- 
        GRID DE CARRERAS
        Muestra las carreras que el maestro imparte.
        Cada carrera es un círculo con su logo.
        Al hacer clic, redirige a maestro.show(carrera)
    --}}
    <div class="carreras-container">
        <div class="carreras-grid">
            @foreach ($carreras as $carrera)
                {{-- Solo mostrar carreras que el maestro imparte --}}
                @if(Auth::user()->maestro?->carreras->contains('id', $carrera->id))
                    <div class="carrera-card" data-carrera="{{ $carrera->nombre }}">
                        <div class="carrera-img">
                            <a href="{{ route('maestro.show', $carrera) }}">
                                @if($carrera->logo)
                                    <img src="{{ asset($carrera->logo) }}" 
                                        alt="{{ $carrera->nombre }}"
                                        style="width: 100%; height: 100%; object-fit: contain;">
                                @else
                                    <img src="{{ asset('img/jaguar.png') }}" alt="{{ __('messages.no_logo') }}">
                                @endif
                            </a>
                        </div>
                        {{-- Clave de la carrera debajo del logo --}}
                        <div class="carrera-clave">{{ $carrera->clave ?? '' }}</div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- 
        ======================================================
        MODAL - LISTA GLOBAL DE ALUMNOS
        ====================================================== 
        Este modal muestra:
        - Filtro de búsqueda (por nombre, matrícula, carrera, grupo)
        - Botón para descargar PDF con la lista completa
        - Tabla con todos los alumnos
    --}}
    <div id="modalListaGlobal" class="modal">
        <div class="modal-content">
            
            {{-- HEADER DEL MODAL --}}
            <div class="modal-header">
                <h3>{{ __('messages.modal_global_title') }}</h3>
                <span class="modal-close" id="closeModal">&times;</span>
            </div>

            {{-- BODY DEL MODAL --}}
            <div class="modal-body">
                
                {{-- Acciones: Filtro + Botón descargar --}}
                <div class="modal-actions">
                    <div class="modal-filtro">
                        <img src="{{ asset('img/lupa.png') }}" alt="Buscar" class="lupa-icon-modal">
                        <input type="text" id="busquedaModal" 
                               placeholder="{{ __('messages.placeholder_search_modal') }}" 
                               class="input-busqueda-modal">
                    </div>
                    <a href="{{ route('maestro.alumnos.pdf') }}" style="text-decoration: none;">
                        <button class="btn-descargar-modal">
                            <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-modal">
                            {{ __('messages.btn_download_list') }}
                        </button>
                    </a>
                </div>

                {{-- TABLA DE ALUMNOS --}}
                <div class="tabla-container">
                    <table class="tabla-alumnos-global" id="tablaAlumnosModal">
                        <thead>
                            <tr>
                                <th>{{ __('messages.label_id_number') }}</th>
                                <th>{{ __('messages.th_nombre') }}</th>
                                <th>{{ __('messages.label_major') }}</th>
                                <th>{{ __('messages.label_group') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alumnos as $alumno)
                                <tr>
                                    <td>{{ $alumno->matricula }}</td>
                                    <td>{{ $alumno->user?->name }}</td>
                                    <td>{{ $alumno->carrera?->nombre }}</td>
                                    <td>{{ $alumno->grupos->first()?->nombre ?? 'No Asignado' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- SCRIPTS ADICIONALES --}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        1. Modal de lista global (abrir/cerrar)
        2. Filtro de búsqueda en tabla (por cualquier columna)
    --}}

    document.addEventListener('DOMContentLoaded', function() {
        
        {{-- 1. MODAL LISTA GLOBAL --}}
        const modal = document.getElementById('modalListaGlobal');
        const btn = document.getElementById('btnListaGlobal');
        const closeBtn = document.getElementById('closeModal');

        if (btn && modal && closeBtn) {
            {{-- Abrir modal --}}
            btn.addEventListener('click', function() {
                modal.style.display = 'flex';
            });

            {{-- Cerrar modal con la X --}}
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            {{-- Cerrar modal al hacer clic fuera --}}
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }

        {{-- 2. FILTRO DE BÚSQUEDA EN LA TABLA --}}
        const inputBusquedaModal = document.getElementById('busquedaModal');
        if (inputBusquedaModal) {
            inputBusquedaModal.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                const filas = document.querySelectorAll('#tablaAlumnosModal tbody tr');
                
                filas.forEach(fila => {
                    const texto = fila.innerText.toLowerCase();
                    if (texto.includes(busqueda) || busqueda === '') {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush