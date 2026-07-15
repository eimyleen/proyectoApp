<<<<<<< HEAD
@extends('layouts.dashboard')

@section('title', 'Mi Perfil - Administrador')
@section('user-role', 'Administrador')
@section('avatar-iniciales', 'AD')
@section('nombre-completo', 'Admin User')
@section('welcome-message', 'Mi Perfil')
@section('subtitle', 'Consulta y edita tu información personal')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="perfil-container">
        <!-- Botones de acción -->
        <div class="acciones-superiores">
            <button class="btn-editar-perfil" id="btnEditarPerfil">
                <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> Editar perfil
            </button>
            <button class="btn-cambiar-contrasena" id="btnCambiarContrasena">
                <img src="{{ asset('img/candado.png') }}" alt="Cambiar contraseña" class="btn-icono"> Cambiar contraseña
            </button>
        </div>

        <!-- Foto de perfil -->
        <div class="perfil-section">
            <div class="foto-perfil">
                <div class="avatar-grande">
                    <span class="avatar-iniciales-grande">AD</span>
                </div>
                <button class="btn-subir-foto" id="btnCambiarFoto">Cambiar foto</button>
            </div>
        </div>

        <!-- Datos personales -->
        <h3 class="perfil-titulo">Datos personales</h3>
        <div class="datos-grid">
            <div class="dato-item">
                <label>Nombre(s)</label>
                <span class="dato-valor" id="datoNombre">Admin</span>
            </div>
            <div class="dato-item">
                <label>Apellidos</label>
                <span class="dato-valor" id="datoApellidos">Usuario</span>
            </div>
            <div class="dato-item">
                <label>Correo electrónico</label>
                <span class="dato-valor" id="datoCorreo">admin@utnay.edu.mx</span>
            </div>
            <div class="dato-item">
                <label>Teléfono</label>
                <span class="dato-valor" id="datoTelefono">311-123-4567</span>
            </div>
            <div class="dato-item">
                <label>Rol</label>
                <span class="dato-valor" id="datoRol">Administrador</span>
            </div>
            <div class="dato-item">
                <label>Fecha de registro</label>
                <span class="dato-valor" id="datoFechaRegistro">01 de enero de 2024</span>
            </div>
        </div>
    </div>

    <!-- Modal para editar perfil -->
    <div id="modalEditarPerfil" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar perfil</h3>
                <span class="modal-close" id="closeModalEditar">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre(s)</label>
                    <input type="text" id="editNombre" value="Admin">
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" id="editApellidos" value="Usuario">
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" id="editCorreo" value="admin@utnay.edu.mx">
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

    <!-- Modal para cambiar contraseña -->
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
@endpush< ! - -   P e r f i l e s   -   M � d u l o   d e   e d i c i � n   d e   p e r f i l e s   d e   u s u a r i o   - - > 
 
 
=======
@extends('layouts.dashboard')

@section('title', __('messages.profile_admin_title'))
@section('welcome-message', __('messages.profile_welcome'))
@section('subtitle', __('messages.profile_admin_subtitle'))

@section('title', 'Mi Perfil - Administrador')
@section('welcome-message', 'Mi Perfil')
@section('subtitle', 'Consulta tu información personal')

@section('back-button')
    <!-- Botón de regresar -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="perfil-container">
        <div class="acciones-superiores">
            <button class="btn-editar-perfil" id="btnEditarPerfil">
                <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> {{ __('messages.btn_edit_profile') }}
            </button>
            <button class="btn-cambiar-contrasena" id="btnCambiarContrasena">
                <img src="{{ asset('img/candado.png') }}" alt="Cambiar contraseña" class="btn-icono"> {{ __('messages.btn_change_password') }}
            </button>
        </div>

        <div class="perfil-section">
            <div class="foto-perfil">
                <div class="avatar-grande">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" class="foto-perfil-img">
                            @else
                                <span class="avatar-iniciales-grande">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('perfil.foto.update') }}" method="POST" enctype="multipart/form-data" id="fotoForm">
                            @csrf
                            @method('PUT')
                            <input type="file" name="foto" id="inputFoto" style="display: none;" accept="image/*" onchange="document.getElementById('fotoForm').submit();">
                            <button type="button" class="btn-subir-foto" onclick="document.getElementById('inputFoto').click();">
                                {{ __('messages.btn_upload_photo') }}
                            </button>
                        </form>
            </div>
        </div>

        <h3 class="perfil-titulo">{{ __('messages.personal_data') }}</h3>
        <div class="datos-grid">
            <div class="dato-item">
                <label>{{ __('messages.field_firstname') }}</label>
                <span class="dato-valor" id="datoNombre">{{ Auth::user()->name }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.field_lastname') }}</label>
                <span class="dato-valor" id="datoApellidos">{{ Auth::user()->apellido }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.field_email') }}</label>
                <span class="dato-valor" id="datoCorreo">{{ Auth::user()->email }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.field_role') }}</label>
                <span class="dato-valor" id="datoRol">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
            <div class="dato-item">
                <label>{{ __('messages.field_member_since') }}</label>
                <span class="dato-valor" id="datoFechaRegistro">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div id="modalEditarPerfil" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_edit_profile') }}</h3>
                <span class="modal-close" id="closeModalEditar">&times;</span>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" enctype="multipart/form-data" id="formEditarPerfil">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('messages.field_firstname') }}</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_lastname') }}</label>
                        <input type="text" name="apellido" value="{{ Auth::user()->apellido }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_email') }}</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}">
                    </div>
                    <input type="file" id="editFoto" name="foto" style="display:none;" accept="image/*">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarEditar">{{ __('messages.btn_cancel') }}</button>
                <button class="btn-guardar" type="submit" form="formEditarPerfil">{{ __('messages.btn_save_changes') }}</button>
            </div>
        </div>
    </div>

    <div id="modalCambiarContrasena" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_change_password') }}</h3>
                <span class="modal-close" id="closeModalContrasena">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('messages.field_current_password') }}</label>
                    <input type="password" id="contrasenaActual" placeholder="{{ __('messages.placeholder_current_password') }}">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.field_new_password') }}</label>
                    <input type="password" id="nuevaContrasena" placeholder="{{ __('messages.placeholder_new_password') }}">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.field_confirm_password') }}</label>
                    <input type="password" id="confirmarContrasena" placeholder="{{ __('messages.placeholder_confirm_password') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarContrasena">{{ __('messages.btn_cancel') }}</button>
                <button class="btn-guardar" id="guardarContrasena">{{ __('messages.btn_save_changes') }}</button>
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
>>>>>>> origin/version_inicial
