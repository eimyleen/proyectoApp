{{-- 
    ============================================================
    MAESTRO - PERFIL
    ============================================================
    Esta vista muestra el perfil completo del maestro con:
    - Foto de perfil (con opción para subir)
    - Datos personales (Nombre, Apellidos, Núm. empleado, RFC, etc.)
    - Carreras que imparte
    - Grupo(s) tutorado(s)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_maestro.css
    - Botón de regreso: visible (back-button)
    - Comparte estilos con: alumno_expediente
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
    En este caso: "Mi Perfil - Maestro"
--}}
@section('title', __('messages.profile_teacher_title'))
@section('welcome-message', __('messages.profile_welcome'))
@section('subtitle', __('messages.profile_subtitle'))

@section('title', 'Mi Perfil - Maestro')
@section('welcome-message', 'Mi Perfil')
@section('subtitle', 'Aquí puedes consultar y editar tu información personal')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
    El botón aparece porque se define @section('back-button')
--}}
@section('back-button')
    <!-- Botón de regresar -->
@endsection

{{-- 
    URL DE REGRESO
    Define a dónde redirige el botón de regreso.
    En este caso, al dashboard de maestro.
--}}
@section('back-url', '/dashboard/maestro')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    
    {{-- 
        CONTENEDOR PRINCIPAL DEL PERFIL
        Fondo blanco con sombra y bordes redondeados.
    --}}
    <div class="perfil-container">
        
        {{-- ======================================================
             FOTO DE PERFIL
             ====================================================== 
             - Avatar grande: Muestra la foto del maestro o sus iniciales
             - Botón "Subir Foto": Permite cambiar la foto de perfil
               con un formulario que se envía automáticamente al seleccionar archivo.
        --}}
        <div class="perfil-section">
            <div class="foto-perfil">
                
                {{-- 
                    AVATAR GRANDE
                    Si el maestro tiene foto, la muestra.
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
                        {{ __('messages.profile_upload_photo') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- ======================================================
             DATOS PERSONALES
             ====================================================== 
             Muestra los datos del maestro en un grid de 2 columnas.
             Cada campo tiene:
             - label: Nombre del campo (traducido)
             - valor: El dato del maestro desde Auth::user()
        --}}
        <h3 class="perfil-titulo">
            {{ __('messages.profile_personal_data') }}
            <span class="tutor-badge"></span>
        </h3>
        
        <div class="datos-grid">
            {{-- Nombre --}}
            <div class="dato-item">
                <label>{{ __('messages.profile_names') }}</label>
                <span class="dato-valor">{{ $user->name }}</span>
            </div>
            
            {{-- Apellidos --}}
            <div class="dato-item">
                <label>{{ __('messages.profile_last_names') }}</label>
                <span class="dato-valor">{{ $user->apellido }}</span>
            </div>
            
            {{-- Número de empleado --}}
            <div class="dato-item">
                <label>{{ __('messages.profile_employee_num') }}</label>
                <span class="dato-valor">{{ $maestro->num_empleado ?? 'N/A' }}</span>
            </div>
            
            {{-- RFC --}}
            <div class="dato-item">
                <label>{{ __('messages.profile_rfc') }}</label>
                <span class="dato-valor">{{ $maestro->rfc ?? 'N/A' }}</span>
            </div>
            
            {{-- Edad --}}
            <div class="dato-item">
                <label>{{ __('messages.profile_age') }}</label>
                <span class="dato-valor">{{ $maestro->edad ?? 'N/A' }} {{ __('messages.profile_years') }}</span>
            </div>
            
            {{-- Teléfono --}}
            <div class="dato-item">
                <label>{{ __('messages.profile_phone') }}</label>
                <span class="dato-valor">{{ $maestro->telefono ?? __('messages.profile_no_phone') }}</span>
            </div>
            
            {{-- Correo electrónico --}}
            <div class="dato-item">
                <label>{{ __('messages.profile_email') }}</label>
                <span class="dato-valor">{{ $user->email }}</span>
            </div>
        </div>

        {{-- ======================================================
             CARRERAS QUE IMPARTE
             ====================================================== 
             Sección para mostrar las carreras que el maestro imparte.
             Los datos se cargarán dinámicamente desde el backend.
        --}}
        <h3 class="seccion-titulo">{{ __('messages.profile_teaching_careers') }}</h3>
        <div class="carreras-grid tabla-calificaciones">
            {{-- Las carreras se cargarán dinámicamente desde el backend --}}
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Es Tutor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carreras as $carrera)
                        <tr>
                            <td>{{ $carrera->nombre ?? 'Sin Nombre' }}</td>
                            <td>No Es Tutor</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ======================================================
             GRUPO(S) TUTORADO(S)
             ====================================================== 
             Sección para mostrar los grupos que el maestro tutor.
             Los datos se cargarán dinámicamente desde el backend.
        --}}
        <h3 class="seccion-titulo">{{ __('messages.profile_tutored_groups') }}</h3>
        <div class="grupos-grid">
            {{-- Los grupos se cargarán dinámicamente desde el backend --}}
        </div>
    </div>
@endsection