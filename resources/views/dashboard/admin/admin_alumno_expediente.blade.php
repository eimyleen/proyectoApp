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
@section('title', __('messages.admin_alumno_expediente_title'))
@section('subtitle', __('messages.admin_alumno_expediente_subtitle'))

@section('title', 'Expediente del Alumno - Administrador')
@section('subtitle', 'Consulta la información personal del alumno')

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
        
        {{-- ======================================================
             BOTÓN GENERAR PDF
             ====================================================== 
             Permite descargar el expediente en formato PDF.
        --}}
        <div class="pdf-button-container">
            <button class="btn-generar-pdf" onclick="alert('Generando PDF del expediente...')">
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

                {{-- Teléfono (NUEVO) --}}
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

        {{-- ======================================================
             SECCIÓN DE TUTORÍAS (NUEVO)
             ====================================================== 
             Muestra la tabla de tutorías del alumno.
             Incluye botón para agregar nuevas tutorías.
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
                            <td>
                                <button class="btn-editar-tutoria">Editar</button> 
                                <button class="btn-eliminar-tutoria">Eliminar</button>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- ======================================================
             MODAL - EDITAR ALUMNO (NUEVO)
             ====================================================== 
             Modal para editar los datos del alumno.
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
                    <button class="btn-cancelar" id="cancelarEditar">Cancelar</button>
                    <button class="btn-guardar" id="guardarEditar">Guardar cambios</button>
                </div>
            </div>
        </div>

        {{-- ======================================================
             SECCIÓN DE DOCUMENTOS DEL ALUMNO
             ====================================================== 
             Muestra los documentos del alumno con estado.
        --}}
        <div class="documentos-container">
            <h3 class="seccion-titulo documentos-titulo">{{ __('messages.expedient_documents') }}</h3>
            
            {{-- Documento 1: Acta de nacimiento (subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Acta de nacimiento</span>
                    <span class="documento-estado subido">{{ __('messages.document_uploaded') }}</span>
                </div>
                <button class="btn-ver-documento" onclick="alert('Ver documento: Acta de nacimiento')">
                    <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                    {{ __('messages.document_view') }}
                </button>
            </div>

            {{-- Documento 2: CURP (no subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">CURP</span>
                    <span class="documento-estado no-subido">{{ __('messages.document_not_uploaded') }}</span>
                </div>
                <span class="estado-sin-boton">—</span>
            </div>

            {{-- Documento 3: Certificado de bachillerato (no subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Certificado de bachillerato</span>
                    <span class="documento-estado no-subido">{{ __('messages.document_not_uploaded') }}</span>
                </div>
                <span class="estado-sin-boton">—</span>
            </div>

            {{-- Documento 4: Constancia de estudios (subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Constancia de estudios</span>
                    <span class="documento-estado subido">{{ __('messages.document_uploaded') }}</span>
                </div>
                <button class="btn-ver-documento" onclick="alert('Ver documento: Constancia de estudios')">
                    <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                    {{ __('messages.document_view') }}
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
        - Botón generar PDF (alerta de ejemplo)
    --}}
    document.addEventListener('DOMContentLoaded', function() {
        const btnPdf = document.querySelector('.btn-generar-pdf');
        if (btnPdf) {
            btnPdf.addEventListener('click', function() {
                alert('Generando PDF del expediente...');
            });
        }
    });
</script>
@endpush