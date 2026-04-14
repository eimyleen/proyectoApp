@extends('layouts.dashboard')

@section('title', 'Administrador - Perfil del Alumno')

@section('subtitle', 'Consulta y edita la información del alumno')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/admin/carrera')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="perfil-container">
        <div class="acciones-superiores">
            <button class="btn-editar-perfil" id="btnEditarAlumno">
                <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> Editar alumno
            </button>
            <button class="btn-eliminar-perfil" id="btnEliminarAlumno">
                ✕ Eliminar alumno
            </button>
            <button class="btn-descargar-expediente" id="btnDescargarExpediente">
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icono"> Descargar expediente
            </button>
        </div>

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

                <div class="datos-grid">
                    <div class="dato-item">
                        <label>Nombre(s)</label>
                        <span class="dato-valor">{{ $alumno->user->name }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Apellidos</label>
                        <span class="dato-valor">{{ $alumno->user->apellido }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Matrícula</label>
                        <span class="dato-valor">{{ $alumno->matricula }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Carrera</label>
                        <span class="dato-valor">{{ $alumno->carrera->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Grupo</label>
                        <span class="dato-valor">{{ $alumno->grupo }}</span>
                    </div>
                    <div class="dato-item">
                        <label>CURP</label>
                        <span class="dato-valor">{{ $alumno->curp }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Edad</label>
                        <span class="dato-valor">{{ $alumno->edad }} años</span>
                    </div>
                    <div class="dato-item">
                        <label>Sexo</label>
                        <span class="dato-valor">{{ $alumno->sexo }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Fecha de nacimiento</label>
                        <span class="dato-valor">{{ $alumno->fecha_nacimiento }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Correo electrónico</label>
                        <span class="dato-valor">{{ $alumno->user->email }}</span>
                    </div>
                    <div class="dato-item">
                        <label>Teléfono</label>
                        <span class="dato-valor">{{ $alumno->telefono }}</span>
                    </div>
                </div>

                <div class="carrera-info-expediente">
                    <div class="logo-circular-carrera" style="width: 80px; height: 80px; overflow: hidden; border-radius: 50%;">
                        @if($alumno->carrera && $alumno->carrera->logo)
                            <img src="{{ asset($alumno->carrera->logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <img src="{{ asset('img/carreras/default.png') }}" alt="Sin logo">
                        @endif
                    </div>
                    <span class="logo-texto-carrera">{{ $alumno->carrera->nombre ?? 'Sin Carrera' }}</span>
                </div>

        <!-- Sección de tutorías -->
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
                            <td><button class="btn-editar-tutoria">Editar</button> <button class="btn-eliminar-tutoria">Eliminar</button></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para editar alumno -->
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

    <!-- Modal para agregar/editar tutoría -->
    <div id="modalTutoria" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTutoriaTitulo">Agregar tutoría</h3>
                <span class="modal-close" id="closeModalTutoria">&times;</span>
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
                    <textarea id="notasTutoria" rows="3" placeholder="Observaciones adicionales..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarTutoria">Cancelar</button>
                <button class="btn-guardar" id="guardarTutoria">Guardar tutoría</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flecha de regreso
        const backButton = document.getElementById('backButton');
        if (backButton) {
            backButton.addEventListener('click', function() {
                window.location.href = '/dashboard/admin/carrera';
            });
        }

        // Modal Editar Alumno
        const modalEditar = document.getElementById('modalEditarAlumno');
        const btnEditar = document.getElementById('btnEditarAlumno');
        const closeModalEditar = document.getElementById('closeModalEditar');
        const cancelarEditar = document.getElementById('cancelarEditar');

        if (btnEditar) {
            btnEditar.onclick = function() {
                modalEditar.style.display = 'flex';
            };
        }

        function cerrarModalEditar() {
            modalEditar.style.display = 'none';
        }

        if (closeModalEditar) closeModalEditar.onclick = cerrarModalEditar;
        if (cancelarEditar) cancelarEditar.onclick = cerrarModalEditar;

        // Guardar cambios alumno
        const guardarEditar = document.getElementById('guardarEditar');
        if (guardarEditar) {
            guardarEditar.onclick = function() {
                document.getElementById('datoNombre').textContent = document.getElementById('editNombre').value;
                document.getElementById('datoApellidos').textContent = document.getElementById('editApellidos').value;
                document.getElementById('datoMatricula').textContent = document.getElementById('editMatricula').value;
                document.getElementById('datoCarrera').textContent = document.getElementById('editCarrera').value;
                document.getElementById('datoGrupo').textContent = document.getElementById('editGrupo').value;
                document.getElementById('datoCURP').textContent = document.getElementById('editCURP').value;
                document.getElementById('datoEdad').textContent = document.getElementById('editEdad').value ? document.getElementById('editEdad').value + ' años' : '';
                document.getElementById('datoSexo').textContent = document.getElementById('editSexo').value;
                
                const fecha = document.getElementById('editFechaNac').value;
                if (fecha) {
                    const partes = fecha.split('-');
                    const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                    document.getElementById('datoFechaNac').textContent = partes[2] + ' de ' + meses[parseInt(partes[1]) - 1] + ' de ' + partes[0];
                }
                
                document.getElementById('datoCorreo').textContent = document.getElementById('editCorreo').value;
                document.getElementById('datoTelefono').textContent = document.getElementById('editTelefono').value;
                
                const nombre = document.getElementById('editNombre').value;
                const apellidos = document.getElementById('editApellidos').value;
                const iniciales = (nombre ? nombre.charAt(0) : '') + (apellidos ? apellidos.charAt(0) : '');
                document.querySelector('.avatar-iniciales-grande').textContent = iniciales.toUpperCase();
                
                alert('Alumno actualizado correctamente');
                cerrarModalEditar();
            };
        }

        // Eliminar alumno
        const btnEliminar = document.getElementById('btnEliminarAlumno');
        if (btnEliminar) {
            btnEliminar.onclick = function() {
                if (confirm('¿Estás seguro de eliminar este alumno?')) {
                    alert('Alumno eliminado');
                }
            };
        }

        // Descargar expediente
        const btnDescargarExpediente = document.getElementById('btnDescargarExpediente');
        if (btnDescargarExpediente) {
            btnDescargarExpediente.onclick = function() {
                alert('Descargar expediente del alumno');
            };
        }

        // Cambiar foto
        const btnCambiarFoto = document.getElementById('btnCambiarFoto');
        if (btnCambiarFoto) {
            btnCambiarFoto.onclick = function() {
                document.getElementById('editFoto').click();
            };
        }

        document.getElementById('editFoto')?.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const avatar = document.querySelector('.avatar-grande');
                    avatar.innerHTML = `<img src="${event.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Modal Tutorías
        const modalTutoria = document.getElementById('modalTutoria');
        const btnAgregarTutoria = document.getElementById('btnAgregarTutoria');
        const closeModalTutoria = document.getElementById('closeModalTutoria');
        const cancelarTutoria = document.getElementById('cancelarTutoria');
        const guardarTutoria = document.getElementById('guardarTutoria');
        let editandoTutoria = false;
        let filaEditando = null;

        if (btnAgregarTutoria) {
            btnAgregarTutoria.onclick = function() {
                editandoTutoria = false;
                filaEditando = null;
                document.getElementById('modalTutoriaTitulo').textContent = 'Agregar tutoría';
                document.getElementById('fechaTutoria').value = '';
                document.getElementById('temaTutoria').value = '';
                document.getElementById('notasTutoria').value = '';
                modalTutoria.style.display = 'flex';
            };
        }

        function cerrarModalTutoria() {
            modalTutoria.style.display = 'none';
        }

        if (closeModalTutoria) closeModalTutoria.onclick = cerrarModalTutoria;
        if (cancelarTutoria) cancelarTutoria.onclick = cerrarModalTutoria;

        if (guardarTutoria) {
            guardarTutoria.onclick = function() {
                const fecha = document.getElementById('fechaTutoria').value;
                const tema = document.getElementById('temaTutoria').value;
                const notas = document.getElementById('notasTutoria').value;
                
                if (fecha && tema) {
                    if (editandoTutoria && filaEditando) {
                        filaEditando.cells[0].textContent = fecha;
                        filaEditando.cells[1].textContent = tema;
                        filaEditando.cells[2].textContent = notas;
                        alert('Tutoría actualizada correctamente');
                    } else {
                        const tbody = document.getElementById('tutoriasBody');
                        const row = tbody.insertRow();
                        row.innerHTML = `
                            <td>${fecha}</td>
                            <td>${tema}</td>
                            <td>${notas}</td>
                            <td><button class="btn-editar-tutoria">Editar</button> <button class="btn-eliminar-tutoria">Eliminar</button></td>
                        `;
                        alert('Tutoría agregada correctamente');
                    }
                    cerrarModalTutoria();
                } else {
                    alert('Por favor complete la fecha y el tema');
                }
            };
        }

        // Editar y eliminar tutorías
        document.getElementById('tutoriasBody')?.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-editar-tutoria')) {
                const row = e.target.closest('tr');
                filaEditando = row;
                editandoTutoria = true;
                document.getElementById('modalTutoriaTitulo').textContent = 'Editar tutoría';
                document.getElementById('fechaTutoria').value = row.cells[0].textContent;
                document.getElementById('temaTutoria').value = row.cells[1].textContent;
                document.getElementById('notasTutoria').value = row.cells[2].textContent;
                modalTutoria.style.display = 'flex';
            }
            
            if (e.target.classList.contains('btn-eliminar-tutoria')) {
                if (confirm('¿Estás seguro de eliminar esta tutoría?')) {
                    const row = e.target.closest('tr');
                    row.remove();
                    alert('Tutoría eliminada');
                }
            }
        });

        // Cerrar modales al hacer clic fuera
        window.onclick = function(e) {
            if (e.target === modalEditar) cerrarModalEditar();
            if (e.target === modalTutoria) cerrarModalTutoria();
        };
    });
</script>
@endpush