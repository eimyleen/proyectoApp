{{-- 
    ============================================================
    ALUMNO - EXPEDIENTE
    ============================================================
    Esta vista muestra el expediente completo del alumno con:
    - Foto de perfil (con opción para subir)
    - Datos personales (Nombre, Apellidos, Carrera, Grupo, etc.)
    - Sección de documentos (Acta, CURP, Certificado, Constancia/estatico)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_alumno.css
    - Botón de regreso: visible (back-button)
    - Comparte botones con: alumno y alumno_calificaciones
    ============================================================ 
--}}

@extends('layouts.dashboard')

@section('title', __('messages.title_my_record'))
@section('subtitle', __('messages.subtitle_record'))

@section('title', 'Mi Expediente - Alumno')

{{-- ======================================================
     CSS ADICIONAL
     ====================================================== --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

@section('back-button')
    <!-- Botón de regresar -->
@endsection

@section('content')
    {{-- 
        CONTENEDOR PRINCIPAL CON FLEX
        Organiza en dos columnas:
        - Izquierda: Botones laterales (Expediente activo, Calificaciones, Logo carrera)
        - Derecha: Contenido del expediente (datos + documentos)
    --}}
        <div class="contenido-con-botones">
            <div class="botones-laterales">
            <a href="{{ route('alumno.expediente') }}" style="text-decoration: none;">
                <button class="btn-expediente {{ Request::routeIs('alumno.expediente') ? 'active' : '' }}">
                    {{ __('messages.btn_record') }}
                </button>
            </a>
            <a href="{{ route('alumno.calificaciones') }}" style="text-decoration: none;">
                <button class="btn-calificaciones {{ Request::routeIs('alumno.calificaciones') ? 'active' : '' }}">
                    {{ __('messages.btn_grades') }}
                </button>
            </a>
            
            {{-- Logo circular de la carrera --}}
            <div class="carrera-logo">
                <div class="logo-circular">
                    @if(Auth::user()->alumno->carrera->logo)
                        <img src="{{ asset(Auth::user()->alumno->carrera->logo) }}" 
                            alt="Logo {{ Auth::user()->alumno->carrera->nombre }}"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <img src="{{ asset('img/jaguar.png') }}" alt="UTNay">
                    @endif
                </div>
            </div>
        </div>

        <div class="contenido-principal">
            
            {{-- ==================================================
                 DATOS PERSONALES
                 ================================================== 
                 Contenedor blanco con sombra que agrupa:
                 - Foto de perfil (avatar grande)
                 - Grid con todos los datos personales del alumno
            --}}
            <div class="datos-container">
                <div class="perfil-section">
                    <div class="foto-perfil">
                        
                        {{-- 
                            AVATAR GRANDE
                            Si el alumno tiene foto, la muestra.
                            Si no, muestra las iniciales en un círculo con gradiente.
                        --}}
                        <div class="avatar-grande">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" class="foto-perfil-img">
                            @else
                                {{-- Si no hay foto, muestra iniciales --}}
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
                                Subir Foto
                            </button>
                        </form>
                    </div>
                </div>

                <h3 class="datos-titulo">{{ __('messages.title_personal_record') }}</h3>
                <div class="datos-grid">
                    
                    {{-- Nombre --}}
                    <div class="dato-item">
                        <label>{{ __('messages.th_nombre') }}</label>
                        <span class="dato-valor">{{ Auth::user()->name }}</span>
                    </div>
                    
                    {{-- Apellidos --}}
                    <div class="dato-item">
                        <label>{{ __('messages.label_apellidos') }}</label>
                        <span class="dato-valor">{{ Auth::user()->apellido }}</span>
                    </div>
                    
                    {{-- Carrera (relación con modelo Carrera) --}}
                    <div class="dato-item">
                        <label><label>{{ __('messages.label_major') }}</label></label>
                        <span class="dato-valor">{{ Auth::user()->alumno->carrera->nombre ?? 'No asignada' }}</span>
                    </div>
                    
                    {{-- Grupo --}}
                    <div class="dato-item">
                        <label>{{ __('messages.label_group') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->grupo ?? 'N/A' }}</span>
                    </div>
                    
                    {{-- Matrícula --}}
                    <div class="dato-item">
                        <label>{{ __('messages.label_id_number') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->matricula ?? 'N/A' }}</span>
                    </div>
                    
                    {{-- CURP --}}
                    <div class="dato-item">
                       <label>{{ __('messages.label_curp') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->curp ?? 'N/A' }}</span>
                    </div>
                    
                    {{-- Edad --}}
                    <div class="dato-item">
                        <label>{{ __('messages.label_age') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->edad ?? 'N/A' }} años</span>
                    </div>
                    
                    {{-- Sexo --}}
                    <div class="dato-item">
                        <label>{{ __('messages.label_gender') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->sexo ?? 'N/A' }}</span>
                    </div>
                    
                    {{-- Fecha de nacimiento --}}
                    <div class="dato-item">
                        <label>{{ __('messages.label_birthdate') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->fecha_nacimiento ?? 'N/A' }}</span>
                    </div>
                    
                    {{-- Correo electrónico --}}
                    <div class="dato-item">
                        <label>{{ __('messages.th_email') }}</label>
                        <span class="dato-valor">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            {{-- ======================================================
                 SECCIÓN DE DOCUMENTOS
                 ====================================================== 
                 Muestra la lista de documentos requeridos para el alumno.
                 Cada documento tiene:
                 - Nombre del documento
                 - Estado (mensaje de "no cargado")
                 - Botón "Cargar archivo" con icono
                 
                 DOCUMENTOS ACTUALES:
                 1. Acta de nacimiento
                 2. CURP
                 3. Certificado de bachillerato
                 4. Constancia de estudios
                 
                 NOTA: El estado "no-cargado" se muestra en gris.
                 Cuando el documento esté cargado, se puede cambiar la clase.
            --}}
            <div class="documentos-container">
                
                {{-- Título de la sección --}}
                <h3 class="documentos-titulo">Documentos</h3>
                
                {{-- ==============================================
                     DOCUMENTO 1: Acta de nacimiento
                     ============================================== --}}
                <div class="documento-item">
                    <div class="documento-info">
                        <span class="documento-nombre">Acta de nacimiento</span>
                        {{-- Estado: "no-cargado" lo muestra en gris --}}
                        <span class="documento-estado no-cargado">Aún no has cargado este documento.</span>
                    </div>
                    {{-- Botón con icono para cargar el documento --}}
                    <button class="btn-cargar-documento">
                        <img src="{{ asset('img/subir.png') }}" alt="Subir" class="btn-icon">
                        Cargar archivo
                    </button>
                </div>

                {{-- ==============================================
                     DOCUMENTO 2: CURP
                     ============================================== --}}
                <div class="documento-item">
                    <div class="documento-info">
                        <span class="documento-nombre">CURP</span>
                        <span class="documento-estado no-cargado">Aún no has cargado este documento.</span>
                    </div>
                    <button class="btn-cargar-documento">
                        <img src="{{ asset('img/subir.png') }}" alt="Subir" class="btn-icon">
                        Cargar archivo
                    </button>
                </div>

                {{-- ==============================================
                     DOCUMENTO 3: Certificado de bachillerato
                     ============================================== --}}
                <div class="documento-item">
                    <div class="documento-info">
                        <span class="documento-nombre">Certificado de bachillerato</span>
                        <span class="documento-estado no-cargado">Aún no has cargado este documento.</span>
                    </div>
                    <button class="btn-cargar-documento">
                        <img src="{{ asset('img/subir.png') }}" alt="Subir" class="btn-icon">
                        Cargar archivo
                    </button>
                </div>

                {{-- ==============================================
                     DOCUMENTO 4: Constancia de estudios
                     ============================================== --}}
                <div class="documento-item">
                    <div class="documento-info">
                        <span class="documento-nombre">Constancia de estudios</span>
                        <span class="documento-estado no-cargado">Aún no has cargado este documento.</span>
                    </div>
                    <button class="btn-cargar-documento">
                        <img src="{{ asset('img/subir.png') }}" alt="Subir" class="btn-icon">
                        Cargar archivo
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection