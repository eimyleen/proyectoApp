<!-- Dashboard Maestro - Módulo de carreras que imparte -->
@extends('layouts.dashboard')

@section('title', 'Panel Maestro')

@section('subtitle', 'Selecciona una carrera para gestionar sus grupos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

@section('content')
    <!-- Botones superiores -->
    <div class="maestro-buttons">
        <button class="btn-lista-global" id="btnListaGlobal">Ver lista de alumnos global</button>
    </div>

    <!-- Carreras -->
    <div class="carreras-container">
        <div class="carreras-grid">
            @foreach ($carreras as $carrera)
                <div class="carrera-card" data-carrera="{{ $carrera->nombre }}">
                    <div class="carrera-img">
                        <a href="{{ route("maestro.show", $carrera) }}">
                            @if($carrera->logo)
                                <img src="{{ asset($carrera->logo) }}" 
                                    alt="{{ $carrera->nombre }}"
                                    style="width: 100%; height: 100%; object-fit: contain;">
                            @else
                                <img src="{{ asset('img/jaguar.png') }}" alt="Sin logo">
                            @endif
                        </a>
                    </div>
                    <div class="carrera-info">
                        <p>{{ $carrera->nombre }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para lista global de alumnos -->
    <div id="modalListaGlobal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Lista de alumnos global</h3>
                <span class="modal-close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-actions">
                    <div class="modal-filtro">
                        <img src="{{ asset('img/lupa.png') }}" alt="Buscar" class="lupa-icon-modal">
                        <input type="text" id="busquedaModal" placeholder="Buscar por nombre o matrícula..." class="input-busqueda-modal">
                    </div>
                    <button class="btn-descargar-modal">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-modal">
                        Descargar lista
                    </button>
                </div>
                <div class="tabla-container">
                    <table class="tabla-alumnos-global" id="tablaAlumnosModal">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Carrera</th>
                                <th>Grupo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alumnos as $alumno)
                                <tr>
                                    <td>{{ $alumno->matricula }}</td>
                                    <td>{{ $alumno->user?->name }}</td>
                                    <td>{{ $alumno->carrera?->nombre }}</td>
                                    <td>{{ $alumno->grupo }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal lista global
        const modal = document.getElementById('modalListaGlobal');
        const btn = document.getElementById('btnListaGlobal');
        const closeBtn = document.getElementById('closeModal');

        if (btn && modal && closeBtn) {
            btn.addEventListener('click', function() {
                modal.style.display = 'flex';
            });

            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Filtro dentro del modal
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