<!-- Dashboard Admin - Módulo de gestión de carreras y usuarios -->
@extends('layouts.dashboard')

@section('title', 'Administrador - Carreras')

@section('subtitle', 'Gestiona las carreras de la universidad')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="admin-dashboard-container">
        <!-- Botones superiores -->
        <div class="admin-buttons">
            <a href="{{ route('admin.logs') }}" style="text-decoration: none;">
                <button class="btn-lista-global" id="btnLogs">
                    Logs del sistema
                </button>
            </a>
            <button class="btn-lista-global" id="btnRespaldos">
                Respaldos
            </button>
            <button class="btn-lista-global" id="btnListaGlobal">
                Ver lista de alumnos global
            </button>
            <button class="btn-agregar-carrera" id="btnAgregarCarrera">
                + Agregar carrera
            </button>
        </div>
        <form action="{{ route("admin.store") }}" method="POST">
            @csrf
            <div id="modalAgregarCarrera" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Agregar carrera</h3>
                        <span class="modal-close" id="closeModalCarrera">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre de la carrera</label>
                            <input name="nombre" type="text" id="nombreCarrera" placeholder="Ej: Ingeniería en Sistemas">
                        </div>
                        <div class="form-group">
                            <label>Clave de la carrera</label>
                            <input name="clave" type="text" id="claveCarrera" placeholder="Ej: ISC">
                        </div>
                        <div class="form-group">
                            <label>Logo de la carrera</label>
                            <input name="logo" type="file" id="logoCarrera" accept="image/*">
                            <small class="form-text">Selecciona una imagen para el logo</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-guardar" id="guardarCarrera">Guardar carrera</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- Grid de carreras -->
        <div class="carreras-grid">
            @foreach ($carreras as $carrera)
                <div class="carrera-card" data-carrera="{{ $carrera->nombre }}">
                    <div class="carrera-img">
                        <a href="{{ route('admin.show', $carrera) }}">
                            @if($carrera->logo)
                                @if(file_exists($carrera->logo))
                                    <img src="{{ asset($carrera->logo) }}" alt="{{ $carrera->nombre }}">
                                @else
                                    <img src="{{ asset('img/jaguar.png') }}" alt="Sin logo">
                                @endif
                            @else
                                <img src="{{ asset('img/jaguar.png') }}" alt="Sin logo">
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para lista global de alumnos - CORREGIDO -->
    <div id="modalListaGlobal" class="modal-lista-global">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Lista de alumnos global</h3>
                <span class="modal-close" id="closeModalListaGlobal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-actions">
                    <div class="modal-filtro">
                        <img src="{{ asset('img/lupa.png') }}" alt="Buscar" class="lupa-icon-modal">
                        <input type="text" id="busquedaModal" placeholder="Buscar por nombre o matrícula..." class="input-busqueda-modal">
                    </div>
                    <a href="{{ route('admin.alumnos.pdf') }}" style="text-decoration: none;">
                        <button class="btn-lista-global">
                            <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icono">
                            Descargar lista global
                        </button>
                    </a>
                </div>
                <div class="tabla-container">
                    <table class="tabla-alumnos-global" id="tablaAlumnosModal">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Carrera</th>
                                <th>Grupo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alumnos as $alumno)
                                <tr>
                                    <td>{{ $alumno->matricula }}</td>
                                    <td>{{ $alumno->user?->name }}</td>
                                    <td>{{ $alumno->user?->apellido }}</td>
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
     <div id="modalRespaldos" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Respaldos</h3>
                <span class="modal-close" id="closeModalRespaldos">&times;</span>
            </div>
            <form class="modal-body" action="{{ route("admin.respaldo") }}" method="POST">
                <button type="submit" class="btn-guardar" id="btnRespaldosExec">Respaldo Ahora</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Agregar Carrera
        const modalCarrera = document.getElementById('modalAgregarCarrera');
        const btnAgregar = document.getElementById('btnAgregarCarrera');
        const closeModalCarrera = document.getElementById('closeModalCarrera');

        if (btnAgregar) {
            btnAgregar.onclick = function() {
                modalCarrera.style.display = 'flex';
            };
        }

        function cerrarModalCarrera() {
            modalCarrera.style.display = 'none';
            document.getElementById('nombreCarrera').value = '';
            document.getElementById('claveCarrera').value = '';
            document.getElementById('logoCarrera').value = '';
        }

        if (closeModalCarrera) closeModalCarrera.onclick = cerrarModalCarrera;

        const btnRespaldos = document.getElementById('btnRespaldos');
        const btonCerrarRespaldos= document.getElementById('closeModalRespaldos');
        const modalRespaldos = document.getElementById('modalRespaldos');

        if (btnRespaldos) {
            btnRespaldos.onclick = function() {
                modalRespaldos.style.display = 'flex';
            };
        }

        function cerrarModalRespaldos() {
            modalRespaldos.style.display = 'none';
        }

        if (btonCerrarRespaldos) btonCerrarRespaldos.onclick = cerrarModalRespaldos;

        // Modal Lista Global de Alumnos 
        const modalListaGlobal = document.getElementById('modalListaGlobal');
        const btnListaGlobal = document.getElementById('btnListaGlobal');
        const closeModalListaGlobal = document.getElementById('closeModalListaGlobal');

        if (btnListaGlobal) {
            btnListaGlobal.onclick = function() {
                modalListaGlobal.style.display = 'flex';
            };
        }

        function cerrarModalListaGlobal() {
            modalListaGlobal.style.display = 'none';
        }

        if (closeModalListaGlobal) closeModalListaGlobal.onclick = cerrarModalListaGlobal;

        // Cerrar modales al hacer clic fuera
        window.onclick = function(e) {
            if (e.target === modalCarrera) cerrarModalCarrera();
            if (e.target === modalListaGlobal) cerrarModalListaGlobal();
        };

        // Filtro dentro del modal de lista global
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