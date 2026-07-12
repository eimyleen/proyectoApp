@extends('layouts.dashboard')

@section('title', __('messages.career_detail_title'))
@section('subtitle', __('messages.career_detail_subtitle'))
@section('title', 'Administrador - Detalle de Carrera')

@section('subtitle', 'Gestiona los grupos y maestros de esta carrera')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="carrera-container">
        <!-- Header de la carrera -->
        <div class="carrera-header">
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
            <div class="carrera-info">
                <h2>{{ $carrera->nombre }}</h2>
                <p class="carrera-clave">Clave: {{ $carrera->clave }}</p>
                <p>{{ __('messages.career_management') }}</p>
            </div>
            <div class="carrera-acciones">
                <button class="btn-editar" id="btnEditarCarrera">
                    <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> {{ __('messages.btn_edit_career') }}
                </button>
                <form action="{{ route("admin.delete", $carrera) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-eliminar-carrera" id="btnEliminarCarrera">
                        ✕ {{ __('messages.btn_delete_career') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="grupos">{{ __('messages.tab_groups') }}</button>
            <button class="tab-btn" data-tab="maestros">{{ __('messages.tab_teachers') }}</button>
        </div>

        <!-- Contenido Grupos -->
        <div class="tab-content active" id="tab-grupos">
            <!-- Panel de información del tutor -->
            <div class="tutor-info-panel">
                <div class="tutor-info">
                    <span class="tutor-label">{{ __('messages.tutor_label') }}</span>
                    <span class="tutor-nombre" id="tutorNombre"></span>
                </div>
            </div>

            <div class="filtro-grupo">
                <select class="grupo-select" id="filtroGrupo">
                    <option value="">{{ __('messages.select_group') }}</option>
                    <!-- Los grupos se cargarán dinámicamente desde el backend -->
                </select>
                <div class="botones-accion">
                    <button class="btn-agregar" id="btnAgregarAlumno">
                        {{ __('messages.btn_add_student') }}
                    </button>
                    <button class="btn-descargar-lista" id="btnDescargarGrupos">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga"> {{ __('messages.btn_download_groups') }}
                    </button>
                </div>
            </div>
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
                            @if($alumno->carrera_id == $carrera->id)
                                <tr>
                                    <td class="col-numero">{{ $i+1 }}</td>
                                    <td class="col-matricula">{{ $alumno->matricula }}</td>
                                    <td class="col-nombre">{{ $alumno->user?->name }}</td>
                                    <td class="col-nombre">{{ $alumno->user?->apellido }}</td>
                                    <td class="col-acciones">
                                        <a href="{{ route('admin.alumno.expediente', $alumno->id) }}" style="text-decoration: none;">
                                            <button class="btn-ver-expediente">{{ __('messages.btn_view_record') }}</button>
                                        </a>
                                        
                                        <button class="btn-eliminar">{{ __('messages.btn_delete') }}</button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Contenido Maestros -->
        <div class="tab-content" id="tab-maestros">
            <div class="filtro-grupo" style="justify-content: flex-end;">
                <div class="botones-accion">
                    <button class="btn-agregar" id="btnAgregarMaestro">
                        {{ __('messages.btn_add_teacher') }}
                    </button>
                    <button class="btn-descargar-lista" id="btnDescargarMaestros">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga"> {{ __('messages.btn_download_teachers') }}
                    </button>
                </div>
            </div>
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
                        @foreach($maestros as $i => $maestro)
                        //TODO: aqui se necesita en el backend una referencia clara con las materias y carreras con el maestro y alumno para los filtros...
                            @if ($maestro->carrera_id == $carrera->id)
                                <tr>
                                    <td class="col-numero">{{ $i+1 }}</td>
                                    <td class="col-nombre">{{ $maestro->user?->name }}</td>
                                    <td class="col-nombre">{{ $maestro->user?->apellido }}</td>
                                    <td class="col-correo">{{ $maestro->user?->email }}</td>
                                    <td class="col-acciones">
                                        <a href="{{ route('admin.maestro.perfil', $maestro->id) }}" style="text-decoration: none;">
                                            <button class="btn-ver-perfil">{{ __('messages.btn_view_profile') }}</button>
                                        </a>
                                        <button class="btn-eliminar">{{ __('messages.btn_delete') }}</button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para agregar alumno -->
    <div id="modalAgregarAlumno" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_add_student') }}</h3>
                <span class="modal-close" id="closeModalAlumno">&times;</span>
            </div>
            <form action="{{ route("admin.carrera.storeAlumno", $carrera) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input name="name" type="text" id="nombreAlumno" placeholder="Ej: Juan">
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input name="apellido" type="text" id="apellidosAlumno" placeholder="Ej: Pérez García">
                    </div>
                    <div class="form-group">
                        <label>Grupo</label>
                        <select name="grupo" id="grupoAlumno">
                            <option value=""></option>
                            <option value="TI-XX">TI-XX</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Matrícula</label>
                        <input name="matricula" type="text" id="matriculaAlumno" placeholder="Ej: UTN-2024-001">
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input name="email" type="email" id="correoAlumno" placeholder="ejemplo@utnay.edu.mx">
                    </div>
                    <div class="form-group">
                        <label>CURP</label>
                        <input name="curp" type="input" id="curpAlumno" placeholder="48932HJFIE">
                    </div>
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input name="fecha_nacimiento" type="date" id="fechaNacAlumno" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Edad</label>
                        <input name="edad" type="number" value="18" min="18" max="80" id="edadAlumno" placeholder="18">
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
                                <td><input id="sexoAlumnoMas" name="sexo" type="radio" value="Masculino"></td>
                                <td><input id="sexoAlumnoFem" name="sexo" type="radio" value="Femenino"></td>
                                <td><input id="sexoAlumnoOt" name="sexo" type="radio" value="Otro"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Telefono</label>
                        <input name="telefono" type="input" id="telefonoAlumno" placeholder="3110006785">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar" id="">Guardar alumno</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para agregar maestro -->
    <div id="modalAgregarMaestro" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_add_teacher') }}</h3>
                <span class="modal-close" id="closeModalMaestro">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('messages.field_employee_num') }}</label>
                    <input type="text" id="numEmpleado" placeholder="{{ __('messages.placeholder_id_teacher') }}">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.field_firstname') }}</label>
                    <input type="text" id="nombreMaestro" placeholder="{{  __('messages.placeholder_name')  }}">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.field_lastname') }}</label>
                    <input type="text" id="apellidosMaestro" placeholder="{{ __('messages.placeholder_lastname') }}">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.field_email') }}</label>
                    <input type="email" id="correoMaestro" placeholder="{{ __('messages.placeholder_email') }}">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.field_phone') }}</label>
                    <input type="text" id="telefonoMaestro" placeholder="{{ __('messages.placeholder_phone') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-guardar" id="guardarMaestro">{{ __('messages.btn_save') }}</button>
            </div>
        </div>
    </div>

    <!-- Modal para editar carrera -->
    <form action="{{ route("admin.update", $carrera) }}" method="POST">
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
                        <input name="inLogo" type="file" id="logoCarrera" accept="image/*" value="{{ $carrera->logo }}">
                        <small class="form-text">{{ __('messages.helper_logo') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar">{{ __('messages.btn_save_changes') }}</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flecha de regreso
        const backButton = document.getElementById('backButton');
        if (backButton) {
            backButton.addEventListener('click', function() {
                window.location.href = '/dashboard/admin';
            });
        }

        // Cambio de pestañas
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

        // Tutor (ejemplo, backend llenará)
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

        // Modal Agregar Alumno
        const modalAlumno = document.getElementById('modalAgregarAlumno');
        const btnAgregarAlumno = document.getElementById('btnAgregarAlumno');
        const closeModalAlumno = document.getElementById('closeModalAlumno');
        const cancelarAlumno = document.getElementById('cancelarAlumno');

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
        if (cancelarAlumno) cancelarAlumno.onclick = cerrarModalAlumno;

        // Modal Agregar Maestro
        const modalMaestro = document.getElementById('modalAgregarMaestro');
        const btnAgregarMaestro = document.getElementById('btnAgregarMaestro');
        const closeModalMaestro = document.getElementById('closeModalMaestro');
        const cancelarMaestro = document.getElementById('cancelarMaestro');

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
        if (cancelarMaestro) cancelarMaestro.onclick = cerrarModalMaestro;

        // Modal Editar Carrera
        const modalCarrera = document.getElementById('modalCarrera');
        const btnEditarCarrera = document.getElementById('btnEditarCarrera');
        const closeModalCarrera = document.getElementById('closeModalCarrera');
        const cancelarCarrera = document.getElementById('cancelarCarrera');

        if (btnEditarCarrera) {
            btnEditarCarrera.onclick = function() {
                modalCarrera.style.display = 'flex';
            };
        }

        function cerrarModalCarrera() {
            modalCarrera.style.display = 'none';
        }

        if (closeModalCarrera) closeModalCarrera.onclick = cerrarModalCarrera;
        if (cancelarCarrera) cancelarCarrera.onclick = cerrarModalCarrera;

        // Cerrar modales al hacer clic fuera
        window.onclick = function(e) {
            if (e.target === modalAlumno) cerrarModalAlumno();
            if (e.target === modalMaestro) cerrarModalMaestro();
            if (e.target === modalCarrera) cerrarModalCarrera();
        };

        // Guardar alumno
        const guardarAlumno = document.getElementById('guardarAlumno');
        if (guardarAlumno) {
            guardarAlumno.onclick = function() {
                const matricula = document.getElementById('matriculaAlumno').value;
                const nombre = document.getElementById('nombreAlumno').value;
                const apellidos = document.getElementById('apellidosAlumno').value;
                if (matricula && nombre && apellidos) {
                    cerrarModalAlumno();
                } else {
                    alert('Por favor complete Matrícula, Nombre y Apellidos');
                }
            };
        }

        // Guardar maestro
        const guardarMaestro = document.getElementById('guardarMaestro');
        if (guardarMaestro) {
            guardarMaestro.onclick = function() {
                const nombre = document.getElementById('nombreMaestro').value;
                const apellidos = document.getElementById('apellidosMaestro').value;
                const correo = document.getElementById('correoMaestro').value;
                if (nombre && apellidos && correo) {
                    cerrarModalMaestro();
                } else {
                    alert('Por favor complete Nombre, Apellidos y Correo');
                }
            };
        }

        // Guardar carrera
        const guardarCarrera = document.getElementById('guardarCarrera');
        if (guardarCarrera) {
            guardarCarrera.onclick = function() {
                const nombre = document.getElementById('nombreCarrera').value;
                const clave = document.getElementById('claveCarrera').value;
                if (nombre && clave) {
                    cerrarModalCarrera();
                    document.querySelector('.carrera-info h2').textContent = nombre;
                    document.querySelector('.carrera-clave').textContent = 'Clave: ' + clave;
                } else {
                    alert('Por favor ingrese el nombre y la clave de la carrera');
                }
            };
        }

        // Eliminar carrera
        const btnEliminarCarrera = document.getElementById('btnEliminarCarrera');
        if (btnEliminarCarrera) {
            btnEliminarCarrera.onclick = function() {
                if (confirm('¿Estás seguro de eliminar esta carrera?')) {
                    alert('Carrera eliminada');
                }
            };
        }

        // Botones de descarga
        const btnDescargarGrupos = document.getElementById('btnDescargarGrupos');
        if (btnDescargarGrupos) {
            btnDescargarGrupos.onclick = function() {
                alert('Descargar lista de grupos');
            };
        }

        const btnDescargarMaestros = document.getElementById('btnDescargarMaestros');
        if (btnDescargarMaestros) {
            btnDescargarMaestros.onclick = function() {
                alert('Descargar lista de maestros');
            };
        }

        // Botones de acciones
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-eliminar')) {
                if (confirm('¿Estás seguro de eliminar este elemento?')) {
                    const row = e.target.closest('tr');
                    if (row) row.remove();
                }
            }
        });
    });
</script>
@endpush