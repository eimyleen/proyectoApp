{{-- 
    ============================================================
    ADMIN - EXPEDIENTE DEL ALUMNO
    ============================================================
    Esta vista muestra el expediente completo de un alumno
    desde la perspectiva del administrador.
    Muestra:
    - Foto de perfil del alumno
    - Datos personales (Nombre, Matrícula, Carrera, Grupo, etc.)
    - Documentos del alumno con estado (subido/no subido)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_admin.css
    - Botón de regreso: visible (back-button)
    - Se conecta con: admin.carrera (detalle de carrera)
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
--}}
@section('title', __('messages.admin_alumno_expediente_title'))
@section('subtitle', __('messages.admin_alumno_expediente_subtitle'))

@section('title', 'Expediente del Alumno - Administrador')
@section('subtitle', 'Consulta la información personal del alumno')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
--}}
@section('back-button')
    <!-- Botón de regresar -->
@endsection

{{-- 
    URL DE REGRESO
    Define a dónde redirige el botón de regreso.
    En este caso, al detalle de la carrera del alumno.
--}}
@section('back-url', '/dashboard/admin')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    
    {{-- 
        CONTENEDOR PRINCIPAL DEL EXPEDIENTE
        Fondo blanco con sombra y bordes redondeados.
    --}}
    <div class="expediente-container admin-expediente">
        
        {{-- ======================================================
             BOTÓN GENERAR PDF
             ====================================================== 
             Permite descargar el expediente en formato PDF.
        --}}
        <div class="pdf-button-container">
            <button class="btn-generar-pdf" onclick="alert('Generando PDF del expediente...')">
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-pdf">
                {{ __('messages.expedient_generate_pdf') }}
            </button>
        </div>

        {{-- ======================================================
             FOTO DE PERFIL DEL ALUMNO
             ====================================================== 
             Muestra la foto del alumno o sus iniciales.
        --}}
        <div class="perfil-section">
            <div class="avatar-grande">
                @if($alumno->user->foto)
                    <img src="{{ asset('storage/' . $alumno->user->foto) }}" 
                         style="width:100%; height:100%; object-fit:cover;">
                @else
                    <span class="avatar-iniciales-grande">
                        {{ strtoupper(substr($alumno->user->name, 0, 1)) }}{{ strtoupper(substr($alumno->user->apellido, 0, 1)) }}
                    </span>
                @endif
            </div>
        </div>

        {{-- ======================================================
             DATOS PERSONALES DEL ALUMNO
             ====================================================== 
             Grid de 2 columnas con los datos del alumno.
        --}}
        <h3 class="seccion-titulo">{{ __('messages.expedient_personal_data') }}</h3>
        <div class="datos-grid">
            
            {{-- Nombre --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_name') }}</label>
                <span class="dato-valor">{{ $alumno->user->name }}</span>
            </div>
            
            {{-- Apellidos --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_last_names') }}</label>
                <span class="dato-valor">{{ $alumno->user->apellido }}</span>
            </div>

            {{-- Matrícula --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_id') }}</label>
                <span class="dato-valor">{{ $alumno->matricula }}</span>
            </div>

            {{-- Carrera --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_career') }}</label>
                <span class="dato-valor">{{ $alumno->carrera->nombre ?? 'N/A' }}</span>
            </div>

            {{-- Grupo --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_group') }}</label>
                <span class="dato-valor">{{ $alumno->grupo }}</span>
            </div>

            {{-- CURP --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_curp') }}</label>
                <span class="dato-valor">{{ $alumno->curp }}</span>
            </div>

            {{-- Edad --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_age') }}</label>
                <span class="dato-valor">{{ $alumno->edad }} {{ __('messages.profile_years') }}</span>
            </div>

            {{-- Sexo --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_gender') }}</label>
                <span class="dato-valor">{{ $alumno->sexo }}</span>
            </div>

            {{-- Fecha de nacimiento --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_birth_date') }}</label>
                <span class="dato-valor">{{ $alumno->fecha_nacimiento }}</span>
            </div>

            {{-- Correo electrónico --}}
            <div class="dato-item">
                <label>{{ __('messages.expedient_email') }}</label>
                <span class="dato-valor">{{ $alumno->user->email }}</span>
            </div>
        </div>

        {{-- ======================================================
             SECCIÓN DE DOCUMENTOS DEL ALUMNO
             ====================================================== 
             Muestra los documentos que el alumno ha subido.
             Cada documento tiene:
             - Nombre del documento
             - Estado (subido / no subido)
             - Botón "Ver documento" con icono de ojo (si está subido)
             - Guion (—) si no está subido
             
             NOTA: Los datos son estáticos para mostrar el diseño.
             El backend decidirá cómo implementar la lógica real.
        --}}
        <div class="documentos-container">
            <h3 class="seccion-titulo documentos-titulo">{{ __('messages.expedient_documents') }}</h3>
            
            {{-- Documento 1: Acta de nacimiento (subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Acta de nacimiento</span>
                    <span class="documento-estado subido">{{ __('messages.document_uploaded') }}</span>
                </div>
                <button class="btn-ver-documento" onclick="alert('Ver documento: Acta de nacimiento')">
                    <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                    {{ __('messages.document_view') }}
                </button>
            </div>

            {{-- Documento 2: CURP (no subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">CURP</span>
                    <span class="documento-estado no-subido">{{ __('messages.document_not_uploaded') }}</span>
                </div>
                <span class="estado-sin-boton">—</span>
            </div>

            {{-- Documento 3: Certificado de bachillerato (no subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Certificado de bachillerato</span>
                    <span class="documento-estado no-subido">{{ __('messages.document_not_uploaded') }}</span>
                </div>
                <span class="estado-sin-boton">—</span>
            </div>

            {{-- Documento 4: Constancia de estudios (subido) --}}
            <div class="documento-item">
                <div class="documento-info">
                    <span class="documento-nombre">Constancia de estudios</span>
                    <span class="documento-estado subido">{{ __('messages.document_uploaded') }}</span>
                </div>
                <button class="btn-ver-documento" onclick="alert('Ver documento: Constancia de estudios')">
                    <img src="{{ asset('img/ojo.png') }}" alt="Ver" class="btn-icon">
                    {{ __('messages.document_view') }}
                </button>
            </div>
        </div>
    </div>
@endsection

{{-- SCRIPTS ADICIONALES --}}
@push('scripts')
<script>
    {{-- 
        FUNCIONALIDAD JAVASCRIPT:
        - Botón generar PDF (alerta de ejemplo)
    --}}
    document.addEventListener('DOMContentLoaded', function() {
        const btnPdf = document.querySelector('.btn-generar-pdf');
        if (btnPdf) {
            btnPdf.addEventListener('click', function() {
                alert('Generando PDF del expediente...');
            });
        }
    });
</script>
@endpush