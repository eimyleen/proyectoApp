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
        
        {{-- 
            ======================================================
            NOTA: CAMBIO REALIZADO - GENERACIÓN DE PDF
            ======================================================
            Se agregó ID "btnGenerarPDF" al botón y una alerta de
            confirmación con SweetAlert.
            
            Originalmente solo redirigía a '#'.
            Ahora pregunta antes de mostrar el mensaje informativo.
            ======================================================
        --}}
        <div class="pdf-button-container">
            <button class="btn-generar-pdf" id="btnGenerarPDF">
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
                <span class="dato-valor">{{ $alumno->sexo_texto }}</span>
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

            {{-- Telefono --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_phone') }}</label>
                <span class="dato-valor">{{ $alumno->telefono ?? __('messages.not_assigned') }}</span>
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
            
            {{-- Documento 1: Acta de nacimiento --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">{{ __('messages.document_birth_act') }}</span>
                    @if($alumno->doc_acta_nacimiento)
                        <span class="documento-estado subido">{{ __('messages.document_sent') }}</span>
                    @else
                        <span class="documento-estado no-subido">{{ __('messages.document_not_sent') }}</span>
                    @endif
                </div>
                @if($alumno->doc_acta_nacimiento)
                    <button class="btn-ver-documento" onclick="window.location.href='{{ asset('storage/' . $alumno->doc_acta_nacimiento) }}'">
                        <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                        {{ __('messages.view_document') }}
                    </button>
                @else
                    <span class="estado-sin-boton">—</span>
                @endif
            </div>

            {{-- Documento 2: CURP --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">{{ __('messages.document_curp') }}</span>
                    @if($alumno->doc_curp)
                        <span class="documento-estado subido">{{ __('messages.document_sent') }}</span>
                    @else
                        <span class="documento-estado no-subido">{{ __('messages.document_not_sent') }}</span>
                    @endif
                </div>
                @if($alumno->doc_curp)
                    <button class="btn-ver-documento" onclick="window.location.href='{{ asset('storage/' . $alumno->doc_curp) }}'">
                        <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                        {{ __('messages.view_document') }}
                    </button>
                @else
                    <span class="estado-sin-boton">—</span>
                @endif
            </div>

            {{-- Documento 3: Certificado de bachillerato --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">{{ __('messages.document_institute_certificate') }}</span>
                    @if($alumno->doc_certificado_bachillerato)
                        <span class="documento-estado subido">{{ __('messages.document_sent') }}</span>
                    @else
                        <span class="documento-estado no-subido">{{ __('messages.document_not_sent') }}</span>
                    @endif
                </div>
                @if($alumno->doc_certificado_bachillerato)
                    <button class="btn-ver-documento" onclick="window.location.href='{{ asset('storage/' . $alumno->doc_certificado_bachillerato) }}'">
                        <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                        {{ __('messages.view_document') }}
                    </button>
                @else
                    <span class="estado-sin-boton">—</span>
                @endif
            </div>

            {{-- Documento 4: Constancia de estudios --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">{{ __('messages.document_study_constancy') }}</span>
                    @if($alumno->doc_constancia_estudios)
                        <span class="documento-estado subido">{{ __('messages.document_sent') }}</span>
                    @else
                        <span class="documento-estado no-subido">{{ __('messages.document_not_sent') }}</span>
                    @endif
                </div>
                @if($alumno->doc_constancia_estudios)
                    <button class="btn-ver-documento" onclick="window.location.href='{{ asset('storage/' . $alumno->doc_constancia_estudios) }}'">
                        <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                        {{ __('messages.view_document') }}
                    </button>
                @else
                    <span class="estado-sin-boton">—</span>
                @endif
            </div>
        </div>

        {{-- ======================================================
             SECCIÓN DE CALIFICACIONES
             ====================================================== 
             Muestra las calificaciones del alumno.
             Incluye un filtro por período.
        --}}
        <h3 class="seccion-titulo">{{ __('messages.expedient_grades') }}</h3>
        
        {{-- 
            Filtro de período
            ======================================================
            NOTA: CAMBIO REALIZADO - ESTRUCTURA DEL FILTRO
            ======================================================
            Se movió el <form> para que envuelva el div en lugar de
            estar dentro, igual que en la vista de calificaciones
            del alumno. Esto corrige el desalineamiento de los
            elementos (label, select, botón).
            ======================================================
        --}}
        <div class="filtro-periodo-expediente">
            <button id="btnAnadirEditarCalificacion" class="btn-agregar">Añadir Calificación</button>
            <form action="{{ route('maestro.alumno.expediente', $alumno->id) }}" method="get">
                <div class="periodo-select-expediente">
                    <label for="periodoSelect">{{ __('messages.expedient_period') }}:</label>
                    <select name="periodo" id="periodoSelect" onchange="this.form.submit()">
                        <option value="">{{ __('messages.expedient_select_period') }}</option>
                        @foreach($periodos as $periodo)
                            <option value="{{ $periodo }}" {{ $periodoSeleccionado == $periodo ? 'selected' : '' }}>
                                {{ $periodo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        {{-- Tabla de calificaciones --}}
        <div class="tabla-container">
            <table class="tabla-calificaciones" id="tablaCalificaciones">
                <thead>
                    <tr>
                        <th rowspan="2">{{ __('messages.expedient_subject') }}</th>
                        <th colspan="4">{{ __('messages.first_period') }}</th>
                        <th colspan="4">{{ __('messages.second_period') }}</th>
                        <th rowspan="2">{{ __('messages.final_grade') }}</th>
                        <th rowspan="2">{{ __('messages.column_actions') }}</th>
                    </tr>
                    <tr>
                        <th>{{ __('messages.first_ordinal_grade') }}</th><th>{{ __('messages.first_remedial_grade') }}</th><th>{{ __('messages.first_extraordinary_grade') }}</th><th>{{ __('messages.first_final_grade') }}</th>
                        <th>{{ __('messages.second_ordinal_grade') }}</th><th>{{ __('messages.second_remedial_grade') }}</th><th>{{ __('messages.second_extraordinary_grade') }}</th><th>{{ __('messages.second_final_grade') }}</th>
                    </tr>
                </thead>
                <tbody id="calificacionesBody">
                    @if ($periodoSeleccionado)
                        @foreach($calificacionesCalculadas as $cal)
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
                                <td class="calificacion btn-editar-materia">
                                    <button class="" 
                                    data-materia-id="{{ $cal->materia->id }}"
                                    data-materia-nombre="{{ $cal->materia->nombre }}"
                                    data-parciales='@json($cal->parciales ?? '')'
                                    >Editar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="9" style="text-align: right;"><strong>{{ __('messages.period_average') . ':' }}</strong></td>
                            <td class="calificacion {{ $promedioPeriodo >= 8 ? 'aprobado' : 'reprobado' }}">
                                {{ number_format($promedioPeriodo, 1) }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="11" style="text-align: center;">{{ __('messages.table_empty_grades') }}</td>
                        </tr>
                    @endif
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
        {{-- FIN SECCIÓN TUTORÍAS --}}

    </div>

    {{-- ======================================================
         MODAL PARA AGREGAR/EDITAR TUTORÍA
    ====================================================== --}}
    <div id="modalTutoria">
        <div class="modal-small modal-content">
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

    {{-- ======================================================
         MODAL PARA AGREGAR/EDITAR CALIFICACIONES
         ====================================================== --}}
    <div id="modalCalificaciones" class="modal modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitulo">{{ __('messages.modal_add_edit_notes') }}</h3>
                <span class="modal-close" id="cerrarModalCalificaciones">&times;</span>
            </div>
            <form action="{{ route('maestro.show.guardarCalificacion', $alumno) }}" id="formAladirEditarCalificacion" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Periodo</label>
                        <select name="periodo" required>
                            <option value="">Selecciona un Periodo</option>
                            @foreach ($periodos as $periodo)
                                <option value="{{ $periodo }}">{{ $periodo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Materia</label>
                        <select name="materia" id="selectMateriaCalificacion" required>
                            <option value="">Selecciona una Materia</option>
                            @foreach ($materias as $materia)
                                <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Parcial</label>
                        <select name="parcial" id="selectParcialCalificacion" required>
                            <option value="">Selecciona un Parcial</option>
                            <option value="1">Primer Parcial</option>
                            <option value="2">Segundo Parcial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Evaluación</label>
                        <select name="evaluacion" id="selectEvaluacionCalificacion" required>
                            <option value="">Selecciona una Evaluación</option>
                            <option value="ordinario">Ordinario</option>
                            <option value="remedial">Remedial</option>
                            <option value="extraordinario">Extraordinario</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Calificación</label>
                        <input type="number" name="calificacion" min="0.0" max="10.0" value="0.0" step="0.1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar" id="formGuardarCalificacion">Añadir Calificación</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalCalificacionesEditar" class="modal small">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitulo">Editar Calificación</h3>
                <span class="modal-close" id="cerrarModalCalificacionesEditar">&times;</span>
            </div>
            <form action="{{ route('maestro.show.editarCalificacion', $alumno) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group" id="">
                        <label for="">Periodo</label>
                        <input type="text" name="periodo" value="{{ $periodoSeleccionado }}" id="formEditPeriodoInput" readonly required>
                    </div>
                    <div class="form-group" id="">
                        <label for="">Materia</label>
                        <input type="text" name="materia" value="" id="formEditMateriaInput" readonly required>
                    </div>
                    <div class="form-group" id="">
                        <label for="">Parciales</label>
                        <select name="parcial" id="formEditParcialesSelect" required>
                            <option value="">Selecciona un Parcial</option>
                        </select>
                    </div>
                    <div class="form-group" id="evaluacionEditarDiv">
                        <label for="">Evaluaciones Disponibles</label>
                        <select name="evaluacion" id="formEditEvaluacionesSelect" required>
                            <option value="">Selecciona una Evaluación</option>
                        </select>
                    </div>
                    <div class="form-group" id="calificacionEditarDiv">
                        <label for="">Calificación</label>
                        <input type="number" name="calificacion" min="0.0" max="10.0" value="0.0" step="0.1" id="formEditCalificacionInput" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar" id="formEditarCalificacion">Cambiar Calificación</button>
                </div>
            </form>
        </div>
    </div>
@endsection

{{-- 
    ======================================================
    SCRIPTS ADICIONALES
    ======================================================
    NOTA: FUNCIONALIDADES AGREGADAS
    ======================================================
    1. Confirmación antes de generar PDF del expediente
    2. Confirmación antes de guardar tutoría
    ======================================================
--}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        1. Filtro de período para calificaciones
        2. Modal para agregar/editar tutorías
        3. Botones de edición en filas de tutorías
        4. Confirmación antes de generar PDF del expediente (NUEVO)
        5. Confirmación antes de guardar tutoría (NUEVO)
        6. Validación de campos vacíos en modal (NUEVO)
    --}}

    document.addEventListener('DOMContentLoaded', function() {
        
        // ==============================================
        // 1. FILTRO DE PERÍODO PARA CALIFICACIONES
        // ==============================================
        const periodoSelect = document.getElementById('periodoSelect');
        const calificacionesBody = document.getElementById('calificacionesBody');
        
        if (periodoSelect) {
            periodoSelect.addEventListener('change', function() {
                const periodo = this.value;
                if (periodo) {
                    // Aquí el backend cargará las calificaciones del período seleccionado
                    calificacionesBody.innerHTML = '';
                    for (let i = 0; i < 5; i++) {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td></td><td class="calificacion"></td>`;
                        calificacionesBody.appendChild(row);
                    }
                } else {
                    // Mostrar 5 filas vacías
                    calificacionesBody.innerHTML = '';
                    for (let i = 0; i < 5; i++) {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td></td><td class="calificacion"></td>`;
                        calificacionesBody.appendChild(row);
                    }
                }
            });
        }

        // ==============================================
        // 2. MODAL DE TUTORÍAS
        // ==============================================
        const modal = document.getElementById('modalTutoria');
        const btnAgregar = document.getElementById('btnAgregarTutoria');
        const closeModal = document.getElementById('closeModal');
        const cancelarModal = document.getElementById('cancelarModal');
        const modalTitulo = document.getElementById('modalTitulo');

        // Abrir modal para agregar
        if (btnAgregar) {
            btnAgregar.addEventListener('click', function() {
                modalTitulo.textContent = '{{ __('messages.modal_add_tutoria') }}';
                document.getElementById('fechaTutoria').value = '';
                document.getElementById('temaTutoria').value = '';
                document.getElementById('notasTutoria').value = '';
                modal.style.display = 'flex';
            });
        }

        // Cerrar modal
        function cerrarModal() {
            modal.style.display = 'none';
        }

        if (closeModal) closeModal.addEventListener('click', cerrarModal);
        if (cancelarModal) cancelarModal.addEventListener('click', cerrarModal);

        // ==============================================
        // 3. BOTONES DE EDICIÓN EN FILAS DE TUTORÍAS
        // ==============================================
        document.querySelectorAll('.btn-editar-tutoria').forEach(btn => {
            btn.addEventListener('click', function() {
                modalTitulo.textContent = '{{ __('messages.modal_edit_tutoria') }}';
                const row = this.closest('tr');
                const fecha = row.cells[0].textContent;
                const tema = row.cells[1].textContent;
                const notas = row.cells[2].textContent;
                
                // Convertir fecha al formato del input date
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

        // ==============================================
        // 4. MODAL DE CALIFICACIONES AGREGAR
        // ==============================================
        const modalCalificaciones = document.getElementById('modalCalificaciones');
        const btnAnadirEditarCalificacion = document.getElementById('btnAnadirEditarCalificacion');
        const cerrarModalCalificaciones = document.getElementById('cerrarModalCalificaciones');
        const formCalificacion = document.getElementById('formAladirEditarCalificacion');

        // ⭐ NUEVO: Validación de campos antes de enviar
        if (formCalificacion) {
            formCalificacion.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const periodo = this.querySelector('[name="periodo"]').value;
                const materia = this.querySelector('[name="materia"]').value;
                const parcial = this.querySelector('[name="parcial"]').value;
                const evaluacion = this.querySelector('[name="evaluacion"]').value;
                const calificacion = this.querySelector('[name="calificacion"]').value.trim();
                
                if (!periodo || !materia || !parcial || !evaluacion || !calificacion) {
                    alertaInfo(
                        'Campos incompletos',
                        'Debes completar todos los campos obligatorios del formulario.'
                    );
                    return;
                }
                
                // Si todos los campos están completos, enviar el formulario
                this.submit();
            });
        }

        //form modal cal agregar
        const formRestoCalififacionesDiv = document.getElementById('formRestoCalififacionesDiv');
        const selectMateriaCalificacion = document.getElementById('selectMateriaCalificacion');

        // Abrir modal para agregar
        if (btnAnadirEditarCalificacion) {
            btnAnadirEditarCalificacion.addEventListener('click', function() {
                modalCalificaciones.style.display = 'flex';
            });
        }

        // Cerrar modal
        function cerrarModalCalific() {
            modalCalificaciones.style.display = 'none';
        }
        if (cerrarModalCalificaciones) cerrarModalCalificaciones.addEventListener('click', cerrarModalCalific);

        // ==============================================
        // 5. MODAL DE CALIFICACIONES EDITAR
        // ==============================================
        const modalCalificacionesEditar = document.getElementById('modalCalificacionesEditar');
        const cerrarModalCalificacionesEditar = document.getElementById('cerrarModalCalificacionesEditar');

        const formEditMateriaInput = document.getElementById('formEditMateriaInput');
        const formEditParcialesSelect = document.getElementById('formEditParcialesSelect');
        const formEditEvaluacionesSelect = document.getElementById('formEditEvaluacionesSelect');
        const formEditCalificacionInput = document.getElementById('formEditCalificacionInput');

        //botones de editar
        document.querySelectorAll('.btn-editar-materia').forEach(btn => {
            btn.addEventListener('click', function() {
                let btnSrc = btn.children[0];
                let dataParciales = JSON.parse(btnSrc.dataset.parciales);
                let dataMateriaId = btnSrc.dataset.materiaId;
                let dataMateriaNombre = btnSrc.dataset.materiaNombre;

                const evaluacionesMap = {
                    co: 'Ordinario',
                    cr: 'Remedial',
                    ce: 'Extraordinario'
                };

                let evaluaciones;

                formEditMateriaInput.value = dataMateriaId;
                formEditMateriaInput.textContent = btnSrc.dataset.materiaNombre;
                formEditMateriaInput.readonly = true;

                formEditParcialesSelect.innerHTML = '';

                Object.keys(dataParciales).forEach(parcial => {
                    evaluaciones = dataParciales[parcial];

                    const tieneAlgo = Object.values(evaluaciones).some(v => v !== null);

                    if (tieneAlgo) {
                        formEditParcialesSelect.innerHTML += `
                            <option value="${parcial}">Parcial ${parcial}</option>
                        `;
                    }
                });

                formEditEvaluacionesSelect.innerHTML = '';

                
                Object.keys(evaluaciones).forEach(key => {
                    if (evaluaciones[key] !== null && key !== 'cf') {
                        formEditEvaluacionesSelect.innerHTML += `
                            <option 
                                value="${evaluacionesMap[key].toLowerCase()}" 
                                data-key="${key}">
                                ${evaluacionesMap[key]}
                            </option>
                        `;
                    }
                });

                function actualizarEvaluaciones() {
                    const parcial = formEditParcialesSelect.value;
                    const evaluaciones = dataParciales[parcial];

                    formEditEvaluacionesSelect.innerHTML = '';

                    Object.keys(evaluaciones).forEach(key => {
                        if (evaluaciones[key] !== null && key !== 'cf') {
                            formEditEvaluacionesSelect.innerHTML += `
                                <option 
                                    value="${evaluacionesMap[key].toLowerCase()}" 
                                    data-key="${key}">
                                    ${evaluacionesMap[key]}
                                </option>
                            `;
                        }
                    });
                }

                function actualizarCalificacion() {
                    const parcial = formEditParcialesSelect.value;

                    const selectedOption = formEditEvaluacionesSelect.selectedOptions[0];
                    const key = selectedOption.dataset.key;

                    const valor = dataParciales[parcial][key];

                    formEditCalificacionInput.value = valor ?? '';
                }

                formEditParcialesSelect.addEventListener('change', () => {
                    actualizarEvaluaciones();
                    actualizarCalificacion();
                });
                formEditEvaluacionesSelect.addEventListener('change', actualizarCalificacion);
                
                actualizarEvaluaciones();
                actualizarCalificacion();

                modalCalificacionesEditar.style.display = 'flex';
            });
        });

        // Cerrar modal
        function cerrarModalCalificEditar() {
            modalCalificacionesEditar.style.display = 'none';
        }

        if (cerrarModalCalificacionesEditar) cerrarModalCalificacionesEditar.addEventListener('click', cerrarModalCalificEditar);

        // Cerrar modal al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                cerrarModal();
            } else if(event.target == modalCalificaciones) {
                cerrarModalCalific();
            } else if(event.target == modalCalificacionesEditar) {
                cerrarModalCalificEditar();
            }
        });

        // ==============================================
        // 6. CONFIRMAR GENERACIÓN DE PDF DEL EXPEDIENTE
        // ==============================================
        // Originalmente redirigía a '#' sin confirmación.
        // Ahora muestra una alerta de confirmación antes de mostrar el mensaje.
        const btnGenerarPDF = document.getElementById('btnGenerarPDF');
        if (btnGenerarPDF) {
            btnGenerarPDF.addEventListener('click', function(e) {
                e.preventDefault();
                
                confirmarAccion(
                    'Generar PDF del expediente',
                    'Se generará un archivo PDF con el expediente completo del alumno. ¿Deseas continuar?',
                    'Generar PDF',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        // NOTA: La descarga real se integrará cuando la ruta esté definida
                        alertaInfo(
                            'Descarga de PDF',
                            'La funcionalidad de descarga del expediente se integrará próximamente.'
                        );
                    }
                });
            });
        }

        // ==============================================
        // 6. CONFIRMAR GUARDAR TUTORÍA
        // ==============================================
        // Originalmente usaba alert() para éxito y error.
        // Ahora usa SweetAlert para confirmar el guardado.
        const guardarBtn = document.getElementById('guardarTutoria');
        if (guardarBtn) {
            guardarBtn.addEventListener('click', function() {
                const fecha = document.getElementById('fechaTutoria').value;
                const tema = document.getElementById('temaTutoria').value;
                const notas = document.getElementById('notasTutoria').value;
                
                if (fecha && tema) {
                    confirmarAccion(
                        'Guardar tutoría',
                        '¿Estás seguro de que quieres guardar esta tutoría?',
                        'Guardar',
                        'Cancelar'
                    ).then((result) => {
                        if (result.isConfirmed) {
                            // Aquí iría la lógica de guardado real
                            alertaExito(
                                'Tutoría guardada',
                                'La tutoría se ha registrado correctamente.'
                            );
                            cerrarModal();
                        }
                    });
                } else {
                    alertaInfo(
                        'Campos incompletos',
                        'Debes completar la fecha y el tema de la tutoría.'
                    );
                }
            });
        }
    });
</script>
@endpush