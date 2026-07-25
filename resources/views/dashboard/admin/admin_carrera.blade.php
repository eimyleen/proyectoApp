{{-- 
    ============================================================
    ADMIN - DETALLE DE CARRERA
    ============================================================
    Esta vista muestra el detalle de una carrera específica.
    Muestra:
    - Header con logo, nombre, clave y acciones (editar/eliminar)
    - Pestañas para cambiar entre Grupos y Maestros
    - Tabla de alumnos con filtro por grupo
    - Tabla de maestros
    - Modales para agregar alumno, agregar maestro y editar carrera
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_admin.css
    - Botón de regreso: visible (back-button)
    - Se conecta con: admin.index (dashboard de admin)
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
--}}
@section('title', __('messages.career_detail_title'))
@section('subtitle', __('messages.career_detail_subtitle'))

@section('title', 'Administrador - Detalle de Carrera')
@section('subtitle', 'Gestiona los grupos y maestros de esta carrera')

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
    En este caso, al dashboard de administrador.
--}}
@section('back-url', '/dashboard/admin')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    
    {{-- 
        CONTENEDOR PRINCIPAL
        Fondo blanco con sombra y bordes redondeados.
    --}}
    <div class="carrera-container">
        
        {{-- ======================================================
             HEADER DE LA CARRERA
             ====================================================== 
             Muestra:
             - Logo circular de la carrera (o logo por defecto)
             - Nombre de la carrera
             - Clave de la carrera
             - Botones: Editar carrera y Eliminar carrera
        --}}
        <div class="carrera-header">
            
            {{-- Logo de la carrera --}}
            <div class="carrera-logo">
                <div class="logo-circular" style="width: 120px; height: 120px; overflow: hidden;">
                    @if($carrera->logo)
                        <img src="{{ asset($carrera->logo) }}" 
                            alt="{{ $carrera->nombre }}"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <img src="{{ asset('img/jaguar.png') }}" alt="{{ __('messages.admin_no_logo') }}">
                    @endif
                </div>
            </div>
            
            {{-- Información de la carrera --}}
            <div class="carrera-info">
                <h2>{{ $carrera->nombre }}</h2>
                <p class="carrera-clave">Clave: {{ $carrera->clave }}</p>
                <p>{{ __('messages.career_management') }}</p>
            </div>
            
            {{-- Acciones de la carrera --}}
            <div class="carrera-acciones">
                {{-- Botón Editar (abre modal) --}}
                <button class="btn-editar" id="btnEditarCarrera">
                    <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> 
                    {{ __('messages.btn_edit_career') }}
                </button>
                
                {{-- 
                    ======================================================
                    NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE ELIMINAR CARRERA
                    ======================================================
                    Se agregó ID "formEliminarCarrera" al formulario y una
                    alerta de confirmación con SweetAlert antes de ejecutar
                    la acción.
                    
                    La ruta y el método de eliminación se mantienen igual.
                    ======================================================
                --}}
                <form action="{{ route('admin.delete', $carrera) }}" method="POST" id="formEliminarCarrera">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn-eliminar-carrera" id="btnEliminarCarrera">
                        ✕ {{ __('messages.btn_delete_career') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- ======================================================
             PESTAÑAS
             ====================================================== 
             Permite cambiar entre la vista de Grupos y Maestros.
             La pestaña activa tiene un borde inferior azul oscuro.
        --}}
        <div class="tabs">
            <button class="tab-btn active" data-tab="grupos">{{ __('messages.tab_groups') }}</button>
            <button class="tab-btn" data-tab="maestros">{{ __('messages.tab_teachers') }}</button>
        </div>

        {{-- ======================================================
             CONTENIDO - GRUPOS
             ====================================================== 
             Muestra:
             - Panel del tutor (información del tutor del grupo)
             - Filtro para seleccionar un grupo específico
             - Botones: Agregar alumno y Descargar lista
             - Tabla con los alumnos del grupo seleccionado
        --}}
        <div class="tab-content active" id="tab-grupos">
            
            {{-- Panel de información del tutor --}}
            <div class="tutor-info-panel">
                <div class="tutor-info">
                    <span class="tutor-label">{{ __('messages.tutor_label') . ': ' . __('messages.groups_no_tutor') }}</span>
                    <span class="tutor-nombre" id="tutorNombre"></span>
                </div>
            </div>

            {{-- Filtro y acciones --}}
            <div class="filtro-grupo">
                {{-- Select de grupos (recarga la página al cambiar) --}}
                <form method="GET">
                    <select name="grupo_id" class="grupo-select" onchange="this.form.submit()">
                        <option value="">{{ __('messages.select_group') }}</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}"
                                {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>
                
                {{-- Botones de acción --}}
                <div class="botones-accion">
                    <button class="btn-agregar" id="btnAgregarAlumno">
                        {{ __('messages.btn_add_student') }}
                    </button>
                    {{-- 
                        ======================================================
                        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE DESCARGA
                        ======================================================
                        Se agregó ID "btnDescargarGrupos" y una alerta de
                        confirmación con SweetAlert antes de ejecutar la acción.
                        ======================================================
                    --}}
                    <button class="btn-descargar-lista" id="btnDescargarGrupos">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga"> 
                        {{ __('messages.btn_download_groups') }}
                    </button>
                </div>
            </div>
            
            {{-- Tabla de alumnos --}}
            <div class="tabla-container">
                <table class="tabla-alumnos">
                    <thead>
                        <tr>
                            <th>{{ __('messages.column_number') }}</th>
                            <th>{{ __('messages.column_id') }}</th>
                            <th>{{ __('messages.column_name') }}</th>
                            <th>{{ __('messages.column_lastname') }}</th>
                            <th>{{ __('messages.column_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="alumnosBody">
                        @foreach($alumnos as $i => $alumno)
                            <tr>
                                <td class="col-numero">{{ $i+1 }}</td>
                                <td class="col-matricula">{{ $alumno->matricula }}</td>
                                <td class="col-nombre">{{ $alumno->user?->name }}</td>
                                <td class="col-nombre">{{ $alumno->user?->apellido }}</td>
                                <td class="col-acciones">
                                    {{-- Botón Ver Expediente --}}
                                    <a href="{{ route('admin.alumno.expediente', $alumno->id) }}" style="text-decoration: none;">
                                        <button class="btn-ver-expediente">{{ __('messages.btn_view_record') }}</button>
                                    </a>
                                    
                                    {{-- 
                                        ======================================================
                                        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE ELIMINAR ALUMNO
                                        ======================================================
                                        Se agregó data attributes para manejar la alerta
                                        de confirmación con SweetAlert antes de ejecutar
                                        la acción de eliminación.
                                        
                                        La eliminación real permanece a cargo del backend.
                                        ======================================================
                                    --}}
                                    <button class="btn-eliminar btn-eliminar-alumno" 
                                            data-id="{{ $alumno->id }}" 
                                            data-nombre="{{ $alumno->user?->name }} {{ $alumno->user?->apellido }}">
                                        {{ __('messages.btn_delete') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ======================================================
             CONTENIDO - MAESTROS
             ====================================================== 
             Muestra:
             - Botones: Agregar maestro y Descargar lista
             - Tabla con los maestros que imparten la carrera
        --}}
        <div class="tab-content" id="tab-maestros">
            
            {{-- Acciones --}}
            <div class="filtro-grupo" style="justify-content: flex-end;">
                <div class="botones-accion">
                    <button class="btn-agregar" id="btnAgregarMaestro">
                        {{ __('messages.btn_add_teacher') }}
                    </button>
                    {{-- 
                        ======================================================
                        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE DESCARGA
                        ======================================================
                        Se agregó ID "btnDescargarMaestros" y una alerta de
                        confirmación con SweetAlert antes de ejecutar la acción.
                        ======================================================
                    --}}
                    <button class="btn-descargar-lista" id="btnDescargarMaestros">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga"> 
                        {{ __('messages.btn_download_teachers') }}
                    </button>
                </div>
            </div>
            
            {{-- Tabla de maestros --}}
            <div class="tabla-container">
                <table class="tabla-maestros">
                    <thead>
                        <tr>
                            <th>{{ __('messages.column_number') }}</th>
                            <th>{{ __('messages.column_name') }}</th>
                            <th>{{ __('messages.column_lastname') }}</th>
                            <th>{{ __('messages.column_email') }}</th>
                            <th>{{ __('messages.column_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="maestrosBody">
                        {{-- Solo mostrar maestros que están asignados a esta carrera --}}
                        @foreach($maestros as $i => $maestro)
                            @if ($maestro->carreras->contains('id', $carrera->id))
                                <tr>
                                    <td class="col-numero">{{ $i+1 }}</td>
                                    <td class="col-nombre">{{ $maestro->user?->name }}</td>
                                    <td class="col-nombre">{{ $maestro->user?->apellido }}</td>
                                    <td class="col-correo">{{ $maestro->user?->email }}</td>
                                    <td class="col-acciones">
                                        {{-- Botón Ver Perfil --}}
                                        <a href="{{ route('admin.maestro.perfil', $maestro->id) }}" style="text-decoration: none;">
                                            <button class="btn-ver-perfil">{{ __('messages.btn_view_profile') }}</button>
                                        </a>
                                        
                                        {{-- 
                                            ======================================================
                                            NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE ELIMINAR MAESTRO
                                            ======================================================
                                            Se agregó data attributes para manejar la alerta
                                            de confirmación con SweetAlert antes de ejecutar
                                            la acción de eliminación.
                                            
                                            La eliminación real permanece a cargo del backend.
                                            ======================================================
                                        --}}
                                        <button class="btn-eliminar btn-eliminar-maestro" 
                                                data-id="{{ $maestro->id }}" 
                                                data-nombre="{{ $maestro->user?->name }} {{ $maestro->user?->apellido }}">
                                            {{ __('messages.btn_delete') }}
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 
        ======================================================
        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE GUARDAR ALUMNO
        ======================================================
        Se agregó ID "formAgregarAlumno" al formulario y una
        alerta de confirmación con SweetAlert antes de ejecutar
        la acción de guardado.
        
        También se agregó validación de campos obligatorios.
        ======================================================
    --}}
    <div id="modalAgregarAlumno" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_add_student') }}</h3>
                <span class="modal-close" id="closeModalAlumno">&times;</span>
            </div>
            <form action="{{ route('admin.carrera.storeAlumno', $carrera) }}" method="POST" id="formAgregarAlumno">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input name="name" type="text" id="nombreAlumno" placeholder="Ej: Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input name="apellido" type="text" id="apellidosAlumno" placeholder="Ej: Pérez García" required>
                    </div>
                    <div class="form-group">
                        <label>Grupo</label>
                        <select name="grupo" id="grupoAlumno" required>
                            <option value="">Selecciona un Grupo</option>
                            @foreach ($grupos as $grup)
                                <option value="{{ $grup->id }}">{{ $grup->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Matrícula</label>
                        <input name="matricula" type="text" id="matriculaAlumno" placeholder="Ej: UTN-2024-001" required>
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input name="email" type="email" id="correoAlumno" placeholder="ejemplo@utnay.edu.mx" required>
                    </div>
                    <div class="form-group">
                        <label>CURP</label>
                        <input name="curp" type="input" id="curpAlumno" placeholder="48932HJFIE" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input name="fecha_nacimiento" type="date" id="fechaNacAlumno" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <table>
                            <tr style="text-align: center">
                                <td><label>Masculino</label></td>
                                <td><label>Femenino</label></td>
                                <td><label>Otro</label></td>
                            </tr>
                            <tr>
                                <td><input id="sexoAlumnoMas" name="sexo" type="radio" value="M" required></td>
                                <td><input id="sexoAlumnoFem" name="sexo" type="radio" value="F" required></td>
                                <td><input id="sexoAlumnoOt" name="sexo" type="radio" value="Otro" required></td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Telefono</label>
                        <input name="telefono" type="input" id="telefonoAlumno" placeholder="3110006785">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar" id="guardarAlumno">Guardar alumno</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 
        ======================================================
        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE GUARDAR MAESTRO
        ======================================================
        Se agregó ID "formAgregarMaestro" al formulario y una
        alerta de confirmación con SweetAlert antes de ejecutar
        la acción de guardado.
        
        El formulario no tiene action ni method porque la
        funcionalidad de backend se implementará posteriormente.
        ======================================================
    --}}
    <div id="modalAgregarMaestro" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_add_teacher') }}</h3>
                <span class="modal-close" id="closeModalMaestro">&times;</span>
            </div>
            <form action="{{ route('admin.carrera.storeMaestro', $carrera) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('messages.field_firstname') }}</label>
                        <input type="text" id="nombreMaestro" placeholder="{{  __('messages.placeholder_name')  }}" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_lastname') }}</label>
                        <input type="text" id="apellidosMaestro" placeholder="{{ __('messages.placeholder_lastname') }}" name="apellido" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_email') }}</label>
                        <input type="email" id="correoMaestro" placeholder="{{ __('messages.placeholder_email') }}" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_rfc') }}</label>
                        <input type="text" id="numEmpleado" placeholder="{{ __('messages.placeholder_rfc_teacher') }}" name="rfc" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_employee_num') }}</label>
                        <input type="text" id="numEmpleado" placeholder="{{ __('messages.placeholder_id_teacher') }}" name="num_empleado" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input name="fecha_nacimiento" type="date" id="fechaNacAlumno" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <table>
                            <tr style="text-align: center">
                                <td><label>Masculino</label></td>
                                <td><label>Femenino</label></td>
                                <td><label>Otro</label></td>
                            </tr>
                            <tr>
                                <td><input id="sexoAlumnoMas" name="sexo" type="radio" value="M" required></td>
                                <td><input id="sexoAlumnoFem" name="sexo" type="radio" value="F" required></td>
                                <td><input id="sexoAlumnoOt" name="sexo" type="radio" value="Otro" required></td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_phone') }}</label>
                        <input type="text" name="telefono" id="telefonoMaestro" placeholder="{{ __('messages.placeholder_phone') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar">{{ __('messages.btn_save') }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 
        ======================================================
        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE GUARDAR CARRERA
        ======================================================
        Se agregó ID "formEditarCarrera" al formulario y una
        alerta de confirmación con SweetAlert antes de ejecutar
        la acción de guardado.
        
        También se agregó validación de campos obligatorios.
        ======================================================
    --}}
    <form action="{{ route('admin.update', $carrera) }}" method="POST" enctype="multipart/form-data" id="formEditarCarrera">
        @csrf
        @method('PATCH')
        <div id="modalCarrera" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ __('messages.modal_edit_career') }}</h3>
                    <span class="modal-close" id="closeModalCarrera">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('messages.field_career_name') }}</label>
                        <input name="inNombre" type="text" id="nombreCarrera" value="{{ $carrera->nombre }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_career_key') }}</label>
                        <input name="inClave" type="text" id="claveCarrera" value="{{ $carrera->clave }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_career_logo') }}</label>
                        <input name="inLogo" type="file" id="logoCarrera" accept="image/*">
                        <small class="form-text">{{ __('messages.helper_logo') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar" id="guardarCarrera">{{ __('messages.btn_save_changes') }}</button>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- 
    ======================================================
    SCRIPTS ADICIONALES
    ======================================================
    NOTA: FUNCIONALIDADES AGREGADAS
    ======================================================
    1. Confirmación antes de eliminar carrera.
    2. Confirmación antes de descargar lista de grupos.
    3. Confirmación antes de descargar lista de maestros.
    4. Confirmación antes de eliminar alumno.
    5. Confirmación antes de eliminar maestro.
    6. Confirmación antes de guardar alumno + validación de campos.
    7. Confirmación antes de guardar maestro + validación de campos.
    8. Confirmación antes de guardar carrera + validación de campos.
    
    La eliminación y guardado real permanecen a cargo del backend.
    ======================================================
--}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        1. Cambio de pestañas (Grupos / Maestros)
        2. Modales (Agregar Alumno, Agregar Maestro, Editar Carrera)
        3. Cerrar modales al hacer clic fuera
        4. Confirmación antes de eliminar carrera
        5. Confirmación antes de descargar lista de grupos
        6. Confirmación antes de descargar lista de maestros
        7. Confirmación antes de eliminar alumno
        8. Confirmación antes de eliminar maestro
        9. Confirmación antes de guardar alumno + validación
        10. Confirmación antes de guardar maestro + validación
        11. Confirmación antes de guardar carrera + validación
    --}}

    document.addEventListener('DOMContentLoaded', function() {
        
        // ==============================================
        // 1. CAMBIO DE PESTAÑAS
        // ==============================================
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(`tab-${tabId}`).classList.add('active');
            });
        });

        // ==============================================
        // 2. TUTOR (placeholder mientras se integra la funcionalidad)
        // ==============================================
        const tutorNombreSpan = document.getElementById('tutorNombre');
        const filtroGrupo = document.getElementById('filtroGrupo');

        function cargarTutor(grupo) {
            if (tutorNombreSpan) {
                tutorNombreSpan.textContent = '';
            }
        }

        if (filtroGrupo) {
            filtroGrupo.addEventListener('change', function() {
                cargarTutor(this.value);
            });
        }

        // ==============================================
        // 3. MODAL AGREGAR ALUMNO
        // ==============================================
        const modalAlumno = document.getElementById('modalAgregarAlumno');
        const btnAgregarAlumno = document.getElementById('btnAgregarAlumno');
        const closeModalAlumno = document.getElementById('closeModalAlumno');

        if (btnAgregarAlumno) {
            btnAgregarAlumno.onclick = function() {
                modalAlumno.style.display = 'flex';
            };
        }

        function cerrarModalAlumno() {
            modalAlumno.style.display = 'none';
            document.getElementById('nombreAlumno').value = '';
            document.getElementById('apellidosAlumno').value = '';
            document.getElementById('grupoAlumno').value = '';
            document.getElementById('matriculaAlumno').value = '';
            document.getElementById('correoAlumno').value = '';
            document.getElementById('curpAlumno').value = '';
            document.getElementById('fechaNacAlumno').value = '';
            document.getElementById('edadAlumno').value = '';
            document.getElementById('sexoAlumnoMas').checked = false;
            document.getElementById('sexoAlumnoFem').checked = false;
            document.getElementById('sexoAlumnoOt').checked = false;
            document.getElementById('telefonoAlumno').value = '';
        }

        if (closeModalAlumno) closeModalAlumno.onclick = cerrarModalAlumno;

        // ==============================================
        // 4. MODAL AGREGAR MAESTRO
        // ==============================================
        const modalMaestro = document.getElementById('modalAgregarMaestro');
        const btnAgregarMaestro = document.getElementById('btnAgregarMaestro');
        const closeModalMaestro = document.getElementById('closeModalMaestro');

        if (btnAgregarMaestro) {
            btnAgregarMaestro.onclick = function() {
                modalMaestro.style.display = 'flex';
            };
        }

        function cerrarModalMaestro() {
            modalMaestro.style.display = 'none';
            document.getElementById('numEmpleado').value = '';
            document.getElementById('nombreMaestro').value = '';
            document.getElementById('apellidosMaestro').value = '';
            document.getElementById('correoMaestro').value = '';
            document.getElementById('telefonoMaestro').value = '';
        }

        if (closeModalMaestro) closeModalMaestro.onclick = cerrarModalMaestro;

        // ==============================================
        // 5. MODAL EDITAR CARRERA
        // ==============================================
        const modalCarrera = document.getElementById('modalCarrera');
        const btnEditarCarrera = document.getElementById('btnEditarCarrera');
        const closeModalCarrera = document.getElementById('closeModalCarrera');

        if (btnEditarCarrera) {
            btnEditarCarrera.onclick = function() {
                modalCarrera.style.display = 'flex';
            };
        }

        function cerrarModalCarrera() {
            modalCarrera.style.display = 'none';
        }

        if (closeModalCarrera) closeModalCarrera.onclick = cerrarModalCarrera;

        // ==============================================
        // 6. CERRAR MODALES AL HACER CLIC FUERA
        // ==============================================
        window.onclick = function(e) {
            if (e.target === modalAlumno) cerrarModalAlumno();
            if (e.target === modalMaestro) cerrarModalMaestro();
            if (e.target === modalCarrera) cerrarModalCarrera();
        };

        // ==============================================
        // 7. CONFIRMACIÓN DE ELIMINAR CARRERA
        // ==============================================
        const formEliminarCarrera = document.getElementById('formEliminarCarrera');
        if (formEliminarCarrera) {
            formEliminarCarrera.addEventListener('submit', function(e) {
                e.preventDefault();
                confirmarAccion(
                    'Confirmar eliminación',
                    '¿Deseas continuar con esta acción?'
                ).then((result) => {
                    if (result.isConfirmed) {
                        formEliminarCarrera.submit();
                    }
                });
            });
        }

        // ==============================================
        // 8. CONFIRMACIÓN DE DESCARGAR LISTA DE GRUPOS
        // ==============================================
        const btnDescargarGrupos = document.getElementById('btnDescargarGrupos');
        if (btnDescargarGrupos) {
            btnDescargarGrupos.addEventListener('click', function(e) {
                e.preventDefault();
                confirmarAccion(
                    'Descargar lista de grupos',
                    '¿Deseas continuar con esta acción?'
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Descarga de lista',
                            'La funcionalidad de descarga se integrará próximamente.'
                        );
                    }
                });
            });
        }

        // ==============================================
        // 9. CONFIRMACIÓN DE DESCARGAR LISTA DE MAESTROS
        // ==============================================
        const btnDescargarMaestros = document.getElementById('btnDescargarMaestros');
        if (btnDescargarMaestros) {
            btnDescargarMaestros.addEventListener('click', function(e) {
                e.preventDefault();
                confirmarAccion(
                    'Descargar lista de maestros',
                    '¿Deseas continuar con esta acción?'
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Descarga de lista',
                            'La funcionalidad de descarga se integrará próximamente.'
                        );
                    }
                });
            });
        }

        // ==============================================
        // 10. CONFIRMACIÓN DE GUARDAR ALUMNO
        // ==============================================
        const formAgregarAlumno = document.getElementById('formAgregarAlumno');
        if (formAgregarAlumno) {
            formAgregarAlumno.addEventListener('submit', function(e) {
                e.preventDefault();
                const matricula = document.getElementById('matriculaAlumno').value.trim();
                const nombre = document.getElementById('nombreAlumno').value.trim();
                const apellidos = document.getElementById('apellidosAlumno').value.trim();
                const email = document.getElementById('correoAlumno').value.trim();
                
                if (!matricula || !nombre || !apellidos || !email) {
                    alertaInfo(
                        'Campos incompletos',
                        'Debes completar todos los campos obligatorios del formulario.'
                    );
                    return;
                }
                
                confirmarAccion(
                    'Guardar alumno',
                    '¿Estás seguro de que quieres guardar este alumno?',
                    'Guardar',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        formAgregarAlumno.submit();
                    }
                });
            });
        }

        // ==============================================
        // 11. CONFIRMACIÓN DE GUARDAR MAESTRO
        // ==============================================
        const formAgregarMaestro = document.getElementById('formAgregarMaestro');
        if (formAgregarMaestro) {
            formAgregarMaestro.addEventListener('submit', function(e) {
                e.preventDefault();
                const nombre = document.getElementById('nombreMaestro').value.trim();
                const apellidos = document.getElementById('apellidosMaestro').value.trim();
                const correo = document.getElementById('correoMaestro').value.trim();
                
                if (!nombre || !apellidos || !correo) {
                    alertaInfo(
                        'Campos incompletos',
                        'Debes completar todos los campos obligatorios del formulario.'
                    );
                    return;
                }
                
                confirmarAccion(
                    'Guardar maestro',
                    '¿Estás seguro de que quieres guardar este maestro?',
                    'Guardar',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Funcionalidad pendiente',
                            'El registro de maestros se implementará posteriormente.'
                        );
                    }
                });
            });
        }

        // ==============================================
        // 12. CONFIRMACIÓN DE GUARDAR CARRERA
        // ==============================================
        const formEditarCarrera = document.getElementById('formEditarCarrera');
        if (formEditarCarrera) {
            formEditarCarrera.addEventListener('submit', function(e) {
                e.preventDefault();
                const nombre = document.getElementById('nombreCarrera').value.trim();
                const clave = document.getElementById('claveCarrera').value.trim();
                
                if (!nombre || !clave) {
                    alertaInfo(
                        'Campos incompletos',
                        'Debes completar todos los campos obligatorios del formulario.'
                    );
                    return;
                }
                
                confirmarAccion(
                    'Guardar cambios',
                    '¿Estás seguro de que quieres guardar los cambios?',
                    'Guardar',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        formEditarCarrera.submit();
                    }
                });
            });
        }

        // ==============================================
        // 13. CONFIRMACIÓN DE ELIMINAR ALUMNO
        // ==============================================
        document.querySelectorAll('.btn-eliminar-alumno').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const nombre = this.getAttribute('data-nombre') || 'este alumno';
                
                confirmarAccion(
                    'Confirmar eliminación',
                    `¿Estás seguro de que deseas eliminar al alumno "${nombre}"?`
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Funcionalidad pendiente',
                            'La eliminación de alumnos se implementará posteriormente.'
                        );
                    }
                });
            });
        });

        // ==============================================
        // 14. CONFIRMACIÓN DE ELIMINAR MAESTRO
        // ==============================================
        document.querySelectorAll('.btn-eliminar-maestro').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const nombre = this.getAttribute('data-nombre') || 'este maestro';
                
                confirmarAccion(
                    'Confirmar eliminación',
                    `¿Estás seguro de que deseas eliminar al maestro "${nombre}"?`
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Funcionalidad pendiente',
                            'La eliminación de maestros se implementará posteriormente.'
                        );
                    }
                });
            });
        });
    });
</script>
@endpush