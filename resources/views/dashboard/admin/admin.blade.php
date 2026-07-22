{{-- 
    ============================================================
    ADMIN - DASHBOARD PRINCIPAL
    ============================================================
    Esta es la vista principal del módulo de administrador.
    Muestra:
    - Botones superiores (Logs, Respaldos, Lista Global, +Agregar Carrera)
    - Grid de carreras con logos circulares
    - Modal para agregar una nueva carrera
    - Modal para lista global de alumnos con filtro y descarga
    - Modal para gestión de respaldos (generar y configurar)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_admin.css
    - Se conecta con: admin.show (detalle de carrera), admin.logs, etc.
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
--}}
@section('title', __('messages.admin_title'))
@section('subtitle', __('messages.admin_subtitle'))

@section('title', 'Administrador - Carreras')
@section('subtitle', 'Gestiona las carreras de la universidad')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    <div class="admin-dashboard-container">
        
        {{-- ======================================================
             BOTONES SUPERIORES
             ====================================================== 
             Acciones principales del administrador:
             - Logs: Ver el historial de acciones
             - Respaldos: Gestionar respaldos de la base de datos
             - Lista Global: Ver todos los alumnos en un modal
             - +Agregar Carrera: Abre modal para crear una nueva carrera
        --}}
        <div class="admin-buttons">
            {{-- Botón Logs (redirige a una página) --}}
            <a href="{{ route('admin.logs') }}" style="text-decoration: none;">
                <button class="btn-lista-global" id="btnLogs">
                    {{ __('messages.admin_btn_logs') }}
                </button>
            </a>
            
            {{-- Botón Respaldos (abre modal) --}}
            <button class="btn-lista-global" id="btnRespaldos">
                {{ __('messages.admin_btn_backups') }}
            </button>
            
            {{-- Botón Lista Global (abre modal) --}}
            <button class="btn-lista-global" id="btnListaGlobal">
                {{ __('messages.admin_btn_global_list') }}
            </button>
            
            {{-- Botón Agregar Carrera (abre modal) --}}
            <button class="btn-agregar-carrera" id="btnAgregarCarrera">
                {{ __('messages.admin_btn_add_career') }}
            </button>
        </div>

        {{-- ======================================================
             MODAL - AGREGAR CARRERA
             ====================================================== 
             Formulario para crear una nueva carrera.
             Los campos son:
             - Nombre (texto)
             - Clave (texto)
             - Logo (archivo de imagen)
        --}}
        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
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

        {{-- ======================================================
             GRID DE CARRERAS
             ====================================================== 
             Muestra todas las carreras en un grid de círculos.
             Cada carrera tiene su logo y al hacer clic redirige
             a la vista de detalle (admin.show)
        --}}
        <div class="carreras-grid">
            @foreach ($carreras as $carrera)
                <div class="carrera-card" data-carrera="{{ $carrera->nombre }}">
                    <div class="carrera-img">
                        <a href="{{ route('admin.show', $carrera) }}">
                            @if($carrera->logo && file_exists($carrera->logo))
                                <img src="{{ asset($carrera->logo) }}" alt="{{ $carrera->nombre }}">
                            @else
                                <img src="{{ asset('img/jaguar.png') }}" alt="{{ __('messages.admin_no_logo') }}">
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ======================================================
         MODAL - LISTA GLOBAL DE ALUMNOS
         ====================================================== 
         Muestra todos los alumnos en una tabla con:
         - Filtro de búsqueda por texto
         - Botón para descargar lista en PDF
    --}}
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
                        <input type="text" id="busquedaModal" placeholder="{{ __('messages.modal_search_placeholder') }}" class="input-busqueda-modal">
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

    {{-- ======================================================
         MODAL - RESPALDOS (Principal)
         ====================================================== 
         Muestra opciones para gestionar respaldos:
         - Generar un respaldo ahora
         - Configurar respaldos automatizados (abre otro modal)
    --}}
    <div id="modalRespaldos" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_backups_title') }}</h3>
                <span class="modal-close" id="closeModalRespaldos">&times;</span>
            </div>
            <div class="modal-body">
                <form action="/respaldo" method="POST">
                    @csrf
                    <button type="submit" style="margin: 4px 4px;" class="btn-guardar" id="btnRespaldosExec">Generar un Respaldo Ahora</button>
                </form>
                <button style="margin: 4px 4px;" class="btn-guardar" id="btnRespaldosAuto">Configurar Respaldos Automatizados</button>
            </div>
        </div>
    </div>

    {{-- ======================================================
         MODAL - CONFIGURAR RESPALDOS AUTOMATIZADOS
         ====================================================== 
         Permite programar respaldos automáticos con:
         - Fecha de inicio
         - Intervalo de tiempo (minutos, horas, días)
         - Botones para guardar, reiniciar o regresar
    --}}
    <div id="modalRespaldosAuto" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Respaldos Automatizados</h3>
                <span class="modal-close" id="closeModalRespaldosAuto">&times;</span>
            </div>
            <div class="modal-body">
                <h2>Datos de los Respaldos</h2>
                
                <div class="info-text">
                    <strong>Estado del Respaldo Automatico:</strong>
                    <span class="valor">{{ $configBackAuto?->activo ? 'Activo' : 'Inactivo' }}</span>
                </div>
                <div class="info-text">
                    <strong>Fecha de Inicio de Respaldo:</strong>
                    <span class="valor">{{ $configBackAuto->fecha_inicio ?? 'Ninguno - Sin Programar' }}</span>
                </div>
                <div class="info-text">
                    <strong>Ultimo Respaldo Realizado:</strong>
                    <span class="valor">{{ $configBackAuto->ultimo_backup ?? 'Ninguno' }}</span>
                </div>
                <div class="info-text">
                    <strong>Fecha para el próximo Respaldo:</strong>
                    <span class="valor">Ninguno</span>
                </div>

                <br>

                <h2>Configurar Horario de Respaldos</h2>

                <div class="config-grid">
                    <form action="/respaldoAuto" method="post">
                        @csrf
                        <div class="campo-fecha">
                            <label for="fechaIniciarRespaldos">Fecha de Inicio: </label><input type="date" id="fechaIniciarRespaldos" name="fecha_inicio" required min="{{ date("Y-m-d") }}"><br>
                        </div>

                        <div class="subtitle">Horario para los Próximos Respaldos:</div>

                        <div class="campo">
                            <label for="permitirTiemposCheck">Permitir tiempo exacto: </label><input type="checkbox" id="permitirTiemposCheck">
                        </div>
                        <br>
                        <div class="campo">
                            <label for="tiempoMinutosSigRespaldo" id="tiempoMinutosSigRespaldoLab">Minutos: <input type="number" min="1" max="59" value="0" id="tiempoMinutosSigRespaldo" name="minutos" required></label>
                            <label for="tiempoHorasSigRespaldo" id="tiempoHorasSigRespaldoLab">Horas: <input type="number" min="0" max="23" value="0" id="tiempoHorasSigRespaldo" name="horas" required></label>
                            <label for="tiempoDiasSigRespaldo" id="tiempoDiasSigRespaldoLab">Días: <input type="number" min="1" max="31" value="1" id="tiempoDiasSigRespaldo" name="dias" required></label>
                        </div>
                        <br>
                        <button type="submit" class="btn-guardar" id="btnRespaldosGuardarIniAuto">Guardar y Iniciar Respaldo</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <form action="/quitarRespaldoAuto" method="post">
                    @csrf
                    <button type="submit" class="btn-eliminar" id="btnRespaldosBorrarAutoDum">Reiniciar Horario de los Respaldos</button>
                </form>
                <button class="btn-guardar" id="btnRespaldosMainRegreso">
                    <img src="{{ asset('img/flecha.png') }}" alt="Regresar" class="btn-icono" style="width: 16px; height: 16px; filter: brightness(0) invert(1);">
                    Regresar
                </button>
            </div>
        </div>
    </div>
