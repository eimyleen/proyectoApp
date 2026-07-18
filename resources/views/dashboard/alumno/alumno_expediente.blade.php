/* ============================================================
   ALUMNO - EXPEDIENTE
   ============================================================
   Esta vista muestra el expediente completo del alumno con:
   - Foto de perfil (con opción para subir)
   - Datos personales (Nombre, Apellidos, Carrera, Grupo, etc.)
   - Sección de documentos (Acta, CURP, Certificado, Constancia)
   
   RELACIÓN CON OTRAS VISTAS:
   - Extiende el layout: layouts.dashboard
   - Usa los estilos de: dashboard_alumno.css
   - Botón de regreso: visible (back-button)
   - Comparte botones con: alumno y alumno_calificaciones
   ============================================================ 
*/

@extends('layouts.dashboard')

/* ======================================================
   TÍTULOS DE LA PÁGINA
   ====================================================== 
   El primero usa traducción (__()), el segundo es texto fijo.
   El que prevalece es el último definido.
   En este caso: "Mi Expediente - Alumno"
*/
@section('title', __('messages.title_my_record'))
@section('subtitle', __('messages.subtitle_record'))

@section('title', 'Mi Expediente - Alumno')

/* ======================================================
   CSS ADICIONAL
   ====================================================== 
   Estilos específicos para el módulo de alumno.
   Incluye: botones laterales, tablas, expediente y documentos.
*/
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

/* ======================================================
   BOTÓN DE REGRESO
   ====================================================== 
   Esta sección hace visible el botón de regreso en el header.
   El botón aparece porque se define @section('back-button')
*/
@section('back-button')
    <!-- Botón de regresar -->
@endsection

/* ======================================================
   CONTENIDO PRINCIPAL
   ====================================================== */
