{{-- 
    ============================================================
    ADMIN - LOGS DEL SISTEMA
    ============================================================
    Esta vista muestra el historial de actividades del sistema.
    Muestra:
    - Tabla con todos los logs registrados
    - Columnas: ID, Fecha, Usuario, Rol, Acción, Descripción
    - Badges de colores según el rol del usuario
    
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
@section('title', __('messages.logs_title'))
@section('subtitle', __('messages.logs_subtitle'))

@section('title', 'Logs del Sistema')
@section('subtitle', 'Consulta el historial de actividades')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
--}}
@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

{{-- 
    URL DE REGRESO
    Define a dónde redirige el botón de regreso.
    En este caso, al dashboard de administrador.
--}}
@section('back-url', '/dashboard/admin')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    <div class="logs-container">
        
        {{-- ======================================================
             HEADER DE LA PÁGINA
             ====================================================== --}}
        <div class="page-header">
            <h2>{{ __('messages.logs_header') }}</h2>
        </div>

        {{-- ======================================================
             TABLA DE LOGS - Usa las mismas clases que admin
             ====================================================== --}}
        <div class="tabla-container">
            <div class="tabla-logs">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.column_id_log') }}</th>
                            <th>{{ __('messages.column_datetime') }}</th>
                            <th>{{ __('messages.column_user') }}</th>
                            <th>{{ __('messages.column_role') }}</th>
                            <th>{{ __('messages.column_action') }}</th>
                            <th>{{ __('messages.column_description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $log->user->name }} {{ $log->user->apellido }}</td>
                                <td>
                                    <span class="badge-rol {{ $log->user->role }}">
                                        {{ ucfirst($log->user->role) }}
                                    </span>
                                </td>
                                <td>{{ $log->accion }}</td>
                                <td>{{ $log->descripcion ?? __('messages.log_no_detail') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- SCRIPTS ADICIONALES --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Página de logs cargada');
    });
</script>
@endpush