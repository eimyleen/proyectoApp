{{-- 
    ============================================================
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
--}}

@extends('layouts.dashboard')

{{-- ======================================================
     SECCIONES QUE LLENAN EL LAYOUT
     ====================================================== --}}

{{-- Título de la página (aparece en la pestaña del navegador) --}}
@section('title', 'Mi Expediente - Alumno')

{{-- Rol del usuario que se muestra en el badge del header --}}
@section('user-role', 'Alumno')

{{-- 
    INICIALES DEL AVATAR
    - Si existe el alumno, toma la primera letra del nombre y apellido
    - strtoupper() las convierte en mayúsculas
    - substr($alumno->nombre, 0, 1) toma la primera letra del nombre
    - substr($alumno->apellido, 0, 1) toma la primera letra del apellido
    - Ejemplo: "Carlos Martínez" → "CM"
--}}
@section('avatar-iniciales', isset($alumno) ? strtoupper(substr($alumno->nombre, 0, 1)) . strtoupper(substr($alumno->apellido, 0, 1)) : 'U')

{{-- 
    NOMBRE COMPLETO
    - Si existe el alumno, muestra "Nombre Apellido"
    - Si no, muestra "Usuario" por defecto
--}}
@section('nombre-completo', isset($alumno) ? $alumno->nombre . ' ' . $alumno->apellido : 'Usuario')

{{-- Mensaje de bienvenida en el header --}}
@section('welcome-message', 'Mi Expediente')

{{-- Subtítulo descriptivo --}}
@section('subtitle', 'Aquí puedes consultar tu información personal')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
    El botón aparece porque se define @section('back-button')
--}}
@section('back-button')
    <!-- Botón de regreso visible -->
@endsection

