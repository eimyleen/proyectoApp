@extends('layouts.dashboard')

@section('title', 'Administrador - Perfil del Alumno')
@section('user-role', 'Administrador')
@section('avatar-iniciales', 'SR')
@section('nombre-completo', 'Santiago Ramírez')
@section('welcome-message', 'Perfil del Alumno')
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
        <!-- Botones de acción -->
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
        
        <!-- Foto de perfil -->
        <div class="perfil-section">
            <div class="foto-perfil">
                <div class="avatar-grande">
                    <span class="avatar-iniciales-grande"></span>
                </div>
                <button class="btn-subir-foto" id="btnCambiarFoto">Cambiar foto</button>
            </div>
        </div>

        <!-- Datos personales -->
        <h3 class="perfil-titulo">Datos personales</h3>
        <div class="datos-grid">
            <div class="dato-item">
                <label>Nombre(s)</label>
                <span class="dato-valor" id="datoNombre"></span>
            </div>
            <div class="dato-item">
                <label>Apellidos</label>
                <span class="dato-valor" id="datoApellidos"></span>
            </div>
            <div class="dato-item">
                <label>Matrícula</label>
                <span class="dato-valor" id="datoMatricula"></span>
            </div>
            <div class="dato-item">
                <label>Carrera</label>
                <span class="dato-valor" id="datoCarrera"></span>
            </div>
            <div class="dato-item">
                <label>Grupo</label>
                <span class="dato-valor" id="datoGrupo"></span>
            </div>
            <div class="dato-item">
                <label>CURP</label>
                <span class="dato-valor" id="datoCURP"></span>
            </div>
            <div class="dato-item">
                <label>Edad</label>
                <span class="dato-valor" id="datoEdad"></span>
            </div>
            <div class="dato-item">
                <label>Sexo</label>
                <span class="dato-valor" id="datoSexo"></span>
            </div>
            <div class="dato-item">
                <label>Fecha de nacimiento</label>
                <span class="dato-valor" id="datoFechaNac"></span>
            </div>
            <div class="dato-item">
                <label>Correo electrónico</label>
                <span class="dato-valor" id="datoCorreo"></span>
            </div>
            <div class="dato-item">
                <label>Teléfono</label>
                <span class="dato-valor" id="datoTelefono"></span>
            </div>
        </div>

        <!-- Logo de la carrera -->
        <h3 class="seccion-titulo">Carrera</h3>
        <div class="carrera-info-expediente">
            <div class="logo-circular-carrera">
                <img src="{{ asset('img/carreras/ing_alimentos.png') }}" alt="Logo de la carrera">
            </div>
            <span class="logo-texto-carrera">Logo de la carrera</span>
        </div>
    </div>

    <!-- Modal para editar alumno -->
    <div id="modalEditarAlumno" class="modal-small">
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

        // Guardar cambios
        const guardar = document.getElementById('guardarEditar');
        if (guardar) {
            guardar.onclick = function() {
                // Actualizar valores en la vista
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
                
                // Actualizar iniciales del avatar
                const nombre = document.getElementById('editNombre').value;
                const apellidos = document.getElementById('editApellidos').value;
                const iniciales = (nombre ? nombre.charAt(0) : '') + (apellidos ? apellidos.charAt(0) : '');
                document.querySelector('.avatar-iniciales-grande').textContent = iniciales.toUpperCase();
                
                alert('Alumno actualizado correctamente');
                cerrarModal();
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