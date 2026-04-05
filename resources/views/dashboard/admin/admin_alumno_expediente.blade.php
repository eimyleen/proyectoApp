@extends('layouts.dashboard')

@section('title', 'Administrador - Expediente del Alumno')
@section('user-role', 'Administrador')
@section('avatar-iniciales', 'JP')
@section('nombre-completo', 'Juan Pérez')
@section('welcome-message', 'Expediente del Alumno')
@section('subtitle', 'Consulta y edita la información del alumno')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/admin/carrera')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="expediente-container">
        <!-- Botones de acción -->
        <div class="acciones-superiores">
            <button class="btn-editar-perfil" id="btnEditarAlumno">
                <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> Editar alumno
            </button>
            <button class="btn-eliminar-perfil" id="btnEliminarAlumno">
                ✕ Eliminar alumno
            </button>
        </div>

        <!-- Foto de perfil -->
        <div class="perfil-section">
            <div class="foto-perfil">
                <div class="avatar-grande">
                    <span class="avatar-iniciales-grande">JP</span>
                </div>
                <button class="btn-subir-foto" id="btnCambiarFoto">Cambiar foto</button>
            </div>
        </div>

        <!-- Datos personales -->
        <h3 class="seccion-titulo">Datos personales</h3>
        <div class="datos-grid">
            <div class="dato-item">
                <label>Nombre(s)</label>
                <span class="dato-valor" id="datoNombre">Juan</span>
            </div>
            <div class="dato-item">
                <label>Apellidos</label>
                <span class="dato-valor" id="datoApellidos">Pérez García</span>
            </div>
            <div class="dato-item">
                <label>Matrícula</label>
                <span class="dato-valor" id="datoMatricula">UTN-2024-001</span>
            </div>
            <div class="dato-item">
                <label>Carrera</label>
                <span class="dato-valor" id="datoCarrera">Ingeniería en Alimentos</span>
            </div>
            <div class="dato-item">
                <label>Grupo</label>
                <span class="dato-valor" id="datoGrupo">8A</span>
            </div>
            <div class="dato-item">
                <label>CURP</label>
                <span class="dato-valor" id="datoCURP">PEGJ040101HDFRRR09</span>
            </div>
            <div class="dato-item">
                <label>Edad</label>
                <span class="dato-valor" id="datoEdad">21 años</span>
            </div>
            <div class="dato-item">
                <label>Sexo</label>
                <span class="dato-valor" id="datoSexo">Masculino</span>
            </div>
            <div class="dato-item">
                <label>Fecha de nacimiento</label>
                <span class="dato-valor" id="datoFechaNac">01 de enero de 2004</span>
            </div>
            <div class="dato-item">
                <label>Correo electrónico</label>
                <span class="dato-valor" id="datoCorreo">juan.perez@utnay.edu.mx</span>
            </div>
            <div class="dato-item">
                <label>Teléfono</label>
                <span class="dato-valor" id="datoTelefono">311-123-4567</span>
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
                    <input type="text" id="editNombre" value="Juan">
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" id="editApellidos" value="Pérez García">
                </div>
                <div class="form-group">
                    <label>Matrícula</label>
                    <input type="text" id="editMatricula" value="UTN-2024-001">
                </div>
                <div class="form-group">
                    <label>Carrera</label>
                    <input type="text" id="editCarrera" value="Ingeniería en Alimentos">
                </div>
                <div class="form-group">
                    <label>Grupo</label>
                    <input type="text" id="editGrupo" value="8A">
                </div>
                <div class="form-group">
                    <label>CURP</label>
                    <input type="text" id="editCURP" value="PEGJ040101HDFRRR09">
                </div>
                <div class="form-group">
                    <label>Edad</label>
                    <input type="text" id="editEdad" value="21">
                </div>
                <div class="form-group">
                    <label>Sexo</label>
                    <select id="editSexo">
                        <option value="Masculino" selected>Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha de nacimiento</label>
                    <input type="date" id="editFechaNac" value="2004-01-01">
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" id="editCorreo" value="juan.perez@utnay.edu.mx">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" id="editTelefono" value="311-123-4567">
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
                document.getElementById('datoNombre').textContent = document.getElementById('editNombre').value;
                document.getElementById('datoApellidos').textContent = document.getElementById('editApellidos').value;
                document.getElementById('datoMatricula').textContent = document.getElementById('editMatricula').value;
                document.getElementById('datoCarrera').textContent = document.getElementById('editCarrera').value;
                document.getElementById('datoGrupo').textContent = document.getElementById('editGrupo').value;
                document.getElementById('datoCURP').textContent = document.getElementById('editCURP').value;
                document.getElementById('datoEdad').textContent = document.getElementById('editEdad').value + ' años';
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
                const iniciales = nombre.charAt(0) + apellidos.charAt(0);
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