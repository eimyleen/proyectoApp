@extends('layouts.dashboard')

@section('title', 'Mi Perfil - Administrador')
@section('welcome-message', 'Mi Perfil')
@section('subtitle', 'Consulta tu información personal')

@section('back-button')
    
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="perfil-container">
        <div class="acciones-superiores">
            <button class="btn-editar-perfil" id="btnEditarPerfil">
                <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> Editar perfil
            </button>
            <button class="btn-cambiar-contrasena" id="btnCambiarContrasena">
                <img src="{{ asset('img/candado.png') }}" alt="Cambiar contraseña" class="btn-icono"> Cambiar contraseña
            </button>
        </div>

        <div class="perfil-section">
            <div class="foto-perfil">
                <div class="avatar-grande">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                    @else
                        <span class="avatar-iniciales-grande">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <button class="btn-subir-foto" id="btnCambiarFoto">Cambiar foto</button>
            </div>
        </div>

        <h3 class="perfil-titulo">Datos personales</h3>
        <div class="datos-grid">
            <div class="dato-item">
                <label>Nombre(s)</label>
                <span class="dato-valor" id="datoNombre">{{ Auth::user()->name }}</span>
            </div>
            <div class="dato-item">
                <label>Apellidos</label>
                <span class="dato-valor" id="datoApellidos">{{ Auth::user()->apellido }}</span>
            </div>
            <div class="dato-item">
                <label>Correo electrónico</label>
                <span class="dato-valor" id="datoCorreo">{{ Auth::user()->email }}</span>
            </div>
            <div class="dato-item">
                <label>Rol</label>
                <span class="dato-valor" id="datoRol">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
            <div class="dato-item">
                <label>Miembro desde</label>
                <span class="dato-valor" id="datoFechaRegistro">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div id="modalEditarPerfil" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar perfil</h3>
                <span class="modal-close" id="closeModalEditar">&times;</span>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" enctype="multipart/form-data" id="formEditarPerfil">
                    @csrf
                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" name="apellido" value="{{ Auth::user()->apellido }}">
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}">
                    </div>
                    <input type="file" id="editFoto" name="foto" style="display:none;" accept="image/*">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarEditar">Cancelar</button>
                <button class="btn-guardar" type="submit" form="formEditarPerfil">Guardar cambios</button>
            </div>
        </div>
    </div>

    <div id="modalCambiarContrasena" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cambiar contraseña</h3>
                <span class="modal-close" id="closeModalContrasena">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Contraseña actual</label>
                    <input type="password" id="contrasenaActual" placeholder="Ingrese su contraseña actual">
                </div>
                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" id="nuevaContrasena" placeholder="Ingrese su nueva contraseña">
                </div>
                <div class="form-group">
                    <label>Confirmar nueva contraseña</label>
                    <input type="password" id="confirmarContrasena" placeholder="Confirme su nueva contraseña">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarContrasena">Cancelar</button>
                <button class="btn-guardar" id="guardarContrasena">Guardar cambios</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Editar Perfil
        const modalEditar = document.getElementById('modalEditarPerfil');
        const btnEditar = document.getElementById('btnEditarPerfil');
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

        // Guardar cambios perfil
        const guardarEditar = document.getElementById('guardarEditar');
        if (guardarEditar) {
            guardarEditar.onclick = function() {
                document.getElementById('datoNombre').textContent = document.getElementById('editNombre').value;
                document.getElementById('datoApellidos').textContent = document.getElementById('editApellidos').value;
                document.getElementById('datoCorreo').textContent = document.getElementById('editCorreo').value;
                document.getElementById('datoTelefono').textContent = document.getElementById('editTelefono').value;
                
                const nombre = document.getElementById('editNombre').value;
                const apellidos = document.getElementById('editApellidos').value;
                const iniciales = (nombre ? nombre.charAt(0) : '') + (apellidos ? apellidos.charAt(0) : '');
                document.querySelector('.avatar-iniciales-grande').textContent = iniciales.toUpperCase();
                
                alert('Perfil actualizado correctamente');
                cerrarModalEditar();
            };
        }

        // Modal Cambiar Contraseña
        const modalContrasena = document.getElementById('modalCambiarContrasena');
        const btnContrasena = document.getElementById('btnCambiarContrasena');
        const closeModalContrasena = document.getElementById('closeModalContrasena');
        const cancelarContrasena = document.getElementById('cancelarContrasena');

        if (btnContrasena) {
            btnContrasena.onclick = function() {
                modalContrasena.style.display = 'flex';
            };
        }

        function cerrarModalContrasena() {
            modalContrasena.style.display = 'none';
            document.getElementById('contrasenaActual').value = '';
            document.getElementById('nuevaContrasena').value = '';
            document.getElementById('confirmarContrasena').value = '';
        }

        if (closeModalContrasena) closeModalContrasena.onclick = cerrarModalContrasena;
        if (cancelarContrasena) cancelarContrasena.onclick = cerrarModalContrasena;

        // Guardar contraseña
        const guardarContrasena = document.getElementById('guardarContrasena');
        if (guardarContrasena) {
            guardarContrasena.onclick = function() {
                const nueva = document.getElementById('nuevaContrasena').value;
                const confirmar = document.getElementById('confirmarContrasena').value;
                if (nueva && nueva === confirmar) {
                    alert('Contraseña actualizada correctamente');
                    cerrarModalContrasena();
                } else {
                    alert('Las contraseñas no coinciden');
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

        // Cerrar modales al hacer clic fuera
        window.onclick = function(e) {
            if (e.target === modalEditar) cerrarModalEditar();
            if (e.target === modalContrasena) cerrarModalContrasena();
        };
    });
</script>
@endpush