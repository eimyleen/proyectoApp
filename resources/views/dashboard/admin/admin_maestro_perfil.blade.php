@extends('layouts.dashboard')

@section('title', 'Administrador - Perfil del Maestro')

@section('subtitle', 'Consulta y edita la información del maestro')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/admin/carrera')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="perfil-container">
        <!-- Botones de acción -->
        <div class="acciones-superiores">
            <button class="btn-editar-perfil" id="btnEditarMaestro">
                <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> Editar maestro
            </button>
            <button class="btn-eliminar-perfil" id="btnEliminarMaestro">
                ✕ Eliminar maestro
            </button>
        </div>

        <!-- Foto de perfil -->
        <div class="perfil-section">
            <div class="foto-perfil">
                <div class="avatar-grande">
                    @if($maestro->user->foto)
                        <img src="{{ asset('storage/' . $maestro->user->foto) }}" alt="Foto" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                    @else
                        <span class="avatar-iniciales-grande">
                            {{ strtoupper(substr($maestro->user->name, 0, 1)) }}{{ strtoupper(substr($maestro->user->apellido, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <button class="btn-subir-foto" id="btnCambiarFoto">Cambiar foto</button>
            </div>
        </div>

        <!-- Datos personales -->
        <h3 class="perfil-seccion-titulo">Datos personales</h3>
        <div class="datos-grid">
        <div class="dato-item">
            <label>Nombre(s)</label>
            <span class="dato-valor" id="datoNombre">{{ $maestro->user->name }}</span>
        </div>
        <div class="dato-item">
            <label>Apellidos</label>
            <span class="dato-valor" id="datoApellidos">{{ $maestro->user->apellido }}</span>
        </div>
        <div class="dato-item">
            <label>Número de empleado</label>
            <span class="dato-valor" id="datoEmpleado">{{ $maestro->num_empleado }}</span>
        </div>
        <div class="dato-item">
            <label>RFC</label>
            <span class="dato-valor" id="datoRFC">{{ $maestro->rfc }}</span>
        </div>
        <div class="dato-item">
            <label>Edad</label>
            <span class="dato-valor" id="datoEdad">{{ $maestro->edad }} años</span>
        </div>
        <div class="dato-item">
            <label>Sexo</label>
            <span class="dato-valor" id="datoSexo">{{ $maestro->sexo }}</span>
        </div>
        <div class="dato-item">
            <label>Fecha de nacimiento</label>
            <span class="dato-valor" id="datoFechaNac">
                {{ \Carbon\Carbon::parse($maestro->fecha_nacimiento)->format('d/m/Y') }}
            </span>
        </div>
        <div class="dato-item">
            <label>Correo electrónico</label>
            <span class="dato-valor" id="datoCorreo">{{ $maestro->user->email }}</span>
        </div>
        <div class="dato-item">
            <label>Teléfono</label>
            <span class="dato-valor" id="datoTelefono">{{ $maestro->telefono }}</span>
        </div>
    </div>

        <!-- Carreras que imparte -->
        <h3 class="seccion-titulo">Carreras que imparte</h3>
        <div class="carreras-grid-perfil" id="carrerasContainer">
            <!-- Las carreras se cargarán dinámicamente -->
        </div>

        <!-- Grupo tutorado -->
        <h3 class="seccion-titulo">Grupo tutorado</h3>
        <div class="grupo-tutorado-info">
            <span class="grupo-nombre-perfil" id="grupoTutorado"></span>
        </div>
    </div>

    <!-- Modal para editar maestro (con un solo scroll) -->
    <div id="modalEditarMaestro" class="modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar maestro</h3>
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
                    <label>Número de empleado</label>
                    <input type="text" id="editEmpleado">
                </div>
                <div class="form-group">
                    <label>RFC</label>
                    <input type="text" id="editRFC">
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
                    <label>¿Es tutor?</label>
                    <select id="editEsTutor">
                        <option value="">Seleccionar</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Grupo tutorado</label>
                    <input type="text" id="editGrupoTutorado">
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

        // Modal Editar Maestro
        const modalEditar = document.getElementById('modalEditarMaestro');
        const btnEditar = document.getElementById('btnEditarMaestro');
        const closeModal = document.getElementById('closeModalEditar');
        const cancelar = document.getElementById('cancelarEditar');

        if (btnEditar) {
            btnEditar.onclick = function() {
                modalEditar.style.display = 'flex';
            };
        }

        function cerrarModal() {
            modalEditar.style.display = 'none';
        }

        if (closeModal) closeModal.onclick = cerrarModal;
        if (cancelar) cancelar.onclick = cerrarModal;

        window.onclick = function(e) {
            if (e.target === modalEditar) cerrarModal();
        };

        // Guardar cambios (actualiza la vista con los nuevos valores)
        const guardar = document.getElementById('guardarEditar');
        if (guardar) {
            guardar.onclick = function() {
                document.getElementById('datoNombre').textContent = document.getElementById('editNombre').value;
                document.getElementById('datoApellidos').textContent = document.getElementById('editApellidos').value;
                document.getElementById('datoEmpleado').textContent = document.getElementById('editEmpleado').value;
                document.getElementById('datoRFC').textContent = document.getElementById('editRFC').value;
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
                document.getElementById('grupoTutorado').textContent = document.getElementById('editGrupoTutorado').value;
                
                const esTutor = document.getElementById('editEsTutor').value === '1';
                const tutorBadge = document.getElementById('tutorBadge');
                if (tutorBadge) {
                    tutorBadge.style.display = esTutor ? 'inline-block' : 'none';
                }
                
                const nombre = document.getElementById('editNombre').value;
                const apellidos = document.getElementById('editApellidos').value;
                const iniciales = (nombre ? nombre.charAt(0) : '') + (apellidos ? apellidos.charAt(0) : '');
                document.querySelector('.avatar-iniciales-grande').textContent = iniciales.toUpperCase();
                
                alert('Maestro actualizado correctamente');
                cerrarModal();
            };
        }

        // Eliminar maestro
        const btnEliminar = document.getElementById('btnEliminarMaestro');
        if (btnEliminar) {
            btnEliminar.onclick = function() {
                if (confirm('¿Estás seguro de eliminar este maestro?')) {
                    alert('Maestro eliminado');
                }
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
    });
</script>
@endpush