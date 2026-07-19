{{-- 
    ============================================================
    MAESTRO - EXPEDIENTE DEL ALUMNO
    ============================================================
    Esta vista muestra el expediente completo de un alumno
    desde la perspectiva del maestro.
    Muestra:
    - Foto de perfil del alumno
    - Datos personales (Nombre, Matrícula, Carrera, Grupo, etc.)
    - Documentos del alumno con estado (subido/no subido/estatico)
    - Calificaciones con filtro por período
    - Sección de tutorías (solo visible si el maestro es tutor)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_maestro.css
    - Botón de regreso: visible (back-button)
    - Se conecta con: maestro.grupos (vista anterior)
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
    En este caso: "Expediente del Alumno - Maestro"
--}}
@section('title', __('messages.expedient_title'))
@section('subtitle', __('messages.expedient_subtitle'))

@section('title', 'Expediente del Alumno - Maestro')
@section('subtitle', 'Consulta la información académica del alumno')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
--}}
@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

{{-- 
    URL DE REGRESO
    Define a dónde redirige el botón de regreso.
    En este caso, a la vista de grupos del maestro.
--}}
@section('back-url', '/dashboard/maestro/grupos')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    
    {{-- 
        CONTENEDOR PRINCIPAL DEL EXPEDIENTE
        Fondo blanco con sombra y bordes redondeados.
    --}}
    <div class="expediente-container">
        
        {{-- ======================================================
             BOTÓN GENERAR PDF
             ====================================================== 
             Permite descargar el expediente en formato PDF.
        --}}
        <div class="pdf-button-container">
            <button class="btn-generar-pdf">
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-pdf">
                {{ __('messages.expedient_generate_pdf') }}
            </button>
        </div>

        {{-- ======================================================
             FOTO DE PERFIL DEL ALUMNO
             ====================================================== 
             Muestra la foto del alumno o sus iniciales.
        --}}
        <div class="perfil-section">
            <div class="avatar-grande">
                @if($alumno->user->foto)
                    <img src="{{ asset('storage/' . $alumno->user->foto) }}" 
                         style="width:100%; height:100%; object-fit:cover;">
                @else
                    <span class="avatar-iniciales-grande">
                        {{ strtoupper(substr($alumno->user->name, 0, 1)) }}{{ strtoupper(substr($alumno->user->apellido, 0, 1)) }}
                    </span>
                @endif
            </div>
        </div>

        {{-- ======================================================
             DATOS PERSONALES DEL ALUMNO
             ====================================================== 
             Grid de 2 columnas con los datos del alumno.
        --}}
        <h3 class="seccion-titulo">{{ __('messages.expedient_personal_data') }}</h3>
        <div class="datos-grid">
            {{-- Nombre --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_name') }}</label>
                <span class="dato-valor">{{ $alumno->user->name }}</span>
            </div>
            
            {{-- Apellidos --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_last_names') }}</label>
                <span class="dato-valor">{{ $alumno->user->apellido }}</span>
            </div>

            {{-- Matrícula --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_id') }}</label>
                <span class="dato-valor">{{ $alumno->matricula }}</span>
            </div>

            {{-- Carrera --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_career') }}</label>
                <span class="dato-valor">{{ $carrera->nombre ?? 'N/A' }}</span>
            </div>

            {{-- Grupo --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_group') }}</label>
                <span class="dato-valor">{{ $grupo->nombre }}</span>
            </div>

            {{-- CURP --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_curp') }}</label>
                <span class="dato-valor">{{ $alumno->curp }}</span>
            </div>

            {{-- Edad --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_age') }}</label>
                <span class="dato-valor">{{ $alumno->edad }} {{ __('messages.profile_years') }}</span>
            </div>

            {{-- Sexo --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_gender') }}</label>
                <span class="dato-valor">{{ $alumno->sexo }}</span>
            </div>

            {{-- Fecha de nacimiento --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_birth_date') }}</label>
                <span class="dato-valor">{{ $alumno->fecha_nacimiento }}</span>
            </div>

            {{-- Correo electrónico --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_email') }}</label>
                <span class="dato-valor">{{ $alumno->user->email }}</span>
            </div>
        </div>

        {{-- ======================================================
            SECCIÓN DE DOCUMENTOS (ESTÁTICA)
            ====================================================== 
            Esta sección está fija para mostrar el diseño visual.
            El backend decidirá cómo implementar la lógica real.
        ====================================================== --}}

        <div class="documentos-container">
            <h3 class="seccion-titulo documentos-titulo">{{ __('messages.expedient_documents') }}</h3>
            
            {{-- Documento 1: Acta de nacimiento (subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Acta de nacimiento</span>
                    <span class="documento-estado subido">Documento subido</span>
                </div>
                <button class="btn-ver-documento" onclick="window.location.href='#'">
                    <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                    Ver documento
                </button>
            </div>

            {{-- Documento 2: CURP (no subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">CURP</span>
                    <span class="documento-estado no-subido">No subido</span>
                </div>
                <span class="estado-sin-boton">—</span>
            </div>

            {{-- Documento 3: Certificado de bachillerato (no subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Certificado de bachillerato</span>
                    <span class="documento-estado no-subido">No subido</span>
                </div>
                <span class="estado-sin-boton">—</span>
            </div>

            {{-- Documento 4: Constancia de estudios (subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Constancia de estudios</span>
                    <span class="documento-estado subido">Documento subido</span>
                </div>
                <button class="btn-ver-documento" onclick="window.location.href='#'">
                    <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                    Ver documento
                </button>
            </div>
        </div>

        {{-- ======================================================
             SECCIÓN DE CALIFICACIONES
             ====================================================== 
             Muestra las calificaciones del alumno.
             Incluye un filtro por período.
        --}}
        <h3 class="seccion-titulo">{{ __('messages.expedient_grades') }}</h3>
        
        {{-- Filtro de período --}}
        <div class="filtro-periodo-expediente">
            <div class="periodo-select-expediente">
                <label>{{ __('messages.expedient_period') }}:</label>
                <select id="periodoSelect">
                    <option value="">{{ __('messages.expedient_select_period') }}</option>
                    {{-- Los períodos se cargarán dinámicamente desde el backend --}}
                </select>
            </div>
        </div>

        {{-- Tabla de calificaciones --}}
        <div class="tabla-container">
            <table class="tabla-calificaciones" id="tablaCalificaciones">
                <thead>
                    <tr>
                        <th>{{ __('messages.expedient_subject') }}</th>
                        <th>{{ __('messages.expedient_grade') }}</th>
                    </tr>
                </thead>
                <tbody id="calificacionesBody">
                    @for($i = 0; $i < 5; $i++)
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Separador visual entre secciones --}}
        <div class="separador-secciones"></div>

        {{-- ======================================================
             SECCIÓN DE TUTORÍAS
             ====================================================== 
             Solo visible para maestros que son TUTOR.
             El backend debe mostrar esta sección solo si $esTutor = true
        --}}
        <div class="tutorias-header">
            <h3 class="seccion-titulo tutorias-titulo">{{ __('messages.expedient_tutorias') }}</h3>
            <button class="btn-agregar-tutoria" id="btnAgregarTutoria">
                {{ __('messages.expedient_add_tutoria') }}
            </button>
        </div>
        
        <div class="tabla-container">
            <table class="tabla-tutorias">
                <thead>
                    <tr>
                        <th>{{ __('messages.expedient_date') }}</th>
                        <th>{{ __('messages.expedient_topic') }}</th>
                        <th>{{ __('messages.expedient_notes') }}</th>
                        <th>{{ __('messages.expedient_actions') }}</th>
                    </tr>
                </thead>
                <tbody id="tutoriasBody">
                    @for($i = 0; $i < 2; $i++)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <button class="btn-editar-tutoria">{{ __('messages.expedient_edit') }}</button>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        {{-- FIN SECCIÓN TUTORÍAS --}}

    </div>

    {{-- ======================================================
         MODAL PARA AGREGAR/EDITAR TUTORÍA
         ====================================================== --}}
    <div id="modalTutoria" class="modal modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitulo">{{ __('messages.modal_add_tutoria') }}</h3>
                <span class="modal-close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('messages.expedient_date') }}</label>
                    <input type="date" id="fechaTutoria">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.expedient_topic') }}</label>
                    <input type="text" id="temaTutoria" placeholder="Ej: Revisión de calificaciones">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.expedient_notes') }}</label>
                    <textarea id="notasTutoria" rows="3" placeholder="{{ __('messages.modal_notes_placeholder') }}"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarModal">{{ __('messages.modal_cancel') }}</button>
                <button class="btn-guardar" id="guardarTutoria">{{ __('messages.modal_save') }}</button>
            </div>
        </div>
    </div>
@endsection

{{-- SCRIPTS ADICIONALES --}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        1. Filtro de período para calificaciones
        2. Modal para agregar/editar tutorías
        3. Botones de edición en filas de tutorías
    --}}

    document.addEventListener('DOMContentLoaded', function() {
        
        {{-- 1. FILTRO DE PERÍODO PARA CALIFICACIONES --}}
        const periodoSelect = document.getElementById('periodoSelect');
        const calificacionesBody = document.getElementById('calificacionesBody');
        
        if (periodoSelect) {
            periodoSelect.addEventListener('change', function() {
                const periodo = this.value;
                if (periodo) {
                    {{-- Aquí el backend cargará las calificaciones del período seleccionado --}}
                    calificacionesBody.innerHTML = '';
                    for (let i = 0; i < 5; i++) {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td></td><td class="calificacion"></td>`;
                        calificacionesBody.appendChild(row);
                    }
                } else {
                    {{-- Mostrar 5 filas vacías --}}
                    calificacionesBody.innerHTML = '';
                    for (let i = 0; i < 5; i++) {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td></td><td class="calificacion"></td>`;
                        calificacionesBody.appendChild(row);
                    }
                }
            });
        }

        {{-- 2. MODAL DE TUTORÍAS --}}
        const modal = document.getElementById('modalTutoria');
        const btnAgregar = document.getElementById('btnAgregarTutoria');
        const closeModal = document.getElementById('closeModal');
        const cancelarModal = document.getElementById('cancelarModal');
        const modalTitulo = document.getElementById('modalTitulo');

        {{-- Abrir modal para agregar --}}
        if (btnAgregar) {
            btnAgregar.addEventListener('click', function() {
                modalTitulo.textContent = '{{ __('messages.modal_add_tutoria') }}';
                document.getElementById('fechaTutoria').value = '';
                document.getElementById('temaTutoria').value = '';
                document.getElementById('notasTutoria').value = '';
                modal.style.display = 'flex';
            });
        }

        {{-- Cerrar modal --}}
        function cerrarModal() {
            modal.style.display = 'none';
        }

        if (closeModal) closeModal.addEventListener('click', cerrarModal);
        if (cancelarModal) cancelarModal.addEventListener('click', cerrarModal);

        {{-- Cerrar modal al hacer clic fuera --}}
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                cerrarModal();
            }
        });

        {{-- Guardar tutoría --}}
        const guardarBtn = document.getElementById('guardarTutoria');
        if (guardarBtn) {
            guardarBtn.addEventListener('click', function() {
                const fecha = document.getElementById('fechaTutoria').value;
                const tema = document.getElementById('temaTutoria').value;
                
                if (fecha && tema) {
                    alert('{{ __('messages.modal_success') }}');
                    cerrarModal();
                } else {
                    alert('{{ __('messages.modal_error_empty') }}');
                }
            });
        }

        {{-- 3. BOTONES DE EDICIÓN EN FILAS DE TUTORÍAS --}}
        document.querySelectorAll('.btn-editar-tutoria').forEach(btn => {
            btn.addEventListener('click', function() {
                modalTitulo.textContent = '{{ __('messages.modal_edit_tutoria') }}';
                const row = this.closest('tr');
                const fecha = row.cells[0].textContent;
                const tema = row.cells[1].textContent;
                const notas = row.cells[2].textContent;
                
                {{-- Convertir fecha al formato del input date --}}
                if (fecha && fecha.includes('/')) {
                    const partes = fecha.split('/');
                    document.getElementById('fechaTutoria').value = `${partes[2]}-${partes[1]}-${partes[0]}`;
                } else {
                    document.getElementById('fechaTutoria').value = '';
                }
                document.getElementById('temaTutoria').value = tema;
                document.getElementById('notasTutoria').value = notas;
                modal.style.display = 'flex';
            });
        });
    });
</script>
@endpush