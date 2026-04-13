<!-- Dashboard Admin - Módulo de gestión de carreras y usuarios -->
@extends('layouts.dashboard')

@section('title', 'Administrador - Carreras')
@section('user-role', 'Administrador')
@section('avatar-iniciales', 'AD')
@section('nombre-completo', 'Admin User')
@section('welcome-message', '¡Bienvenido, Administrador!')
@section('subtitle', 'Gestiona las carreras de la universidad')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="admin-dashboard-container">
        <!-- Botones superiores -->
        <div class="admin-buttons">
            <button class="btn-lista-global" id="btnListaGlobal">
                Ver lista de alumnos global
            </button>
            <button class="btn-agregar-carrera" id="btnAgregarCarrera">
                + Agregar carrera
            </button>
        </div>

        <!-- Grid de carreras -->
        <div class="carreras-grid">
            @foreach($carreras as $carrera)
                <div class="carrera-card" data-carrera="{{ $carrera['alt'] }}">
                    <div class="carrera-img">
                        <img src="{{ asset('img/carreras/' . $carrera['img']) }}" alt="{{ $carrera['alt'] }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para agregar carrera -->
    <div id="modalAgregarCarrera" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar carrera</h3>
                <span class="modal-close" id="closeModalCarrera">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre de la carrera</label>
                    <input type="text" id="nombreCarrera" placeholder="Ej: Ingeniería en Sistemas">
                </div>
                <div class="form-group">
                    <label>Clave de la carrera</label>
                    <input type="text" id="claveCarrera" placeholder="Ej: ISC">
                </div>
                <div class="form-group">
                    <label>Logo de la carrera</label>
                    <input type="file" id="logoCarrera" accept="image/*">
                    <small class="form-text">Selecciona una imagen para el logo</small>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarCarrera">Cancelar</button>
                <button class="btn-guardar" id="guardarCarrera">Guardar carrera</button>
            </div>
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
                            @for($i = 0; $i < 5; $i++)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endfor
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
        // Modal Agregar Carrera
        const modalCarrera = document.getElementById('modalAgregarCarrera');
        const btnAgregar = document.getElementById('btnAgregarCarrera');
        const closeModalCarrera = document.getElementById('closeModalCarrera');
        const cancelarCarrera = document.getElementById('cancelarCarrera');

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
        if (cancelarCarrera) cancelarCarrera.onclick = cerrarModalCarrera;

        // Guardar carrera
        const guardarCarrera = document.getElementById('guardarCarrera');
        if (guardarCarrera) {
            guardarCarrera.onclick = function() {
                const nombre = document.getElementById('nombreCarrera').value;
                const clave = document.getElementById('claveCarrera').value;
                if (nombre && clave) {
                    alert('Carrera agregada: ' + nombre + ' (' + clave + ')');
                    cerrarModalCarrera();
                } else {
                    alert('Por favor complete el nombre y la clave de la carrera');
                }
            };
        }

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

        // Click en carrera para ir al detalle
        document.querySelectorAll('.carrera-card').forEach(card => {
            card.addEventListener('click', function() {
                const nombre = this.getAttribute('data-carrera');
                alert('Navegar a detalle de: ' + nombre);
                // window.location.href = '/dashboard/admin/carrera';
            });
        });
    });
</script>
@endpush