{{-- 
    ============================================================
    ALUMNO - CALIFICACIONES
    ============================================================
    Esta vista muestra las calificaciones del alumno con:
    - Botones laterales (Expediente, Calificaciones activo, Logo carrera)
    - Filtro de período para seleccionar el período académico
    - Tabla de calificaciones (Materia + Calificación)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_alumno.css
    - Botón de regreso: visible (back-button)
    - Comparte botones con: alumno y alumno_expediente
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- ======================================================
     SECCIONES QUE LLENAN EL LAYOUT
     ====================================================== --}}

{{-- Título de la página (aparece en la pestaña del navegador) --}}
@section('title', 'Mis Calificaciones - Alumno')

{{-- Rol del usuario que se muestra en el badge del header --}}
@section('user-role', 'Alumno')

{{-- Iniciales del avatar (CM = Carlos Martínez) --}}
@section('avatar-iniciales', 'CM')

{{-- Nombre completo del usuario --}}
@section('nombre-completo', 'Carlos Martínez')

{{-- Mensaje de bienvenida en el header --}}
@section('welcome-message', 'Mis Calificaciones')

{{-- Subtítulo descriptivo --}}
@section('subtitle', 'Aquí puedes consultar tus calificaciones por período')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
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
        - Izquierda: Botones laterales (Expediente, Calificaciones activo, Logo carrera)
        - Derecha: Contenido de calificaciones (filtro + tabla)
    --}}
    <div class="contenido-con-botones">
        
        {{-- ======================================================
             BOTONES LATERALES (Columna izquierda)
             ====================================================== 
             - "Expediente" → redirige a alumno_expediente
             - "Calificaciones" tiene la clase 'active' porque estamos en esta sección
             - "Logo de carrera" muestra el logo circular de la carrera del alumno
        --}}
        <div class="botones-laterales">
            
            {{-- Botón EXPEDIENTE (inactivo) --}}
            <button class="btn-expediente">Expediente</button>
            
            {{-- Botón CALIFICACIONES (activo) --}}
            <button class="btn-calificaciones active">Calificaciones</button>
            
            {{-- ==================================================
                 LOGO CIRCULAR DE LA CARRERA
                 ================================================== 
                 Muestra el logo de la carrera del alumno.
                 Si no hay logo, muestra un espacio vacío.
            --}}
            <div class="carrera-logo">
                <div class="logo-circular">
                    @if(isset($carrera->logo))
                        <img src="{{ asset('storage/' . $carrera->logo) }}" alt="{{ $carrera->nombre }}">
                    @else
                        {{-- Espacio vacío si no hay logo --}}
                    @endif
                </div>
                <span class="logo-texto">Logo de la carrera</span>
            </div>
        </div>

        {{-- ======================================================
             CONTENIDO PRINCIPAL DE CALIFICACIONES (Columna derecha)
             ====================================================== --}}
        <div class="contenido-principal">
            
            {{-- ==================================================
                 FILTRO DE PERÍODO
                 ================================================== 
                 Permite al alumno seleccionar el período académico
                 para filtrar sus calificaciones.
                 
                 ESTRUCTURA:
                 - Label: "Período:"
                 - Select: Desplegable con opciones de períodos
                 
                 NOTA: Las opciones del select se llenarán desde
                 el controlador con los períodos disponibles.
            --}}
            <div class="filtro-periodo">
                <div class="periodo-select">
                    <label for="periodo">Período:</label>
                    <select id="periodo" name="periodo">
                        {{-- 
                            Opción por defecto (seleccionar período)
                            Las opciones dinámicas se agregarían con @foreach
                            Ejemplo:
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                            @endforeach
                        --}}
                        <option value="">Seleccionar período</option>
                    </select>
                </div>
            </div>

            {{-- ==================================================
                 TABLA DE CALIFICACIONES
                 ================================================== 
                 Muestra las calificaciones del alumno por materia.
                 
                 ESTRUCTURA DE LA TABLA:
                 - Header (azul oscuro): "Materia" y "Calificación"
                 - Cuerpo: Filas con materia y calificación
                 - Columna "Calificación": Tiene la clase 'calificacion'
                   que la centra y le da un ancho fijo (120px)
                 
                 NOTA: 
                 - Los datos se llenarán dinámicamente desde el controlador
                 - Cada calificación puede tener colores según su valor
                   (ej: verde para aprobado, rojo para reprobado)
            --}}
            <div class="tabla-calificaciones">
                <table>
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Calificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 
                            ESTAS FILAS SON DE EJEMPLO
                            Se reemplazarán con datos reales del controlador:
                            @foreach($calificaciones as $calificacion)
                                <tr>
                                    <td>{{ $calificacion->materia->nombre }}</td>
                                    <td class="calificacion">{{ $calificacion->nota }}</td>
                                </tr>
                            @endforeach
                        --}}
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection