{{-- 
    ============================================================
    ADMIN - PERFIL DE ADMINISTRADOR
    ============================================================
    Esta vista muestra el perfil del administrador con:
    - Foto de perfil (con opción para subir)
    - Datos personales (Nombre, Apellidos, Email, Rol, Fecha de registro)
    - Botones para editar perfil y cambiar contraseña
    - Modales para editar perfil y cambiar contraseña
    
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
@section('title', __('messages.profile_admin_title'))
@section('welcome-message', __('messages.profile_welcome'))
@section('subtitle', __('messages.profile_admin_subtitle'))

@section('title', 'Mi Perfil - Administrador')
@section('welcome-message', 'Mi Perfil')
@section('subtitle', 'Consulta tu información personal')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
--}}
@section('back-button')
    <!-- Botón de regresar -->
@endsection

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    
    {{-- 
        CONTENEDOR PRINCIPAL DEL PERFIL
        Fondo blanco con sombra y bordes redondeados.
    --}}
    <div class="perfil-container">
        
        {{-- ======================================================
             ACCIONES SUPERIORES
             ====================================================== 
             Botones para:
             - Editar Perfil: Abre modal para editar datos personales
             - Cambiar Contraseña: Abre modal para cambiar la contraseña
        --}}
        <div class="acciones-superiores">
            <button class="btn-editar-perfil" id="btnEditarPerfil">
                <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> 
                {{ __('messages.btn_edit_profile') }}
            </button>
            <button class="btn-cambiar-contrasena" id="btnCambiarContrasena">
                <img src="{{ asset('img/candado.png') }}" alt="Cambiar contraseña" class="btn-icono"> 
                {{ __('messages.btn_change_password') }}
            </button>
        </div>

        {{-- ======================================================
             FOTO DE PERFIL
             ====================================================== 
             - Avatar grande: Muestra la foto del administrador o sus iniciales
             - Botón "Subir Foto": Permite cambiar la foto de perfil
               con un formulario que se envía automáticamente al seleccionar archivo.
        --}}
        <div class="perfil-section">
            <div class="foto-perfil">
                
                {{-- 
                    AVATAR GRANDE
                    Si el administrador tiene foto, la muestra.
                    Si no, muestra las iniciales en un círculo con gradiente.
                --}}
                <div class="avatar-grande">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" class="foto-perfil-img">
                    @else
                        <span class="avatar-iniciales-grande">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido, 0, 1)) }}
                        </span>
                    @endif
                </div>

                {{-- 
                    FORMULARIO PARA SUBIR FOTO
                    - Envía la foto con método PUT usando route('perfil.foto.update')
                    - El input file está oculto y se activa con el botón
                    - Al seleccionar un archivo, se envía automáticamente el formulario
                --}}
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

        {{-- ======================================================
             DATOS PERSONALES
             ====================================================== 
             Muestra los datos del administrador en un grid de 2 columnas.
             Cada campo tiene:
             - label: Nombre del campo (traducido)
             - valor: El dato del administrador desde Auth::user()
        --}}
        <h3 class="perfil-titulo">{{ __('messages.personal_data') }}</h3>
        
        <div class="datos-grid">
            {{-- Nombre --}}
            <div class="dato-item">
                <label>{{ __('messages.field_firstname') }}</label>
                <span class="dato-valor">{{ Auth::user()->name }}</span>
            </div>
            
            {{-- Apellidos --}}
            <div class="dato-item">
                <label>{{ __('messages.field_lastname') }}</label>
                <span class="dato-valor">{{ Auth::user()->apellido }}</span>
            </div>
            
            {{-- Correo electrónico --}}
            <div class="dato-item">
                <label>{{ __('messages.field_email') }}</label>
                <span class="dato-valor">{{ Auth::user()->email }}</span>
            </div>
            
            {{-- Rol --}}
            <div class="dato-item">
                <label>{{ __('messages.field_role') }}</label>
                <span class="dato-valor">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
            
            {{-- Fecha de registro --}}
            <div class="dato-item">
                <label>{{ __('messages.field_member_since') }}</label>
                <span class="dato-valor">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- 
        ======================================================
        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE GUARDAR PERFIL
        ======================================================
        Se agregó ID "formEditarPerfil" al formulario y una
        alerta de confirmación antes de ejecutar la acción.
        
        También se agregó validación de campos obligatorios.
        
        El formulario mantiene el action="#" original.
        ======================================================
    --}}
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
                        <input type="text" name="name" id="editNombre" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_lastname') }}</label>
                        <input type="text" name="apellido" id="editApellidos" value="{{ Auth::user()->apellido }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_email') }}</label>
                        <input type="email" name="email" id="editCorreo" value="{{ Auth::user()->email }}">
                    </div>
                    <input type="file" id="editFoto" name="foto" style="display:none;" accept="image/*">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarEditar">{{ __('messages.btn_cancel') }}</button>
                <button class="btn-guardar" type="button" id="guardarEditar">{{ __('messages.btn_save_changes') }}</button>
            </div>
        </div>
    </div>

    {{-- 
        ======================================================
        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE CAMBIAR CONTRASEÑA
        ======================================================
        Se agregó IDs a los campos y una alerta de confirmación
        antes de ejecutar la acción.
        
        También se agregó validación de campos obligatorios
        y confirmación de contraseñas.
        ======================================================
    --}}
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

{{-- 
    ======================================================
    SCRIPTS ADICIONALES
    ======================================================
    NOTA: FUNCIONALIDADES AGREGADAS
    ======================================================
    1. Confirmación antes de guardar cambios de perfil.
    2. Confirmación antes de cambiar contraseña.
    3. Validación de campos obligatorios en perfil.
    4. Validación de campos obligatorios y coincidencia en contraseña.
    ======================================================
--}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        1. Modal Editar Perfil (abrir/cerrar/guardar)
        2. Modal Cambiar Contraseña (abrir/cerrar/guardar)
        3. Cerrar modales al hacer clic fuera
        4. Confirmación antes de guardar perfil (NUEVO)
        5. Confirmación antes de cambiar contraseña (NUEVO)
        6. Validación de campos obligatorios (NUEVO)
    --}}

    document.addEventListener('DOMContentLoaded', function() {
        
        // ==============================================
        // 1. MODAL EDITAR PERFIL
        // ==============================================
        const modalEditar = document.getElementById('modalEditarPerfil');
        const btnEditar = document.getElementById('btnEditarPerfil');
        const closeModalEditar = document.getElementById('closeModalEditar');
        const cancelarEditar = document.getElementById('cancelarEditar');

        // Abrir modal
        if (btnEditar) {
            btnEditar.onclick = function() {
                modalEditar.style.display = 'flex';
            };
        }

        // Cerrar modal
        function cerrarModalEditar() {
            modalEditar.style.display = 'none';
        }

        if (closeModalEditar) closeModalEditar.onclick = cerrarModalEditar;
        if (cancelarEditar) cancelarEditar.onclick = cerrarModalEditar;

        // ==============================================
        // 2. MODAL CAMBIAR CONTRASEÑA
        // ==============================================
        const modalContrasena = document.getElementById('modalCambiarContrasena');
        const btnContrasena = document.getElementById('btnCambiarContrasena');
        const closeModalContrasena = document.getElementById('closeModalContrasena');
        const cancelarContrasena = document.getElementById('cancelarContrasena');

        // Abrir modal
        if (btnContrasena) {
            btnContrasena.onclick = function() {
                modalContrasena.style.display = 'flex';
            };
        }

        // Cerrar modal
        function cerrarModalContrasena() {
            modalContrasena.style.display = 'none';
            document.getElementById('contrasenaActual').value = '';
            document.getElementById('nuevaContrasena').value = '';
            document.getElementById('confirmarContrasena').value = '';
        }

        if (closeModalContrasena) closeModalContrasena.onclick = cerrarModalContrasena;
        if (cancelarContrasena) cancelarContrasena.onclick = cerrarModalContrasena;

        // ==============================================
        // 3. CERRAR MODALES AL HACER CLIC FUERA
        // ==============================================
        window.onclick = function(e) {
            if (e.target === modalEditar) cerrarModalEditar();
            if (e.target === modalContrasena) cerrarModalContrasena();
        };

        // ==============================================
        // 4. CONFIRMACIÓN DE GUARDAR PERFIL (NUEVO)
        // ==============================================
        const guardarEditar = document.getElementById('guardarEditar');
        if (guardarEditar) {
            guardarEditar.addEventListener('click', function() {
                const nombre = document.getElementById('editNombre').value.trim();
                const apellidos = document.getElementById('editApellidos').value.trim();
                const correo = document.getElementById('editCorreo').value.trim();
                
                if (!nombre || !apellidos || !correo) {
                    alertaInfo(
                        'Campos incompletos',
                        'Debes completar todos los campos obligatorios del formulario.'
                    );
                    return;
                }
                
                confirmarAccion(
                    'Guardar cambios',
                    '¿Estás seguro de que quieres guardar los cambios en tu perfil?',
                    'Guardar',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Funcionalidad pendiente',
                            'La edición del perfil se implementará posteriormente.'
                        );
                        cerrarModalEditar();
                    }
                });
            });
        }

        // ==============================================
        // 5. CONFIRMACIÓN DE CAMBIAR CONTRASEÑA (NUEVO)
        // ==============================================
        const guardarContrasena = document.getElementById('guardarContrasena');
        if (guardarContrasena) {
            guardarContrasena.addEventListener('click', function() {
                const actual = document.getElementById('contrasenaActual').value.trim();
                const nueva = document.getElementById('nuevaContrasena').value.trim();
                const confirmar = document.getElementById('confirmarContrasena').value.trim();
                
                if (!actual || !nueva || !confirmar) {
                    alertaInfo(
                        'Campos incompletos',
                        'Debes completar todos los campos del formulario.'
                    );
                    return;
                }
                
                if (nueva !== confirmar) {
                    alertaInfo(
                        'Contraseñas no coinciden',
                        'La nueva contraseña y la confirmación no coinciden.'
                    );
                    return;
                }
                
                if (nueva.length < 8) {
                    alertaInfo(
                        'Contraseña muy corta',
                        'La contraseña debe tener al menos 8 caracteres.'
                    );
                    return;
                }
                
                confirmarAccion(
                    'Cambiar contraseña',
                    '¿Estás seguro de que quieres cambiar tu contraseña?',
                    'Cambiar',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        alertaInfo(
                            'Funcionalidad pendiente',
                            'El cambio de contraseña se implementará posteriormente.'
                        );
                        cerrarModalContrasena();
                    }
                });
            });
        }
    });
</script>
@endpush