@section('content')

    /*
        CONTENEDOR PRINCIPAL CON FLEX
        Organiza en dos columnas:
        - Izquierda: Botones laterales (Expediente activo, Calificaciones, Logo carrera)
        - Derecha: Contenido del expediente (datos + documentos)
    */
    <div class="contenido-con-botones">
        
        /* ======================================================
             BOTONES LATERALES (Columna izquierda)
             ====================================================== 
             - "Expediente" tiene la clase 'active' porque estamos en esta sección
             - "Calificaciones" redirige a la vista de calificaciones
             - "Logo circular" muestra el logo de la carrera del alumno
        */
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
            
            /* 
                LOGO CIRCULAR DE LA CARRERA
                Muestra el logo de la carrera del alumno.
                Si no hay logo, muestra el logo de UTNay por defecto.
            */
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

        /* ======================================================
             CONTENIDO DEL EXPEDIENTE (Columna derecha)
             ====================================================== */
        <div class="contenido-principal">
            
            /* ==================================================
                 DATOS PERSONALES
                 ================================================== 
                 Contenedor blanco con sombra que agrupa:
                 - Foto de perfil (avatar grande)
                 - Grid con todos los datos personales del alumno
            */
            <div class="datos-container">
                
                /* ==============================================
                     FOTO DE PERFIL
                     ============================================== 
                     - Avatar grande: Muestra la foto del alumno o sus iniciales
                     - Botón "Subir Foto": Permite cambiar la foto de perfil
                       con un formulario que se envía automáticamente al seleccionar archivo.
                */
                <div class="perfil-section">
                    <div class="foto-perfil">
                        
                        /* 
                            AVATAR GRANDE
                            Si el alumno tiene foto, la muestra.
                            Si no, muestra las iniciales en un círculo con gradiente.
                        */
                        <div class="avatar-grande">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" class="foto-perfil-img">
                            @else
                                <span class="avatar-iniciales-grande">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        /* 
                            FORMULARIO PARA SUBIR FOTO
                            - Envía la foto con método PUT usando route('perfil.foto.update')
                            - El input file está oculto y se activa con el botón
                            - Al seleccionar un archivo, se envía automáticamente el formulario
                        */
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

                /* ==============================================
                     TÍTULO DEL EXPEDIENTE
                     ============================================== */
                <h3 class="datos-titulo">{{ __('messages.title_personal_record') }}</h3>

                /* ==============================================
                     GRID DE DATOS PERSONALES
                     ============================================== 
                     Muestra los datos en 2 columnas en escritorio
                     y 1 columna en móvil (responsive).
                     
                     Cada campo tiene:
                     - label: Nombre del campo (traducido)
                     - valor: El dato del alumno desde Auth::user()
                */
                <div class="datos-grid">
                    
                    /* Nombre */
                    <div class="dato-item">
                        <label>{{ __('messages.th_nombre') }}</label>
                        <span class="dato-valor">{{ Auth::user()->name }}</span>
                    </div>
                    
                    /* Apellidos */
                    <div class="dato-item">
                        <label>{{ __('messages.label_apellidos') }}</label>
                        <span class="dato-valor">{{ Auth::user()->apellido }}</span>
                    </div>
                    
                    /* Carrera (relación con modelo Carrera) */
                    <div class="dato-item">
                        <label>{{ __('messages.label_major') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->carrera->nombre ?? 'No asignada' }}</span>
                    </div>
                    
                    /* Grupo */
                    <div class="dato-item">
                        <label>{{ __('messages.label_group') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->grupo ?? 'N/A' }}</span>
                    </div>
                    
                    /* Matrícula */
                    <div class="dato-item">
                        <label>{{ __('messages.label_id_number') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->matricula ?? 'N/A' }}</span>
                    </div>
                    
                    /* CURP */
                    <div class="dato-item">
                       <label>{{ __('messages.label_curp') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->curp ?? 'N/A' }}</span>
                    </div>
                    
                    /* Edad */
                    <div class="dato-item">
                        <label>{{ __('messages.label_age') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->edad ?? 'N/A' }} años</span>
                    </div>
                    
                    /* Sexo */
                    <div class="dato-item">
                        <label>{{ __('messages.label_gender') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->sexo ?? 'N/A' }}</span>
                    </div>
                    
                    /* Fecha de nacimiento */
                    <div class="dato-item">
                        <label>{{ __('messages.label_birthdate') }}</label>
                        <span class="dato-valor">{{ Auth::user()->alumno->fecha_nacimiento ?? 'N/A' }}</span>
                    </div>
                    
                    /* Correo electrónico */
                    <div class="dato-item">
                        <label>{{ __('messages.th_email') }}</label>
                        <span class="dato-valor">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            /* ======================================================
                 SECCIÓN DE DOCUMENTOS
                 ====================================================== 
                 Muestra la lista de documentos requeridos para el alumno.
                 
                 DOCUMENTOS ACTUALES:
                 1. Acta de nacimiento
                 2. CURP
                 3. Certificado de bachillerato
                 4. Constancia de estudios
                 
                 NOTA: El estado "no-cargado" se muestra en gris.
                 Cuando el documento esté cargado, se puede cambiar la clase.
            */
            <div class="documentos-container">
                
                /* Título de la sección */
                <h3 class="documentos-titulo">Documentos</h3>
                
                /* ==============================================
                     DOCUMENTO 1: Acta de nacimiento
                     ============================================== */
                <div class="documento-item">
                    <div class="documento-info">
                        <span class="documento-nombre">Acta de nacimiento</span>
                        <span class="documento-estado no-cargado">Aún no has cargado este documento.</span>
                    </div>
                    <button class="btn-cargar-documento">
                        <img src="{{ asset('img/subir.png') }}" alt="Subir" class="btn-icon">
                        Cargar archivo
                    </button>
                </div>

                /* ==============================================
                     DOCUMENTO 2: CURP
                     ============================================== */
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

                /* ==============================================
                     DOCUMENTO 3: Certificado de bachillerato
                     ============================================== */
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

                /* ==============================================
                     DOCUMENTO 4: Constancia de estudios
                     ============================================== */
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