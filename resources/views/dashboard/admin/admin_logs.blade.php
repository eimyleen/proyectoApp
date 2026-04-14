@extends('layouts.dashboard')

@section('title', 'Logs del Sistema')
@section('user-role', 'Administrador')
@section('avatar-iniciales', 'AD')
@section('nombre-completo', 'Admin User')
@section('welcome-message', 'Logs del Sistema')
@section('subtitle', 'Consulta el historial de actividades')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin_logs.css') }}">
@endpush

@section('content')
    <div class="logs-container">
        <div class="page-header">
            <h2>Registro de actividades</h2>
        </div>

        <div class="tabla-logs">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha y hora</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="logsTableBody">
                    @for($i = 0; $i < 8; $i++)
                        <tr>
                            <td class="id"></td>
                            <td class="fecha"></td>
                            <td class="usuario"></td>
                            <td class="rol"></td>
                            <td class="accion"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // script 
        console.log('Página de logs cargada');
    });
</script>
@endpush