{{-- ======================================================
     CSS ADICIONAL
     ====================================================== --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

{{-- ======================================================
     CONTENIDO PRINCIPAL
     ====================================================== --}}
@section('content')
    {{-- 
        CONTENEDOR PRINCIPAL CON FLEX
        Organiza en dos columnas:
        - Izquierda: Botones laterales (Expediente activo, Calificaciones, Logo carrera)
        - Derecha: Contenido del expediente (datos + documentos)
    --}}
    <div class="contenido-con-botones">
        
        {{-- ======================================================
             BOTONES LATERALES (Columna izquierda)
             ====================================================== 
             - "Expediente" tiene la clase 'active' porque estamos en esta sección
             - "Calificaciones" redirige a la vista de calificaciones
             - "Logo de carrera" muestra el logo circular de la carrera del alumno
        --}}
        <div class="botones-laterales">
            
            {{-- Botón EXPEDIENTE (activo) --}}
            <button class="btn-expediente active">Expediente</button>
            
            {{-- Botón CALIFICACIONES (inactivo) --}}
            <button class="btn-calificaciones">Calificaciones</button>
            
            {{-- ==================================================
                 LOGO CIRCULAR DE LA CARRERA
                 ================================================== 
                 Muestra el logo de la carrera del alumno.
                 Si no hay logo, muestra un espacio vacío.
                 El logo se guarda en storage/app/public/
            --}}
            <div class="carrera-logo">
                <div class="logo-circular">
                    @if(isset($carrera->logo))
                        {{-- 
                            asset('storage/' . $carrera->logo) 
                            Busca el logo en storage/app/public/
                            Ejemplo: storage/app/public/logos/ingenieria.png
                        --}}
                        <img src="{{ asset('storage/' . $carrera->logo) }}" alt="{{ $carrera->nombre }}">
                    @else
                        {{-- Espacio vacío si no hay logo --}}
                    @endif
                </div>
                <span class="logo-texto">Logo de la carrera</span>
            </div>
        </div>

        {{-- ======================================================
             CONTENIDO DEL EXPEDIENTE (Columna derecha)
             ====================================================== --}}
        <div class="contenido-principal">
            
            {{-- ==================================================
                 DATOS PERSONALES
                 ================================================== 
                 Contenedor blanco con sombra que agrupa:
                 - Foto de perfil (avatar grande)
                 - Grid con todos los datos personales del alumno
            --}}
            <div class="datos-container">
                
                {{-- ==============================================
                     FOTO DE PERFIL
                     ============================================== 
                     - Avatar grande: Muestra la foto del alumno o sus iniciales
                     - Botón "Subir foto": Permite cambiar la foto de perfil
                --}}
                <div class="perfil-section">
                    <div class="foto-perfil">
                        
                        {{-- 
                            AVATAR GRANDE
                            Si el alumno tiene foto, la muestra.
                            Si no, muestra las iniciales en un círculo con gradiente.
                        --}}
                        <div class="avatar-grande">
                            @if(isset($alumno->foto))
                                {{-- Si hay foto, la muestra --}}
                                <img src="{{ asset('storage/' . $alumno->foto) }}" alt="Foto" class="foto-perfil-img">
                            @else
                                {{-- Si no hay foto, muestra iniciales --}}
                                <span class="avatar-iniciales-grande">
                                    {{ isset($alumno) ? strtoupper(substr($alumno->nombre, 0, 1)) . strtoupper(substr($alumno->apellido, 0, 1)) : 'U' }}
                                </span>
                            @endif
                        </div>
                        
                        {{-- 
                            BOTÓN SUBIR FOTO
                            Tiene un icono (subir.png) y texto.
                            Al hacer clic, debería abrir un selector de archivos.
                        --}}
                        <button class="btn-subir-foto">
                            <img src="{{ asset('img/subir.png') }}" alt="Subir" class="btn-icon">
                            Subir foto
                        </button>
                    </div>
                </div>

                {{-- ==============================================
                     TÍTULO DEL EXPEDIENTE
                     ============================================== --}}
                <h3 class="datos-titulo">Expediente Personal</h3>

                {{-- ==============================================
                     GRID DE DATOS PERSONALES
                     ============================================== 
                     Muestra los datos en 2 columnas en escritorio
                     y 1 columna en móvil (responsive).
                     
                     Cada campo tiene:
                     - label: Nombre del campo (en mayúsculas y gris)
                     - valor: El dato del alumno (en negrita y oscuro)
                     
                     NOTA: Los datos se llenan desde el controlador
                     con la variable $alumno.
                --}}
                <div class="datos-grid">
                    
                    {{-- Nombre --}}
                    <div class="dato-item">
                        <label>Nombre</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->nombre : '      ' }}</span>
                    </div>
                    
                    {{-- Apellidos --}}
                    <div class="dato-item">
                        <label>Apellidos</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->apellido : '      ' }}</span>
                    </div>
                    
                    {{-- Carrera (relación con modelo Carrera) --}}
                    <div class="dato-item">
                        <label>Carrera</label>
                        <span class="dato-valor">{{ isset($alumno->carrera) ? $alumno->carrera->nombre : '      ' }}</span>
                    </div>
                    
                    {{-- Grupo --}}
                    <div class="dato-item">
                        <label>Grupo</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->grupo : '      ' }}</span>
                    </div>
                    
                    {{-- Matrícula --}}
                    <div class="dato-item">
                        <label>Matrícula</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->matricula : '      ' }}</span>
                    </div>
                    
                    {{-- CURP --}}
                    <div class="dato-item">
                        <label>CURP</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->curp : '      ' }}</span>
                    </div>
                    
                    {{-- Edad --}}
                    <div class="dato-item">
                        <label>Edad</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->edad : '      ' }}</span>
                    </div>
                    
                    {{-- Sexo --}}
                    <div class="dato-item">
                        <label>Sexo</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->sexo : '      ' }}</span>
                    </div>
                    
                    {{-- Fecha de nacimiento --}}
                    <div class="dato-item">
                        <label>Fecha de nacimiento</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->fecha_nacimiento : '      ' }}</span>
                    </div>
                    
                    {{-- Correo electrónico --}}
                    <div class="dato-item">
                        <label>Correo electrónico</label>
                        <span class="dato-valor">{{ isset($alumno) ? $alumno->email : '      ' }}</span>
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