@endsection

{{-- SCRIPTS ADICIONALES --}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        1. Modal Agregar Carrera (abrir/cerrar)
        2. Modal Respaldos (abrir/cerrar)
        3. Modal Respaldos Automatizados (abrir/cerrar)
        4. Modal Lista Global de Alumnos (abrir/cerrar)
        5. Filtro de búsqueda en la tabla de alumnos
        6. Cerrar modales al hacer clic fuera
    --}}

    document.addEventListener('DOMContentLoaded', function() {
        
        {{-- 1. MODAL AGREGAR CARRERA --}}
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

        {{-- 2. MODAL RESPALDOS --}}
        const btnRespaldos = document.getElementById('btnRespaldos');
        const btnRespaldosAuto = document.getElementById('btnRespaldosAuto');
        const btonCerrarRespaldos = document.getElementById('closeModalRespaldos');
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
        if (btnRespaldosAuto) btnRespaldosAuto.onclick = abrirModalRespaldosAuto;

        {{-- 3. MODAL RESPALDOS AUTOMATIZADOS --}}
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

        //manejar cosas del modal anterior
        const permitirTiemposCheck = document.getElementById('permitirTiemposCheck');

        const tiempoMinutosSigRespaldoLab = document.getElementById('tiempoMinutosSigRespaldoLab');
        const tiempoMinutosSigRespaldo = document.getElementById('tiempoMinutosSigRespaldo');
        const tiempoHorasSigRespaldoLab = document.getElementById('tiempoHorasSigRespaldoLab');
        const tiempoHorasSigRespaldo = document.getElementById('tiempoHorasSigRespaldo');
        const tiempoDiasSigRespaldoLab = document.getElementById('tiempoDiasSigRespaldoLab');
        const tiempoDiasSigRespaldo = document.getElementById('tiempoDiasSigRespaldo');

        permitirTiemposCheck.onclick = function(e) {
            if(e.srcElement.checked) {
                tiempoMinutosSigRespaldoLab.style.display = 'inline';
                tiempoHorasSigRespaldoLab.style.display = 'inline';

                tiempoMinutosSigRespaldo.min = 1;
                tiempoMinutosSigRespaldo.value = 1;
                tiempoDiasSigRespaldo.min = 0;
                tiempoDiasSigRespaldo.value = 0;
            } else {
                tiempoMinutosSigRespaldoLab.style.display = 'none';
                tiempoHorasSigRespaldoLab.style.display = 'none';

                tiempoMinutosSigRespaldo.min = 0;
                tiempoMinutosSigRespaldo.value = 0;
                tiempoHorasSigRespaldo.value = 0;

                if(tiempoDiasSigRespaldo.value == 0) {
                    tiempoDiasSigRespaldo.value = 1;
                }

                tiempoDiasSigRespaldo.min = 1;
            }
        };

        if(permitirTiemposCheck.checked) {
            tiempoMinutosSigRespaldoLab.style.display = 'inline';
            tiempoHorasSigRespaldoLab.style.display = 'inline';
        } else {
            tiempoMinutosSigRespaldoLab.style.display = 'none';
            tiempoHorasSigRespaldoLab.style.display = 'none';
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

        {{-- 6. CERRAR MODALES AL HACER CLIC FUERA --}}
        window.onclick = function(e) {
            if (e.target === modalCarrera) cerrarModalCarrera();
            if (e.target === modalListaGlobal) cerrarModalListaGlobal();
            if (e.target === modalRespaldos) cerrarModalRespaldos();
            if (e.target === modalRespaldosAuto) cerrarModalRespaldosAuto();
        };

        {{-- 5. FILTRO DE BÚSQUEDA EN TABLA --}}
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