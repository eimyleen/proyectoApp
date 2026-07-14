{{-- 
    ============================================================
    ALUMNO - DASHBOARD PRINCIPAL
    ============================================================
    Esta es la vista principal del módulo de alumno.
    Muestra:
    - Botones laterales para navegar a Expediente y Calificaciones
    - Tabla de materias (con docente)
    - Tabla de horario (días de la semana)
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_alumno.css
    - Comparte botones con: alumno_expediente y alumno_calificaciones
    ============================================================ 
--}}

{{-- 
    Extiende el layout base del dashboard.
    Esto significa que esta vista se "inyecta" dentro del layout
    en la sección @yield('content')
--}}
@extends('layouts.dashboard')

{{-- ======================================================
     SECCIONES QUE LLENAN EL LAYOUT
     ====================================================== --}}

{{-- Título de la página (aparece en la pestaña del navegador) --}}
@section('title', 'Mi Panel - Alumno')

{{-- Rol del usuario que se muestra en el badge del header --}}
@section('user-role', 'Alumno')

{{-- Iniciales del avatar (CM = Carlos Martínez) --}}
@section('avatar-iniciales', 'CM')

{{-- Nombre completo del usuario --}}
@section('nombre-completo', 'Carlos Martínez')

{{-- Mensaje de bienvenida que aparece en el header --}}
@section('welcome-message', '¡Bienvenido, Carlos!')

{{-- Subtítulo descriptivo debajo del mensaje de bienvenida --}}
@section('subtitle', 'Aquí puedes consultar tu información académica')

{{-- ======================================================
     CSS ADICIONAL
     ====================================================== 
     Aquí se agrega el CSS específico para el módulo de alumno.
     Este archivo contiene los estilos de:
     - Botones laterales
     - Tablas (materias y horario)
     - Diseño responsive
--}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_alumno.css') }}">
@endpush

{{-- ======================================================
     CONTENIDO PRINCIPAL
     ====================================================== 
     Todo lo que esté dentro de @section('content') se
     inyectará en el layout, en el lugar donde está @yield('content')
--}}
@section('content')
    {{-- 
        CONTENEDOR PRINCIPAL CON FLEX
        Organiza en dos columnas:
        - Izquierda: Botones laterales
        - Derecha: Tablas (materias y horario)
    --}}
    <div class="contenido-con-botones">
        
        {{-- ======================================================
             BOTONES LATERALES (Columna izquierda)
             ====================================================== 
             Estos botones permiten navegar entre las secciones
             del módulo de alumno:
             - "Expediente" → redirige a alumno_expediente
             - "Calificaciones" → redirige a alumno_calificaciones
             
             El botón "Expediente" tiene la clase 'active' porque
             estamos en la vista principal del alumno.
        --}}
        <div class="botones-laterales">
            {{-- 
                Botón EXPEDIENTE (activo)
                'active' indica que estamos en esta sección.
                Al hacer clic, redirige al expediente del alumno.
            --}}
            <button class="btn-expediente">Expediente</button>
            
            {{-- 
                Botón CALIFICACIONES (inactivo)
                Al hacer clic, redirige a la vista de calificaciones.
            --}}
            <button class="btn-calificaciones">Calificaciones</button>
        </div>

        {{-- ======================================================
             TABLAS (Columna derecha)
             ====================================================== --}}
        <div class="tablas-contenedor">
            
            {{-- ==================================================
                 TABLA DE MATERIAS
                 ================================================== 
                 Muestra las materias que el alumno está cursando
                 y el docente que las imparte.
                 
                 ESTRUCTURA DE LA TABLA:
                 - Header (azul oscuro): "Materia" y "Docente"
                 - Cuerpo: Filas con los datos (actualmente vacías)
                 
                 NOTA: Los datos se llenarán dinámicamente desde
                 el controlador con un @foreach
            --}}
            <div class="materias-titulo">
                <h3>Mis materias</h3>
            </div>
            <div class="tabla-materias">
                <table>
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Docente</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 
                            ESTAS FILAS SON DE EJEMPLO
                            Se reemplazarán con datos reales del controlador:
                            @foreach($materias as $materia)
                                <tr>
                                    <td>{{ $materia->nombre }}</td>
                                    <td>{{ $materia->docente->nombre }}</td>
                                </tr>
                            @endforeach
                        --}}
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                    </tbody>
                </table>
            </div>

            {{-- ==================================================
                 TABLA DE HORARIO
                 ================================================== 
                 Muestra los días de la semana con el horario de clases.
                 
                 ESTRUCTURA DE LA TABLA:
                 - Header: "Día" y "Horario"
                 - Cuerpo: Lunes a Domingo
                 - Sábado y Domingo: Tienen clase "dia-bloqueado" 
                 
                 NOTA: Los horarios reales se llenarán desde el controlador.
            --}}
            <div class="horario-titulo">
                <h3>Mi horario</h3>
            </div>
            <div class="tabla-horario">
                <table>
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Horario</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 
                            Días de la semana con horario.
                            Sábado y Domingo tienen la clase 'dia-bloqueado'
                            que los estiliza en gris e itálico.
                        --}}
                        <tr><td>Lunes</td><td></td></tr>
                        <tr><td>Martes</td><td></td></tr>
                        <tr><td>Miércoles</td><td></td></tr>
                        <tr><td>Jueves</td><td></td></tr>
                        <tr><td>Viernes</td><td></td></tr>
                        <tr><td>Sábado</td><td class="dia-bloqueado">Sin clases</td></tr>
                        <tr><td>Domingo</td><td class="dia-bloqueado">Sin clases</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection