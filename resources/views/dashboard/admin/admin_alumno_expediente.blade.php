{{-- 
    ============================================================
    ADMIN - EXPEDIENTE DEL ALUMNO
    ============================================================
    Esta vista muestra el expediente completo de un alumno
    desde la perspectiva del administrador.
    Muestra:
    - Foto de perfil del alumno
    - Datos personales (Nombre, Matrícula, Carrera, Grupo, etc.)
    - Logo de la carrera
    - Sección de tutorías
    - Modal para editar alumno
    - Documentos del alumno con estado (subido/no subido)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_admin.css
    - Botón de regreso: visible (back-button)
    - Se conecta con: admin.carrera (detalle de carrera)
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
--}}
@section('title', __('messages.student_record_admin_detail_title'))
@section('subtitle', __('messages.student_record_admin_detail_subtitle'))

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
--}}
@section('back-button')
    <!-- Botón de regresar -->
@endsection

{{-- 
    URL DE REGRESO
    Define a dónde redirige el botón de regreso.
    En este caso, al detalle de la carrera del alumno.
--}}
@section('back-url', '/dashboard/admin')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    
    {{-- 
        CONTENEDOR PRINCIPAL DEL EXPEDIENTE
        Fondo blanco con sombra y bordes redondeados.
    --}}
    <div class="expediente-container admin-expediente">
        
        {{-- 
            ======================================================
            NOTA: CAMBIO REALIZADO - GENERAR PDF
            ======================================================
            Se agregó ID "btnGenerarPDF" y una alerta de
            confirmación con SweetAlert antes de ejecutar la acción.
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
            <div class="foto-perfil">
                <div class="avatar-grande">
                    @if($alumno->user->foto)
                        <img src="{{ asset('storage/' . $alumno->user->foto) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <span class="avatar-iniciales-grande">
                            {{ strtoupper(substr($alumno->user->name, 0, 1)) }}{{ strtoupper(substr($alumno->user->apellido, 0, 1)) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ======================================================
             DATOS PERSONALES DEL ALUMNO
             ====================================================== 
             Grid de 2 columnas con los datos del alumno.
        --}}
        <h3 class="seccion-titulo">{{ __('messages.expedient_personal_data') }}</h3>
        <div class="datos-container">
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
                    <span class="dato-valor">{{ $grupo->nombre ?? 'N/A' }}</span>
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

                {{-- Teléfono --}}
                <div class="dato-item">
                    <label>{{ __('messages.expedient_phone') }}</label>
                    <span class="dato-valor">{{ $alumno->telefono ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- ======================================================
             LOGO DE LA CARRERA
             ====================================================== 
             Muestra el logo circular de la carrera del alumno.
        --}}
        <div class="carrera-info-expediente">
            <div class="logo-circular-carrera">
                @if($carrera && $carrera->logo)
                    <img src="{{ asset($carrera->logo) }}" alt="Logo">
                @else
                    <img src="{{ asset('img/jaguar.png') }}" alt="Sin logo">
                @endif
            </div>
        </div>

        {{-- 
            ======================================================
            NOTA: CAMBIO REALIZADO - TUTORÍAS Y DOCUMENTOS
            ======================================================
            Se agregaron IDs a los botones necesarios para manejar
            las acciones desde JavaScript.
            
            También se agregaron alertas de confirmación con
            SweetAlert para guardar y eliminar tutorías, además
            de validación de campos obligatorios.
            ======================================================
        --}}
        <h3 class="seccion-titulo">Tutorías</h3>
        <div class="tutorias-header">
            <button class="btn-agregar-tutoria" id="btnAgregarTutoria">+ Agregar tutoría</button>
        </div>
        <div class="tabla-container">
            <table class="tabla-tutorias">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tema</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tutoriasBody">
                    @for($i = 0; $i < 2; $i++)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="col-acciones">
                                <button class="btn-icono-tabla btn-editar-tutoria" data-fila="{{ $i }}" title="Editar tutoría">
                                    <img src="{{ asset('img/editar.png') }}" alt="Editar">
                                </button>
                                <button class="btn-icono-tabla btn-eliminar-tutoria" data-fila="{{ $i }}" title="Eliminar tutoría">
                                    <img src="{{ asset('img/borrar.svg') }}" alt="Eliminar">
                                </button>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- ======================================================
             MODAL - EDITAR ALUMNO
             ====================================================== 
             Modal para editar los datos del alumno.
             
             NOTA: Se mantiene la estructura original.
             ======================================================
        --}}
        <div id="modalEditarAlumno" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Editar alumno</h3>
                    <span class="modal-close" id="closeModalEditar">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input type="text" id="editNombre">
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" id="editApellidos">
                    </div>
                    <div class="form-group">
                        <label>Matrícula</label>
                        <input type="text" id="editMatricula">
                    </div>
                    <div class="form-group">
                        <label>Carrera</label>
                        <input type="text" id="editCarrera">
                    </div>
                    <div class="form-group">
                        <label>Grupo</label>
                        <input type="text" id="editGrupo">
                    </div>
                    <div class="form-group">
                        <label>CURP</label>
                        <input type="text" id="editCURP">
                    </div>
                    <div class="form-group">
                        <label>Edad</label>
                        <input type="text" id="editEdad">
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select id="editSexo">
                            <option value="">Seleccionar</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha de nacimiento</label>
                        <input type="date" id="editFechaNac">
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input type="email" id="editCorreo">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" id="editTelefono">
                    </div>
                    <div class="form-group">
                        <label>Foto de perfil</label>
                        <input type="file" id="editFoto" accept="image/*">
                        <small class="form-text">Selecciona una nueva imagen para la foto</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancelar" id="cancelarEditar">{{ __('messages.btn_cancel') }}</button>
                    <button class="btn-guardar" id="guardarEditar">{{ __('messages.btn_save') }}</button>
                </div>
            </div>
        </div>

        {{-- ======================================================
             MODAL - AGREGAR/EDITAR TUTORÍA
             ====================================================== 
             Modal para agregar o editar una tutoría.
             - Se abre con el botón "+ Agregar tutoría"
             - Se abre con el botón "Editar" en cada fila
             - Los campos son: Fecha, Tema, Notas
             
             NOTA: Se mantiene la estructura original sin <form>.
             ======================================================
        --}}
        <div id="modalTutoria" class="modal modal-small">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalTitulo">Agregar tutoría</h3>
                    <span class="modal-close" id="closeModal">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" id="fechaTutoria">
                    </div>
                    <div class="form-group">
                        <label>Tema</label>
                        <input type="text" id="temaTutoria" placeholder="Ej: Revisión de calificaciones">
                    </div>
                    <div class="form-group">
                        <label>Notas</label>
                        <textarea id="notasTutoria" rows="3" placeholder="Escribe las notas de la tutoría..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancelar" id="cancelarModal">Cancelar</button>
                    <button class="btn-guardar" id="guardarTutoria">Guardar</button>
                </div>
            </div>
        </div>

        {{-- ======================================================
             SECCIÓN DE DOCUMENTOS DEL ALUMNO
             ====================================================== 
             Muestra los documentos del alumno con estado.
             
             NOTA: Se mantiene la estructura original.
             ======================================================
        --}}
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
@endsection

{{-- 
    ======================================================
    SCRIPTS ADICIONALES
    ======================================================
    NOTA: FUNCIONALIDADES AGREGADAS
    ======================================================
    1. Confirmación antes de generar PDF.
    2. Confirmación antes de guardar tutoría + validación.
    3. Confirmación antes de eliminar tutoría.
    4. Visualización de documentos con SweetAlert.
    
    El guardado y eliminación real permanecen a cargo del backend.
    ======================================================
--}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        1. Generar PDF (NUEVO)
        2. Modal de tutorías (agregar/editar)
        3. Guardar tutoría (NUEVO)
        4. Editar tutoría
        5. Eliminar tutoría (NUEVO)
        6. Visualizar documentos (NUEVO)
    --}}

    document.addEventListener('DOMContentLoaded', function() {
        
        // ==============================================
        // 1. CONFIRMACIÓN DE GENERAR PDF (NUEVO)
        // ==============================================
        const btnGenerarPDF = document.getElementById('btnGenerarPDF');
        if (btnGenerarPDF) {
            btnGenerarPDF.addEventListener('click', function(e) {
                e.preventDefault();
                confirmarAccion(
                    'Generar PDF del expediente',
                    '¿Deseas continuar con esta acción?'
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Funcionalidad pendiente',
                            'La generación del PDF se implementará posteriormente.'
                        );
                    }
                });
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
                modalTitulo.textContent = 'Agregar tutoría';
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

        // Cerrar modal al hacer clic fuera
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                cerrarModal();
            }
        });

        // ==============================================
        // 3. CONFIRMACIÓN DE GUARDAR TUTORÍA (NUEVO)
        // ==============================================
        const guardarBtn = document.getElementById('guardarTutoria');
        if (guardarBtn) {
            guardarBtn.addEventListener('click', function() {
                const fecha = document.getElementById('fechaTutoria').value.trim();
                const tema = document.getElementById('temaTutoria').value.trim();
                
                if (!fecha || !tema) {
                    alertaInfo(
                        'Campos incompletos',
                        'Debes completar todos los campos obligatorios del formulario.'
                    );
                    return;
                }
                
                confirmarAccion(
                    'Guardar tutoría',
                    '¿Estás seguro de que quieres guardar esta tutoría?',
                    'Guardar',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaExito(
                            'Tutoría guardada',
                            'La tutoría se ha registrado correctamente.'
                        );
                        cerrarModal();
                    }
                });
            });
        }

        // ==============================================
        // 4. EDITAR TUTORÍA
        // ==============================================
        document.querySelectorAll('.btn-editar-tutoria').forEach(btn => {
            btn.addEventListener('click', function() {
                modalTitulo.textContent = 'Editar tutoría';
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
        // 5. CONFIRMACIÓN DE ELIMINAR TUTORÍA (NUEVO)
        // ==============================================
        document.querySelectorAll('.btn-eliminar-tutoria').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const row = this.closest('tr');
                const fecha = row.cells[0].textContent;
                const tema = row.cells[1].textContent;
                
                confirmarEliminacion(
                    '¿Eliminar tutoría?',
                    `La tutoría "${tema}" del ${fecha} se eliminará permanentemente.`
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Funcionalidad pendiente',
                            'La eliminación de tutorías se implementará posteriormente.'
                        );
                    }
                });
            });
        });

        // ==============================================
        // 6. VISUALIZAR DOCUMENTOS (NUEVO)
        // ==============================================
        const btnVerActa = document.getElementById('btnVerActa');
        if (btnVerActa) {
            btnVerActa.addEventListener('click', function(e) {
                e.preventDefault();
                alertaInfo(
                    'Ver documento',
                    'La visualización de documentos se implementará posteriormente.'
                );
            });
        }

        const btnVerConstancia = document.getElementById('btnVerConstancia');
        if (btnVerConstancia) {
            btnVerConstancia.addEventListener('click', function(e) {
                e.preventDefault();
                alertaInfo(
                    'Ver documento',
                    'La visualización de documentos se implementará posteriormente.'
                );
            });
        }
    });
</script>
@endpush