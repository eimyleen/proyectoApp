@extends('layouts.dashboard')

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
                    @if($var_carrera->logo)
                        <img src="{{ asset($var_carrera->logo) }}" 
                            alt="{{ $var_carrera->nombre }}"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <img src="{{ asset('img/jaguar.png') }}" alt="Sin logo">
                    @endif
                </div>
            </div>
            <div class="carrera-info">
                <h2>{{ $var_carrera->nombre }}</h2>
                <p class="carrera-clave">Clave: {{ $var_carrera->clave }}</p>
                <p>Gestión de la carrera</p>
            </div>
            <div class="carrera-acciones">
                <button class="btn-editar" id="btnEditarCarrera">
                    <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> Editar carrera
                </button>
                <form action="{{ route("admin.delete", $var_carrera) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-eliminar-carrera" id="btnEliminarCarrera">
                        ✕ Eliminar carrera
                    </button>
                </form>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="grupos">Grupos</button>
            <button class="tab-btn" data-tab="maestros">Maestros</button>
        </div>

        <!-- Contenido Grupos -->
        <div class="tab-content active" id="tab-grupos">
            <!-- Panel de información del tutor -->
            <div class="tutor-info-panel">
                <div class="tutor-info">
                    <span class="tutor-label">Tutor:</span>
                    <span class="tutor-nombre" id="tutorNombre"></span>
                </div>
            </div>

            <div class="filtro-grupo">
                <select class="grupo-select" id="filtroGrupo">
                    <option value="">Seleccionar grupo</option>
                    <!-- Los grupos se cargarán dinámicamente desde el backend -->
                </select>
                <div class="botones-accion">
                    <button class="btn-agregar" id="btnAgregarAlumno">
                        + Agregar alumno
                    </button>
                    <button class="btn-descargar-lista" id="btnDescargarGrupos">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga"> Descargar lista de grupos
                    </button>
                </div>
            </div>
            <div class="tabla-container">
                <table class="tabla-alumnos">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Acciones</th>
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
                                    <button class="btn-ver-expediente">Ver expediente</button>
                                    <button class="btn-eliminar">Eliminar</button>
                                </td>
                            </tr>
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
                        + Agregar maestro
                    </button>
                    <button class="btn-descargar-lista" id="btnDescargarMaestros">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga"> Descargar lista de maestros
                    </button>
                </div>
            </div>
            <div class="tabla-container">
                <table class="tabla-maestros">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="maestrosBody">
                        @foreach($maestros as $i => $maestro)
                            <tr>
                                <td class="col-numero">{{ $i+1 }}</td>
                                <td class="col-nombre">{{ $maestro->user?->name }}</td>
                                <td class="col-nombre">{{ $maestro->user?->apellido }}</td>
                                <td class="col-correo">{{ $maestro->user?->email }}</td>
                                <td class="col-acciones">
                                    <button class="btn-ver-perfil">Ver perfil</button>
                                    <button class="btn-eliminar">Eliminar</button>
                                </td>
                            </tr>
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
                <h3>Agregar alumno</h3>
                <span class="modal-close" id="closeModalAlumno">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Matrícula</label>
                    <input type="text" id="matriculaAlumno" placeholder="Ej: UTN-2024-001">
                </div>
                <div class="form-group">
                    <label>Nombre(s)</label>
                    <input type="text" id="nombreAlumno" placeholder="Ej: Juan">
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" id="apellidosAlumno" placeholder="Ej: Pérez García">
                </div>
                <div class="form-group">
                    <label>Grupo</label>
                    <select id="grupoAlumno">
                        <option value="">Seleccionar grupo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" id="correoAlumno" placeholder="ejemplo@utnay.edu.mx">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-guardar" id="guardarAlumno">Guardar alumno</button>
            </div>
        </div>
    </div>

    <!-- Modal para agregar maestro -->
    <div id="modalAgregarMaestro" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar maestro</h3>
                <span class="modal-close" id="closeModalMaestro">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Número de empleado</label>
                    <input type="text" id="numEmpleado" placeholder="Ej: EMP-2024-001">
                </div>
                <div class="form-group">
                    <label>Nombre(s)</label>
                    <input type="text" id="nombreMaestro" placeholder="Ej: Roberto">
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" id="apellidosMaestro" placeholder="Ej: Sánchez Hernández">
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" id="correoMaestro" placeholder="ejemplo@utnay.edu.mx">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" id="telefonoMaestro" placeholder="Ej: 311-123-4567">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-guardar" id="guardarMaestro">Guardar maestro</button>
            </div>
        </div>
    </div>

    <!-- Modal para editar carrera -->
    <form action="{{ route("admin.update", $var_carrera) }}" method="POST">
        @csrf
        @method('PATCH')
        <div id="modalCarrera" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Editar carrera</h3>
                    <span class="modal-close" id="closeModalCarrera">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre de la carrera</label>
                        <input name="inNombre" type="text" id="nombreCarrera" value="{{ $var_carrera->nombre }}">
                    </div>
                    <div class="form-group">
                        <label>Clave de la carrera</label>
                        <input name="inClave" type="text" id="claveCarrera" value="{{ $var_carrera->clave }}">
                    </div>
                    <div class="form-group">
                        <label>Logo de la carrera</label>
                        <input name="inLogo" type="file" id="logoCarrera" accept="image/*">
                        <small class="form-text">Selecciona una nueva imagen para el logo</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar">Guardar cambios</button>
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
            document.getElementById('matriculaAlumno').value = '';
            document.getElementById('nombreAlumno').value = '';
            document.getElementById('apellidosAlumno').value = '';
            document.getElementById('grupoAlumno').value = '';
            document.getElementById('correoAlumno').value = '';
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
                    alert('Alumno agregado correctamente');
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
                    alert('Maestro agregado correctamente');
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
                    //alert('Carrera actualizada: ' + nombre);
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
            if (e.target.classList.contains('btn-ver-expediente')) {
                alert('Ver expediente del alumno');
            }
            if (e.target.classList.contains('btn-ver-perfil')) {
                alert('Ver perfil del maestro');
            }
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