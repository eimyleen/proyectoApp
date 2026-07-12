<!-- Dashboard Admin - Módulo de gestión de carreras y usuarios -->
@extends('layouts.dashboard')

@section('title', __('messages.admin_title'))
@section('subtitle', __('messages.admin_subtitle'))
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
                    {{ __('messages.admin_btn_logs') }}
                </button>
            </a>
            <button class="btn-lista-global" id="btnRespaldos">
                {{ __('messages.admin_btn_backups') }}
            </button>
            <button class="btn-lista-global" id="btnListaGlobal">
                {{ __('messages.admin_btn_global_list') }}
            </button>
            <button class="btn-agregar-carrera" id="btnAgregarCarrera">
                {{ __('messages.admin_btn_add_career') }}
            </button>
        </div>
        <form action="{{ route("admin.store") }}" method="POST" >
            @csrf
            <div id="modalAgregarCarrera" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>{{ __('messages.modal_add_career_title') }}</h3>
                        <span class="modal-close" id="closeModalCarrera">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('messages.modal_career_name') }}</label>
                            <input name="nombre" type="text" id="nombreCarrera" placeholder="{{ __('messages.modal_career_name_placeholder') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('messages.modal_career_key') }}</label>
                            <input name="clave" type="text" id="claveCarrera" placeholder="{{ __('messages.modal_career_key_placeholder') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('messages.modal_career_logo') }}</label>
                            <input name="logo" type="file" id="logoCarrera" accept="image/*">
                            <small class="form-text">{{ __('messages.modal_career_logo_helper') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-guardar" id="guardarCarrera">{{ __('messages.modal_save_career') }}</button>
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
                                <img src="{{ asset('img/jaguar.png') }}" alt="{{ __('messages.admin_no_logo') }}">
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
                <h3>{{ __('messages.modal_global_list_title') }}</h3>
                <span class="modal-close" id="closeModalListaGlobal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-actions">
                    <div class="modal-filtro">
                        <img src="{{ asset('img/lupa.png') }}" alt="Buscar" class="lupa-icon-modal">
                        <input type="text" id="busquedaModal" placeholder= "{{ __('messages.modal_search_placeholder') }}" class="input-busqueda-modal">
                    </div>
                    <a href="{{ route('admin.alumnos.pdf') }}" style="text-decoration: none;">
                        <button class="btn-lista-global">
                            <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icono">
                            {{ __('messages.modal_btn_download_list') }}
                        </button>
                    </a>
                </div>
                <div class="tabla-container">
                    <table class="tabla-alumnos-global" id="tablaAlumnosModal">
                        <thead>
                            <tr>
                                <th>{{ __('messages.expedient_id') }}</th>
                                <th>{{ __('messages.expedient_name') }}</th>
                                <th>{{ __('messages.expedient_last_names') }}</th>
                                <th>{{ __('messages.expedient_career') }}</th>
                                <th>{{ __('messages.expedient_group') }}</th>
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
                <h3>{{ __('messages.modal_backups_title') }}</h3>
                <span class="modal-close" id="closeModalRespaldos">&times;</span>
            </div>
            
            
            <div class="modal-body">
                <form action="/respaldo" method="POST">
                    @csrf
                    <button type="submit" style="margin: 4px 4px;" class="btn-guardar" id="btnRespaldosExec">Respaldo Ahora</button>
                </form>
                <button style="margin: 4px 4px;" class="btn-guardar" id="btnRespaldosAuto">Respaldos Automatizado</button>
            </div>

        </div>
    </div>

    <div id="modalRespaldosAuto" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Respaldos Automatizados</h3>
                <span class="modal-close" id="closeModalRespaldosAuto">&times;</span>
            </div>
            <div class="modal-body">
                dsadasda
            </div>

            <div class="modal-footer">
                <button class="btn-guardar" id="btnRespaldosGuardarIniAuto">Guardar y Iniciar</button>
                <button class="btn-guardar" id="btnRespaldosMainRegreso">Regresar</button>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    //sección donde se maneja cosas de frontend del HTML y CSS (Modales)
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

        //modal respaldos base
        const btnRespaldos = document.getElementById('btnRespaldos');
        const btnRespaldosAuto = document.getElementById('btnRespaldosAuto');
        const btonCerrarRespaldos= document.getElementById('closeModalRespaldos');
        const modalRespaldos = document.getElementById('modalRespaldos');

        const modalRespaldosAuto = document.getElementById('modalRespaldosAuto');


        if (btnRespaldos) {
            btnRespaldos.onclick = function() {
                modalRespaldos.style.display = 'flex';
            };
        }

        function cerrarModalRespaldos() {
            modalRespaldos.style.display = 'none';
        }

        function abrirModalRespaldosAuto() {
            modalRespaldos.style.display = 'none';
            modalRespaldosAuto.style.display = 'flex';
        }

        if (btonCerrarRespaldos) btonCerrarRespaldos.onclick = cerrarModalRespaldos;
        
        if(btnRespaldosAuto) btnRespaldosAuto.onclick = abrirModalRespaldosAuto

        //modal respaldos automatizados
        const closeModalRespaldosAuto = document.getElementById('closeModalRespaldosAuto');
        const btnRespaldosMainRegreso = document.getElementById('btnRespaldosMainRegreso');

        function cerrarModalRespaldosAuto() {
            modalRespaldosAuto.style.display = 'none';
        }

        function regresarModalRespaldos() {
            modalRespaldos.style.display = 'flex';
            modalRespaldosAuto.style.display = 'none';
        }

        if (closeModalRespaldosAuto) closeModalRespaldosAuto.onclick = cerrarModalRespaldosAuto;
        if (btnRespaldosMainRegreso) btnRespaldosMainRegreso.onclick = regresarModalRespaldos;

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
            if (e.target === modalRespaldos) cerrarModalRespaldos();
            if (e.target === modalRespaldosAuto) cerrarModalRespaldosAuto();
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